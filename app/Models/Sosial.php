<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sosial extends Model
{
    protected $table = 'sosial';

    protected $fillable = [
        'tanggal',
        'subjek',
        'pemasukan',
        'pengeluaran',
        'saldo'
    ];

    public $timestamps = false;
}
