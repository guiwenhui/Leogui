<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $table = 'team';
    protected $fillable = ['name', 'email', 'age', 'area'];
    public $timestamps = true;
    
    protected $casts = [
        'age' => 'integer',
    ];
}
