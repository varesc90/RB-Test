<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $dates = ['expiry'];


    public function setFromStoreRequestData($data)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        substr(str_shuffle($permitted_chars), 0, 16);
        $url = new Url();
        $url->origin = $data['origin'];
        $url->expiry = new \DateTime('+' . $data['age'] . ' day');
        $url->code = substr(str_shuffle($permitted_chars), 0, 5);
        return $url;
    }

}
