<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;


class Tag extends Model
{
	public $timestamps = false;
	protected $table = 'calendar_tags';
	protected $fillable = [
		'name',
		'bg_color',
		'text_color',
		'order'
	];

	public function operations()
	{
		return $this->belongsToMany(Operation::class, 'calendar_tag_operation');
	}
}
