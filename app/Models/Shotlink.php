<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shotlink extends Model
{
    use HasFactory;
    protected $table = 'shotlink';

    protected $fillable = [
        'urlold',
        'urlnew',
        'auth_id',
    ];

}
