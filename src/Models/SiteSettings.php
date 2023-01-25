<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'id',
        'name',
        'site_addr',
        'site_addr_short',
        'site_xml',
        'logo',
        'delivery',
        'discount'
    ];

    public $timestamps = false;
}