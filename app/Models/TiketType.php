<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}
