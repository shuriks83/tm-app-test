<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'long_url', 'short', 'expires_at',
    ];

	public function hits($days = 14) 
	{
		return $this->hasMany(Hits::class)
					->whereDate('created_at', '>', date_format(date_create('-'.$days.' days'), 'Y-m-d H:i:s'))
					->groupBy('ip')
					;
	}
	
}
