<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IuranPayment extends Model
{
  protected $fillable = ['member_id', 'tanggal', 'bulan', 'tahun', 'nominal'];

  public function member()
  {
    return $this->belongsTo(IuranMember::class, 'member_id');
  }
}
