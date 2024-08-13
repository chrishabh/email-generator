<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('filepond');
        
        // Validate and store the file
        $path = $file->store('uploads', 'public');

        // Return a response to FilePond
        return response()->json(['path' => $path]);
    }

    public function revert(Request $request)
    {
        // Handle file revert (if needed)
        return response()->json(['status' => 'File reverted']);
    }

    public function load(Request $request)
    {
        // Load existing files (if needed)
        return response()->json([]);
    }

}
