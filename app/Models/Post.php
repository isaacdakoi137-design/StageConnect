<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $table = 'network_posts';

    protected $fillable = [
        'user_id',
        'content',
        'image_path',
        'likes_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
