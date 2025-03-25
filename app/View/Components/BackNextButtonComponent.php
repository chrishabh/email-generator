<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BackNextButtonComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $nextPageName;
    public $prevPageName;
    public $nextUrl;
    public $prevUrl;
    
    public function __construct($nextPageName, $prevPageName, $nextUrl, $prevUrl)
{
    $this->nextPageName = $nextPageName;  
    $this->prevPageName = $prevPageName;
    $this->nextUrl      = $nextUrl;
    $this->prevUrl      = $prevUrl;
}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.docx.back-next-button-component');
    }
}
