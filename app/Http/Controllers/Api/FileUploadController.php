<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use File;

class FileUploadController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        
	}

    public function store(Request $request, $company_id) {
        $file = $request->file('file');
        $destination_path = $request->get('destination_path');
   
        // echo 'File Name: '.$file->getClientOriginalName();
        // echo 'File Extension: '.$file->getClientOriginalExtension();
        // echo 'File Real Path: '.$file->getRealPath();
        // echo 'File Size: '.$file->getSize();
        // echo 'File Mime Type: '.$file->getMimeType();
    
        //Move Uploaded File
        $filename = time() . $file->getClientOriginalName();
        $file->move($destination_path, $filename);

    	return response()->json([
            'url' => url($destination_path) . '/' . $filename,
            'path' => public_path($destination_path) . '/' . $filename,
            'filename' => $filename,
        ], 200);
    }

    public function destroy(Request $request, $company_id, $filename) {
        File::delete(public_path($request->get('path')) . '/' . $filename);

        return response()->json([], 202);
    }
}