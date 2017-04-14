<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;

use Seat\Kassie\Calendar\Models\Operation;

class Tag extends Model
{
	public $timestamps = false;
	protected $table = 'calendar_tags';
	protected $fillable = [
		'name',
		'bg_color',
		'text_color'
	];

	public function operations()
	{
		return $this->belongsToMany(Operation::class, 'calendar_tag_operation');
	}
}