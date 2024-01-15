<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
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
        'name',
        'phone',
        'email',
        'document'
    ];

    protected $casts = [
        'phone' => 'array',
    ];

    public static $rules = [
        'name' => 'required',
        'phone' => 'required|object',
        'phone.countryCode' => 'required',
        'phone.regionCode' => 'required',
        'phone.number' => 'required',
        'email' => 'required|email|unique:contacts',
        'document' => 'required|unique:contacts'
    ];
}


