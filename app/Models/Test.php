<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = \Illuminate\Support\Str::uuid();
        });
    }


    protected $fillable = [
        'jsonData'
    ];

    protected $casts = [
        'jsonData' => 'array',
    ];

    public static $rules = [
        'jsonData' => 'required'
    ];
}


