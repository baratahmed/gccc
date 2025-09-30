<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function index()
    {
        return response()->json([
            ['id' => 1, 'name' => 'John Doe', 'roll' => '101'],
            ['id' => 2, 'name' => 'Jane Smith', 'roll' => '102'],
        ]);
    }
}
