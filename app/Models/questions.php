<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;
    protected $fillable = [
        'texte',
        'image',
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
