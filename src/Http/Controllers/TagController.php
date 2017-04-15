<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;

use Seat\Web\Http\Controllers\Controller;

use Seat\Kassie\Calendar\Models\Tag;

class TagController extends Controller
{

	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:7',
			'bg_color' => [
				'required', 
				'regex:^#(?:[0-9a-fA-F]{3}){1,2}$^'
			],
			'text_color' => [
				'required', 
				'regex:^#(?:[0-9a-fA-F]{3}){1,2}$^'
			],
			'order' => 'required'
		]);

		$tag = new Tag($request->all());

		$tag->save();

		return redirect()->back();
	}

	public function delete(Request $request)
	{
		$tag = Tag::find($request->tag_id);
		if ($tag != null) {
			Tag::destroy($tag->id);
			return redirect()->back();
		}

		return redirect()->back();
	}

}