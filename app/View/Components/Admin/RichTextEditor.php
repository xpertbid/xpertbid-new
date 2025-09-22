<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;
use Illuminate\View\View;

class RichTextEditor extends Component
{
    public string $name;
    public string $value;
    public int $height;
    public string $placeholder;
    public bool $required;
    public string $id;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name = 'content',
        string $value = '',
        int $height = 300,
        string $placeholder = 'Enter content...',
        bool $required = false,
        string $id = null
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->height = $height;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->id = $id ?? 'rich-text-editor-' . $name . '-' . uniqid();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('admin.components.rich-text-editor');
    }
}
