<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'ip',
        'credentials_path',
        'cpu',
        'memory',
        'disk_space',
        'disk_space_left',
        'bandwidth',
        'port',
        'provider',
        'status',
        'created_by',
        'updated_by',
    ];
}
