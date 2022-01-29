<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wtask extends Model
{
    use HasFactory;
	public function getVGIdAttribute($value)
	{
	    return array_values(json_decode($value, true) ?: []);
	}

	public function setVGIdAttribute($value)
	{
	    $this->attributes['v_g_id'] = json_encode(array_values($value));
	}
}
