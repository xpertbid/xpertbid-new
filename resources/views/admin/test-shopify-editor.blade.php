@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Shopify-Style Editor</li>
                    </ol>
                </div>
                <h4 class="page-title">Shopify-Style Rich Text Editor</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Shopify-Style Editor Demo</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="demo_content" class="form-label">Product Description</label>
                            @include('admin.components.shopify-editor', [
                                'name' => 'demo_content',
                                'value' => '<h2>Welcome to Our Amazing Product!</h2><p>This is a <strong>beautiful</strong> and <em>elegant</em> product that will change your life.</p><ul><li>Feature 1: Amazing quality</li><li>Feature 2: Great design</li><li>Feature 3: Excellent value</li></ul><p>Don\'t miss out on this incredible opportunity!</p>',
                                'height' => 400,
                                'placeholder' => 'Start typing your product description...'
                            ])
                        </div>
                        
                        <div class="alert alert-success">
                            <h6><i class="fas fa-star me-1"></i> Shopify-Style Features:</h6>
                            <ul class="mb-0">
                                <li>✅ Clean, modern interface like Shopify</li>
                                <li>✅ Intuitive toolbar with icons</li>
                                <li>✅ Real-time formatting preview</li>
                                <li>✅ Keyboard shortcuts (Ctrl+B, Ctrl+I, Ctrl+U)</li>
                                <li>✅ Responsive design</li>
                                <li>✅ Focus states and hover effects</li>
                                <li>✅ Professional typography</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">How to Use</h5>
                </div>
                <div class="card-body">
                    <h6>Toolbar Buttons:</h6>
                    <ul class="mb-3">
                        <li><strong>Bold, Italic, Underline:</strong> Text formatting</li>
                        <li><strong>Lists:</strong> Bullet and numbered lists</li>
                        <li><strong>Link:</strong> Insert hyperlinks</li>
                        <li><strong>Image:</strong> Insert images by URL</li>
                        <li><strong>H1, H2, H3:</strong> Headings</li>
                        <li><strong>Align:</strong> Left, center, right alignment</li>
                    </ul>
                    
                    <h6>Keyboard Shortcuts:</h6>
                    <ul class="mb-3">
                        <li><kbd>Ctrl</kbd> + <kbd>B</kbd> - Bold</li>
                        <li><kbd>Ctrl</kbd> + <kbd>I</kbd> - Italic</li>
                        <li><kbd>Ctrl</kbd> + <kbd>U</kbd> - Underline</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <strong>Pro Tip:</strong> This editor works exactly like Shopify's - clean, fast, and professional!
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Test Different Content</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="short_content" class="form-label">Short Description</label>
                        @include('admin.components.shopify-editor', [
                            'name' => 'short_content',
                            'value' => '<p>Brief summary of the product...</p>',
                            'height' => 150,
                            'placeholder' => 'Short description...'
                        ])
                    </div>
                    
                    <div class="mb-3">
                        <label for="long_content" class="form-label">Detailed Description</label>
                        @include('admin.components.shopify-editor', [
                            'name' => 'long_content',
                            'value' => '',
                            'height' => 200,
                            'placeholder' => 'Detailed description with formatting...'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
