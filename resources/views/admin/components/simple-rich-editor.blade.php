@php
    $name = $name ?? 'content';
    $value = $value ?? '';
    $height = $height ?? 300;
    $placeholder = $placeholder ?? 'Enter content...';
    $required = $required ?? false;
    $id = $id ?? null;
    $editorId = $id ?? 'simple-rich-editor-' . $name . '-' . uniqid();
@endphp

<div class="simple-rich-editor-wrapper">
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
    
    <!-- Simple formatting toolbar -->
    <div class="editor-toolbar mt-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('{{ $editorId }}', 'bold')" title="Bold">
            <i class="fas fa-bold"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('{{ $editorId }}', 'italic')" title="Italic">
            <i class="fas fa-italic"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('{{ $editorId }}', 'underline')" title="Underline">
            <i class="fas fa-underline"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('{{ $editorId }}', '<br>')" title="Line Break">
            <i class="fas fa-level-down-alt"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('{{ $editorId }}', '<p></p>')" title="Paragraph">
            <i class="fas fa-paragraph"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('{{ $editorId }}', '<ul><li></li></ul>')" title="List">
            <i class="fas fa-list"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('{{ $editorId }}', '<a href=\"\"></a>')" title="Link">
            <i class="fas fa-link"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('{{ $editorId }}', '<img src=\"\" alt=\"\">')" title="Image">
            <i class="fas fa-image"></i>
        </button>
    </div>
</div>

<style>
.editor-toolbar {
    border: 1px solid #ced4da;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    padding: 8px;
    background-color: #f8f9fa;
}

.editor-toolbar .btn {
    margin-right: 4px;
    margin-bottom: 4px;
}

.simple-rich-editor-wrapper textarea {
    border-radius: 0.375rem 0.375rem 0 0 !important;
}
</style>

<script>
function formatText(textareaId, command) {
    const textarea = document.getElementById(textareaId);
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    let formattedText = '';
    switch(command) {
        case 'bold':
            formattedText = '<strong>' + selectedText + '</strong>';
            break;
        case 'italic':
            formattedText = '<em>' + selectedText + '</em>';
            break;
        case 'underline':
            formattedText = '<u>' + selectedText + '</u>';
            break;
    }
    
    if (formattedText) {
        textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
    }
}

function insertText(textareaId, text) {
    const textarea = document.getElementById(textareaId);
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    
    textarea.value = textarea.value.substring(0, start) + text + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + text.length, start + text.length);
}
</script>
