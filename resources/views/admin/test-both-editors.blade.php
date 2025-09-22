@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Both Editors Test</li>
                    </ol>
                </div>
                <h4 class="page-title">Rich Text Editors Comparison</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">TinyMCE Rich Editor</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="tinymce_content" class="form-label">TinyMCE Editor</label>
                        @include('admin.components.rich-text-editor', [
                            'name' => 'tinymce_content',
                            'value' => '<p>This is TinyMCE rich text editor!</p><p>It should have a full toolbar with formatting options.</p>',
                            'height' => 300,
                            'placeholder' => 'TinyMCE editor here...'
                        ])
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> TinyMCE Features:</h6>
                        <ul class="mb-0">
                            <li>Full formatting toolbar</li>
                            <li>Image upload & embedding</li>
                            <li>Video embedding</li>
                            <li>Table creation</li>
                            <li>Link management</li>
                            <li>Auto-save</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Simple Rich Editor</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="simple_content" class="form-label">Simple Rich Editor</label>
                        @include('admin.components.simple-rich-editor', [
                            'name' => 'simple_content',
                            'value' => '<p>This is the simple rich text editor!</p><p>It has basic formatting buttons below the textarea.</p>',
                            'height' => 300,
                            'placeholder' => 'Simple editor here...'
                        ])
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle me-1"></i> Simple Editor Features:</h6>
                        <ul class="mb-0">
                            <li>Basic formatting (bold, italic, underline)</li>
                            <li>HTML insertion (paragraphs, lists, links)</li>
                            <li>Image tag insertion</li>
                            <li>Always works (no external dependencies)</li>
                            <li>Fast loading</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Debug Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>TinyMCE Status:</h6>
                            <div id="tinymce-status" class="alert alert-warning">Checking...</div>
                        </div>
                        <div class="col-md-6">
                            <h6>Page Status:</h6>
                            <div id="page-status" class="alert alert-info">Loading...</div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6>Instructions:</h6>
                        <ol>
                            <li><strong>TinyMCE Editor (Left):</strong> Should show a full rich text editor with toolbar</li>
                            <li><strong>Simple Editor (Right):</strong> Should show a textarea with formatting buttons below</li>
                            <li>Try typing in both editors</li>
                            <li>Use the formatting buttons in the simple editor</li>
                            <li>Check browser console for any errors</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('page-status').innerHTML = '✅ Page loaded successfully';
    
    // Check TinyMCE status
    setTimeout(function() {
        if (typeof tinymce !== 'undefined') {
            document.getElementById('tinymce-status').innerHTML = '✅ TinyMCE loaded successfully';
            document.getElementById('tinymce-status').className = 'alert alert-success';
        } else {
            document.getElementById('tinymce-status').innerHTML = '❌ TinyMCE failed to load';
            document.getElementById('tinymce-status').className = 'alert alert-danger';
        }
    }, 2000);
});
</script>
@endsection
