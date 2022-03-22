<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hits extends Model
{
    protected $fillable = [
        'links_id', 'ip',
    ];
	
}
