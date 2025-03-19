<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ApiViewComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $text;
    public $type;
    public function __construct($text,$type)
    {
        $this->text = $text;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.docx.api-view-component');
    }
}
