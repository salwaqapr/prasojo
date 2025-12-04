<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    public function index()
    {
        return view('pages.kegiatan', [
            'pageTitle' => 'Kegiatan'
        ]);
    }
}
