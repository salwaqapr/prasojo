<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class HakAksesController extends Controller
{
    public function index()
    {
        return view('pages.hakakses', [
            'pageTitle' => 'Hak Akses'
        ]);
    }
}
