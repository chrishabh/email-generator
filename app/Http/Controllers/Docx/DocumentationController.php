<?php

namespace App\Http\Controllers\Docx;
 
use App\Http\Controllers\Controller; 
class DocumentationController extends Controller
{

    private $pages = [
        'overview'               => ['title' => 'Bouncee API Documentation', 'view' => 'docx.index'],
        'authentication'         => ['title' => 'Authentication', 'view' => 'docx.authentication'],
        'request-url-format'     => ['title' => 'Request - URL Formats', 'view' => 'docx.request-url-format'],
        'responses'              => ['title' => 'Responses', 'view' => 'docx.responses'],
        'rate-limiting'          => ['title' => 'Rate Limiting', 'view' => 'docx.rate-limiting'],
        'http-status-codes'      => ['title' => 'HTTP Status Codes', 'view' => 'docx.http-status-codes'],
        'single-validation-api'  => ['title' => 'Single Validation API', 'view' => 'docx.single-validation-api'],
        'get-credit-balance'     => ['title' => 'Get credit balance', 'view' => 'docx.get-credit-balance']
    ];

    public function index(){
        return view('docx.index');
    }

    public function show($page)
    {
        if (!isset($this->pages[$page])) {
            abort(404); // Page not found
        } 

        return view($this->pages[$page]['view'], ['title' => $this->pages[$page]['title']]);
    }
}
