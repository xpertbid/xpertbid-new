@php
    $name = $name ?? 'content';
    $value = $value ?? '';
    $height = $height ?? 300;
    $placeholder = $placeholder ?? 'Enter content...';
    $required = $required ?? false;
    $id = $id ?? null;
    $editorId = $id ?? 'shopify-editor-' . $name . '-' . uniqid();
@endphp

<div class="shopify-editor-wrapper">
    <div class="editor-container">
        <!-- Toolbar -->
        <div class="editor-toolbar">
            <div class="toolbar-group">
                <button type="button" class="toolbar-btn" data-command="bold" title="Bold">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M4.5 2h3.5c1.5 0 2.5 1 2.5 2.5 0 1-.5 1.5-1 2 .5.5 1 1 1 2 0 1.5-1 2.5-2.5 2.5H4.5V2zm2 2v2h1.5c.5 0 1-.5 1-1s-.5-1-1-1H6.5zm0 4v2h2c.5 0 1-.5 1-1s-.5-1-1-1h-2z"/></svg>
                </button>
                <button type="button" class="toolbar-btn" data-command="italic" title="Italic">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M6 2v2h1.5l-2 8H4v2h6v-2H8.5l2-8H12V2H6z"/></svg>
                </button>
                <button type="button" class="toolbar-btn" data-command="underline" title="Underline">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M4 2v6c0 2.5 1.5 4 4 4s4-1.5 4-4V2h-2v6c0 1-.5 2-2 2s-2-1-2-2V2H4zm8 10v2H4v-2h8z"/></svg>
                </button>
            </div>
            
            <div class="toolbar-separator"></div>
            
            <div class="toolbar-group">
                <button type="button" class="toolbar-btn" data-command="insertUnorderedList" title="Bullet List">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M2 4h12v2H2V4zm0 4h12v2H2V8zm0 4h12v2H2v-2z"/></svg>
                </button>
                <button type="button" class="toolbar-btn" data-command="insertOrderedList" title="Numbered List">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M2 4h12v2H2V4zm0 4h12v2H2V8zm0 4h12v2H2v-2z"/></svg>
                </button>
            </div>
            
            <div class="toolbar-separator"></div>
            
            <div class="toolbar-group">
                <button type="button" class="toolbar-btn" data-command="createLink" title="Insert Link">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M6.5 2c-1.5 0-2.5 1-2.5 2.5s1 2.5 2.5 2.5h1v1H6.5c-2 0-3.5-1.5-3.5-3.5S4.5 1 6.5 1h1v1H6.5zm3 0v1h1c2 0 3.5 1.5 3.5 3.5S12.5 10 10.5 10h-1V9h1c1.5 0 2.5-1 2.5-2.5S12 4 10.5 4h-1z"/></svg>
                </button>
                <button type="button" class="toolbar-btn" data-command="insertImage" title="Insert Image">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M2 2h12v12H2V2zm1 1v10h10V3H3zm2 2h6v6H5V5zm1 1v4h4V6H6z"/></svg>
                </button>
            </div>
            
            <div class="toolbar-separator"></div>
            
            <div class="toolbar-group">
                <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h1" title="Heading 1">
                    <span class="heading-text">H1</span>
                </button>
                <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h2" title="Heading 2">
                    <span class="heading-text">H2</span>
                </button>
                <button type="button" class="toolbar-btn" data-command="formatBlock" data-value="h3" title="Heading 3">
                    <span class="heading-text">H3</span>
                </button>
            </div>
            
            <div class="toolbar-separator"></div>
            
            <div class="toolbar-group">
                <button type="button" class="toolbar-btn" data-command="justifyLeft" title="Align Left">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M2 2h12v2H2V2zm0 4h8v2H2V6zm0 4h12v2H2v-2zm0 4h8v2H2v-2z"/></svg>
                </button>
                <button type="button" class="toolbar-btn" data-command="justifyCenter" title="Align Center">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M4 2h8v2H4V2zm0 4h6v2H4V6zm0 4h8v2H4v-2zm0 4h6v2H4v-2z"/></svg>
                </button>
                <button type="button" class="toolbar-btn" data-command="justifyRight" title="Align Right">
                    <svg width="16" height="16" viewBox="0 0 16 16"><path d="M2 2h12v2H2V2zm2 4h8v2H4V6zm0 4h12v2H4v-2zm2 4h8v2H6v-2z"/></svg>
                </button>
            </div>
        </div>
        
        <!-- Editor Content -->
        <div class="editor-content" id="{{ $editorId }}" contenteditable="true" data-placeholder="{{ $placeholder }}">{{ old($name, $value) }}</div>
        
        <!-- Hidden input for form submission -->
        <textarea name="{{ $name }}" id="{{ $editorId }}-input" style="display: none;">{{ old($name, $value) }}</textarea>
        
        <!-- Fallback textarea in case editor doesn't work -->
        <textarea name="{{ $name }}_fallback" id="{{ $editorId }}-fallback" class="form-control mt-2" rows="8" placeholder="{{ $placeholder }}" style="display: none;">{{ old($name, $value) }}</textarea>
    </div>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<style>
.shopify-editor-wrapper {
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    overflow: hidden;
    background: var(--white);
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.shopify-editor-wrapper:focus-within {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 172, 233, 0.15);
}

.editor-toolbar {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid var(--border-color);
    gap: 12px;
    flex-wrap: wrap;
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 2px;
}

.toolbar-separator {
    width: 1px;
    height: 20px;
    background: #d1d5db;
    margin: 0 8px;
}

