<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_quiz extends Model
{
    use HasFactory;
    protected $fillable = [
       
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
