<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $table = 'social_links';
    
    protected $fillable = [
        'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'telegram', 'whatsapp'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