.toolbar-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border: none;
    background: transparent;
    border-radius: var(--border-radius);
    cursor: pointer;
    color: var(--text-light);
    transition: var(--transition);
    font-weight: 500;
}

.toolbar-btn:hover {
    background: rgba(67, 172, 233, 0.08);
    color: var(--primary-color);
    transform: translateY(-1px);
}

.toolbar-btn.active {
    background: var(--primary-color);
    color: var(--white);
    box-shadow: 0 2px 8px rgba(67, 172, 233, 0.3);
}

.toolbar-btn svg {
    fill: currentColor;
}

.heading-text {
    font-weight: 600;
    font-size: 12px;
}

.editor-content {
    min-height: {{ $height }}px;
    padding: 20px;
    outline: none;
    font-family: 'Inter', 'Poppins', sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-dark);
    background: var(--white);
}

.editor-content:empty:before {
    content: attr(data-placeholder);
    color: #9ca3af;
    pointer-events: none;
}

.editor-content h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 16px 0;
    color: #111827;
}

.editor-content h2 {
    font-size: 20px;
    font-weight: 600;
    margin: 0 0 12px 0;
    color: #111827;
}

.editor-content h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: #111827;
}

.editor-content p {
    margin: 0 0 12px 0;
}

.editor-content ul, .editor-content ol {
    margin: 0 0 12px 0;
    padding-left: 20px;
}

.editor-content li {
    margin: 4px 0;
}

.editor-content a {
    color: #3b82f6;
    text-decoration: underline;
}

.editor-content img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin: 8px 0;
}

.editor-content blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 16px;
    margin: 16px 0;
    color: #6b7280;
    font-style: italic;
}

/* Focus state */
.shopify-editor-wrapper:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .editor-toolbar {
        padding: 6px 8px;
    }
    
    .toolbar-btn {
        width: 28px;
        height: 28px;
    }
    
    .editor-content {
        padding: 12px;
        font-size: 16px; /* Prevent zoom on mobile */
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('{{ $editorId }}');
    const input = document.getElementById('{{ $editorId }}-input');
    const toolbar = editor.parentElement.querySelector('.editor-toolbar');
    
    if (!editor) return;
    
    // Update hidden input when content changes
    function updateInput() {
        input.value = editor.innerHTML;
        console.log('Shopify editor content updated:', editor.innerHTML);
    }
    
    // Handle toolbar clicks
    toolbar.addEventListener('click', function(e) {
        const btn = e.target.closest('.toolbar-btn');
        if (!btn) return;
        
        e.preventDefault();
        
        const command = btn.dataset.command;
        const value = btn.dataset.value;
        
        // Remove active state from all buttons
        toolbar.querySelectorAll('.toolbar-btn').forEach(b => b.classList.remove('active'));
        
        if (command === 'createLink') {
            const url = prompt('Enter URL:');
            if (url) {
                document.execCommand(command, false, url);
                btn.classList.add('active');
            }
        } else if (command === 'insertImage') {
            const url = prompt('Enter image URL:');
            if (url) {
                const img = document.createElement('img');
                img.src = url;
                img.alt = '';
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    selection.getRangeAt(0).insertNode(img);
                } else {
                    editor.appendChild(img);
                }
                btn.classList.add('active');
            }
        } else {
            document.execCommand(command, false, value);
            btn.classList.add('active');
        }
        
        updateInput();
        editor.focus();
    });
    
    // Update input on content change
    editor.addEventListener('input', updateInput);
    editor.addEventListener('paste', function(e) {
        setTimeout(updateInput, 10);
    });
    
    // Handle keyboard shortcuts
    editor.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'b':
                    e.preventDefault();
                    document.execCommand('bold');
                    updateInput();
                    break;
                case 'i':
                    e.preventDefault();
                    document.execCommand('italic');
                    updateInput();
                    break;
                case 'u':
                    e.preventDefault();
                    document.execCommand('underline');
                    updateInput();
                    break;
            }
        }
    });
    
    // Update active button states based on selection
    editor.addEventListener('mouseup', updateButtonStates);
    editor.addEventListener('keyup', updateButtonStates);
    
    function updateButtonStates() {
        toolbar.querySelectorAll('.toolbar-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Check bold
        if (document.queryCommandState('bold')) {
            toolbar.querySelector('[data-command="bold"]').classList.add('active');
        }
        
        // Check italic
        if (document.queryCommandState('italic')) {
            toolbar.querySelector('[data-command="italic"]').classList.add('active');
        }
        
        // Check underline
        if (document.queryCommandState('underline')) {
            toolbar.querySelector('[data-command="underline"]').classList.add('active');
        }
    }
    
    // Initial update
    updateInput();
    
    // Ensure content is updated before form submission
    const form = editor.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            updateInput();
            
            // If content is empty, try to get it from fallback
            if (!input.value || input.value.trim() === '') {
                const fallback = document.getElementById('{{ $editorId }}-fallback');
                if (fallback && fallback.value) {
                    input.value = fallback.value;
                    console.log('Using fallback content:', fallback.value);
                } else {
                    // Set a default value to prevent null error
                    input.value = '<p>No content provided</p>';
                    console.log('Setting default content');
                }
            }
        });
    }
    
    // Show fallback if editor is empty after 2 seconds
    setTimeout(function() {
        if (!editor.innerHTML || editor.innerHTML.trim() === '') {
            const fallback = document.getElementById('{{ $editorId }}-fallback');
            if (fallback) {
                fallback.style.display = 'block';
                console.log('Showing fallback textarea');
            }
        }
    }, 2000);
});
</script>
