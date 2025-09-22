@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rich Text Editor Test</li>
                    </ol>
                </div>
                <h4 class="page-title">Rich Text Editor Test</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Test Rich Text Editor</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="test_content" class="form-label">Test Content</label>
                            @include('admin.components.rich-text-editor', [
                                'name' => 'test_content',
                                'value' => '<p>This is a test of the rich text editor!</p><p>Try uploading an image or embedding a video.</p>',
                                'height' => 400,
                                'placeholder' => 'Test the rich text editor here...'
                            ])
                        </div>
                        
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-1"></i> Rich Text Editor Features:</h6>
                            <ul class="mb-0">
                                <li>✅ Text formatting (bold, italic, etc.)</li>
                                <li>✅ Image upload and embedding</li>
                                <li>✅ Video embedding (YouTube, Vimeo)</li>
                                <li>✅ Tables and lists</li>
                                <li>✅ Links and media</li>
                                <li>✅ Auto-save functionality</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Instructions</h5>
                </div>
                <div class="card-body">
                    <h6>How to Test:</h6>
                    <ol>
                        <li>Type some text in the editor</li>
                        <li>Use formatting buttons (bold, italic, etc.)</li>
                        <li>Click the image button to upload an image</li>
                        <li>Click the media button to embed a video</li>
                        <li>Try creating tables and lists</li>
                    </ol>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Note:</strong> The rich text editor is now available in all admin forms for blogs, auctions, properties, vehicles, and more!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
