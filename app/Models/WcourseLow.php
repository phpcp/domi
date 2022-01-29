<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WcourseLow extends Model
{
    use HasFactory;
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
