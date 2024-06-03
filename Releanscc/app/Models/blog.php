<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'description',
    ];

    public function articles()
    {
        return $this->hasOne(Article::class, 'blog_id', 'id');
    }
}
