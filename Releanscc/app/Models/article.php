<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class article extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'header',
        'paragraph',
        'image1',
        'image2',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
