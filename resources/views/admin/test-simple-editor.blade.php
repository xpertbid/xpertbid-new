@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Simple Editor Test</li>
                    </ol>
                </div>
                <h4 class="page-title">Simple Editor Test</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Test Simple Textarea</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="simple_content" class="form-label">Simple Content</label>
                            <textarea 
                                name="simple_content" 
                                id="simple_content"
                                class="form-control"
                                rows="8"
                                placeholder="This is a simple textarea to test if basic functionality works..."
                            >This is a test of the simple textarea!</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="rich_content" class="form-label">Rich Text Editor</label>
                            @include('admin.components.rich-text-editor', [
                                'name' => 'rich_content',
                                'value' => '<p>This is a test of the rich text editor!</p><p>Try uploading an image or embedding a video.</p>',
                                'height' => 400,
                                'placeholder' => 'Test the rich text editor here...'
                            ])
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-1"></i> Test Instructions:</h6>
                            <ol>
                                <li>Check if both textareas are visible</li>
                                <li>Type in both fields to test functionality</li>
                                <li>Check browser console for any JavaScript errors</li>
                                <li>If rich text editor doesn't load, the simple textarea should still work</li>
                            </ol>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Debug Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Status:</strong><br>
                        <span id="status-text">Loading...</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>TinyMCE Status:</strong><br>
                        <span id="tinymce-status">Checking...</span>
                    </div>
                    
                    <div class="alert alert-warning">
                        <strong>Note:</strong> If you see this page, the basic textarea is working. The rich text editor should enhance it with formatting tools.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('status-text').textContent = 'Page loaded successfully';
    
    // Check TinyMCE status
    setTimeout(function() {
        if (typeof tinymce !== 'undefined') {
            document.getElementById('tinymce-status').textContent = 'TinyMCE loaded successfully';
        } else {
            document.getElementById('tinymce-status').textContent = 'TinyMCE failed to load';
        }
    }, 2000);
});
</script>
@endsection
