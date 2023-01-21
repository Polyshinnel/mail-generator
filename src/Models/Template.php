<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates';
    protected $fillable = [
        'id',
        'name',
        'img',
        'date_create',
        'json'
    ];
}