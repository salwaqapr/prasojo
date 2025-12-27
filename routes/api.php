<?php

use App\Http\Controllers\Web\KegiatanController;

Route::get('/kegiatan',[KegiatanController::class,'apiIndex']);
