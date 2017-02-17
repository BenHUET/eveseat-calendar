<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Account\ApiKeyInfoCharacters;
use Seat\Web\Http\Controllers\Controller;

class LookupController extends Controller
{

	public function lookupCharacters(Request $request)
	{
		$characters = ApiKeyInfoCharacters::where('characterName', 'LIKE', '%' . $request->input('query') . '%')->take(5)->get();

		$results = array();

		foreach ($characters as $character) {
			array_push($results, array(
				"value" => $character->characterName, 
				"data" => $character->characterID
			));
		}

		return response()->json(array('suggestions' => $results));
	}

}