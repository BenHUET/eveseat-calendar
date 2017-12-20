<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Seat\Eveapi\Models\Account\ApiKeyInfoCharacters;
use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Models\Attendee;


class LookupController extends Controller
{

	public function lookupCharacters(Request $request)
	{
		$characters = ApiKeyInfoCharacters::where('characterName', 'LIKE', '%' . $request->input('query') . '%')->take(5)->get()->unique('characterID');

		$results = array();

		foreach ($characters as $character) {
			array_push($results, array(
				"value" => $character->characterName, 
				"data" => $character->characterID
			));
		}

		return response()->json(array('suggestions' => $results));
	}

	public function lookupSystems(Request $request)
	{
		$systems = DB::table('invUniqueNames')->where([
			['groupID', '=', 5],
			['itemName', 'like', $request->input('query') . '%']
		])->take(10)->get();

		$results = array();

		foreach ($systems as $system) {
			array_push($results, array(
				"value" => $system->itemName, 
				"data" => $system->itemID
			));
		}

		return response()->json(array('suggestions' => $results));
	}

	public function lookupAttendees(Request $request)
	{
		$attendees = Attendee::where('operation_id', $request->input('id'))
			->with(['character' => function ($query) {
				$query->select('characterID', 'characterName', 'corporationID');
			}])
			->select('character_id', 'user_id', 'status', 'comment AS _comment', 'created_at', 'updated_at')
			->get();
		
		return Datatables::collection($attendees)
			->removeColumn('character_id', 'main_character', 'user_id', 'status', 'character', 'created_at', 'updated_at')
			->addColumn('_character', function ($row) {
                return view('calendar::operation.includes.cols.attendees.character', compact('row'))->render();
            })
            ->addColumn('_character_name', function ($row) {
                return $row->character->characterName;
            })
            ->addColumn('_status', function ($row) {
                return view('calendar::operation.includes.cols.attendees.status', compact('row'))->render();
            })
            ->addColumn('_timestamps', function ($row) {
                return view('calendar::operation.includes.cols.attendees.timestamps', compact('row'))->render();
            })
			->make(true);
	}

}
