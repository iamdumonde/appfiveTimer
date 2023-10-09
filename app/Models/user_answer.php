<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Question\Question;

class user_answer extends Model
{
    use HasFactory;
    protected $fillable = [
       
    ];
    public function possible_answer()
    {
        return $this->belongsTo(Possible_answer::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
