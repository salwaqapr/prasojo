<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IuranMember extends Model
{
  protected $fillable = ['kode', 'nama'];

  public function payments()
  {
    return $this->hasMany(IuranPayment::class, 'member_id');
  }
}
