@php
    $name = $name ?? 'content';
    $value = $value ?? '';
    $height = $height ?? 300;
    $placeholder = $placeholder ?? 'Enter content...';
    $required = $required ?? false;
    $id = $id ?? null;
    $editorId = $id ?? 'rich-text-editor-' . $name . '-' . uniqid();
@endphp

<div class="rich-text-editor-wrapper">
    <textarea 
        name="{{ $name }}" 
        id="{{ $editorId }}"
        class="form-control @error($name) is-invalid @enderror"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        rows="8"
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tinymce@6.8.0/tinymce.min.css" rel="stylesheet">
<style>
.tox-tinymce {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.0/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for TinyMCE...');
    
    // Wait a bit for TinyMCE to load
    setTimeout(function() {
        if (typeof tinymce === 'undefined') {
            console.error('TinyMCE failed to load from CDN');
            return;
        }
        
        console.log('TinyMCE loaded successfully, initializing editor...');
        
        tinymce.init({
        selector: '#{{ $editorId }}',
        height: {{ $height }},
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
            'template', 'codesample', 'hr', 'pagebreak', 'nonbreaking', 'toc',
            'imagetools', 'textpattern', 'noneditable', 'quickbars', 'accordion'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | image media link | code preview | ' +
            'emoticons charmap | searchreplace | table | fullscreen',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        branding: false,
        promotion: false,
        resize: true,
        elementpath: false,
        statusbar: true,
        browser_spellcheck: true,
        contextmenu: 'link image imagetools table spellchecker configurepermanentpen',
        
        // Image upload configuration
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("admin.media.upload") }}');
            
            xhr.onload = function() {
                var json;
                
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                
                json = JSON.parse(xhr.responseText);
                
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                
                success(json.location);
            };
            
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            xhr.send(formData);
        },
        
        // Media (video) upload configuration
        media_live_embeds: true,
        media_url_resolver: function (data, resolve) {
            // Handle video URLs
            if (data.url.indexOf('youtube.com') !== -1 || data.url.indexOf('youtu.be') !== -1) {
                resolve({html: '<iframe src="' + data.url + '" width="560" height="315" frameborder="0" allowfullscreen></iframe>'});
            } else if (data.url.indexOf('vimeo.com') !== -1) {
                resolve({html: '<iframe src="' + data.url + '" width="560" height="315" frameborder="0" allowfullscreen></iframe>'});
            } else {
                resolve({html: '<video controls><source src="' + data.url + '" type="video/mp4">Your browser does not support the video tag.</video>'});
            }
        },
        
        // File browser configuration
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'image') {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                
                input.onchange = function () {
                    var file = this.files[0];
                    var reader = new FileReader();
                    
                    reader.onload = function () {
                        callback(reader.result, {
                            alt: file.name
                        });
                    };
                    
                    reader.readAsDataURL(file);
                };
                
                input.click();
            } else if (meta.filetype === 'media') {
                var url = prompt('Enter video URL (YouTube, Vimeo, or direct video file):');
                if (url) {
                    callback(url);
                }
            }
        },
        
        // Custom styles
        style_formats: [
            {title: 'Headings', items: [
                {title: 'Heading 1', format: 'h1'},
                {title: 'Heading 2', format: 'h2'},
                {title: 'Heading 3', format: 'h3'},
                {title: 'Heading 4', format: 'h4'},
                {title: 'Heading 5', format: 'h5'},
                {title: 'Heading 6', format: 'h6'}
            ]},
            {title: 'Inline', items: [
                {title: 'Bold', icon: 'bold', format: 'bold'},
                {title: 'Italic', icon: 'italic', format: 'italic'},
                {title: 'Underline', icon: 'underline', format: 'underline'},
                {title: 'Strikethrough', icon: 'strikethrough', format: 'strikethrough'},
                {title: 'Superscript', icon: 'superscript', format: 'sup'},
                {title: 'Subscript', icon: 'subscript', format: 'sub'},
                {title: 'Code', icon: 'code', format: 'code'}
            ]},
            {title: 'Blocks', items: [
                {title: 'Paragraph', format: 'p'},
                {title: 'Blockquote', format: 'blockquote'},
                {title: 'Div', format: 'div'},
                {title: 'Pre', format: 'pre'}
            ]},
            {title: 'Alignment', items: [
                {title: 'Left', icon: 'alignleft', format: 'alignleft'},
                {title: 'Center', icon: 'aligncenter', format: 'aligncenter'},
                {title: 'Right', icon: 'alignright', format: 'alignright'},
                {title: 'Justify', icon: 'alignjustify', format: 'alignjustify'}
            ]}
        ],
        
        // Templates
        templates: [
            {
                title: 'Image with caption',
                description: 'Add an image with a caption',
                content: '<figure class="image"><img src="" alt=""><figcaption>Caption</figcaption></figure>'
            },
            {
                title: 'Two column layout',
                description: 'Create a two column layout',
                content: '<div class="row"><div class="col-md-6"><p>Left column content</p></div><div class="col-md-6"><p>Right column content</p></div></div>'
            }
        ],
        
        // Error handling
        init_instance_callback: function (editor) {
            console.log('TinyMCE initialized successfully for editor:', editor.id);
            // Hide the original textarea
            var textarea = document.getElementById('{{ $editorId }}');
            if (textarea) {
                textarea.style.display = 'none';
            }
        },
        
        // Setup function
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
            
            editor.on('init', function () {
                console.log('TinyMCE editor initialized for:', editor.id);
            });
        }
    });
    
    }, 1000); // Wait 1 second for TinyMCE to load
});
</script>
@endpush
