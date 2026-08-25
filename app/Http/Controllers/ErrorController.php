<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function error404 (){
        return response()->view('error_pages.404Error', [], 404);
    }

    public function proveedorNoEncontrado(Request $request)
    {
        return response()->view('error_pages.proveedorNoEncontrado', [
            'number_id' => $request->query('number_id'),
            'document_type' => $request->query('document_type'),
        ], 404);
    }
}
