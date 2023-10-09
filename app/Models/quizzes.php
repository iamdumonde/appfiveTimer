<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quizzes extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'title',
        'description',
        'visibility'
    ];
    public function user_answer()
    {
        return $this->hasMany(user_answer::class);
    }
    public function user_note()
    {
        return $this->hasMany(user_note::class);
    }
    public function user_quiz()
    {
        return $this->hasMany(user_quiz::class);
    }
    public function possible_answer()
    {
        return $this->hasMany(possible_answer::class);
    }
    public function question()
    {
        return $this->hasMany(question::class);
    }
}
