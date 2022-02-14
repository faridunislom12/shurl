<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'short',
        'long',
        'is_active',
    ];

    /**
     * Get the url applicant.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
