<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Kassie\Calendar\Models\Pap;
use Yajra\Datatables\Facades\Datatables;
use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Models\Attendee;


class LookupController extends Controller
{

    public function lookupCharacters(Request $request)
    {
        $characters = CharacterInfo::where('name', 'LIKE', '%' . $request->input('query') . '%')
            ->take(5)
            ->get()
            ->unique('character_id');

        $results = array();

        foreach ($characters as $character) {
            array_push($results, array(
                "value" => $character->name,
                "data" => $character->character_id
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
                $query->select('character_id', 'name', 'corporation_id');
            }])
            ->select('character_id', 'user_id', 'status', 'comment AS _comment', 'created_at', 'updated_at')
            ->get();

        return Datatables::collection($attendees)
            ->removeColumn('character_id', 'main_character', 'user_id', 'status', 'character', 'created_at', 'updated_at')
            ->addColumn('_character', function ($row) {
                return view('calendar::operation.includes.cols.attendees.character', compact('row'))->render();
            })
            ->addColumn('_character_name', function ($row) {
                return $row->character->name;
            })
            ->addColumn('_status', function ($row) {
                return view('calendar::operation.includes.cols.attendees.status', compact('row'))->render();
            })
            ->addColumn('_timestamps', function ($row) {
                return view('calendar::operation.includes.cols.attendees.timestamps', compact('row'))->render();
            })
            ->make(true);
    }

    public function lookupConfirmed(Request $request)
    {
        $confirmed = Pap::with(['character:character_id,name,corporation_id', 'type:typeID,typeName,groupID', 'type.group:groupID,groupName'])
            ->where('operation_id', $request->input('id'))
            ->select('character_id', 'ship_type_id')
            ->get();

        return Datatables::collection($confirmed)
            ->removeColumn('ship_type_id', 'character_id')
            ->editColumn('character.character_id', function ($row) {
                return view('calendar::operation.includes.cols.confirmed.character', compact('row'))->render();
            })
            ->editColumn('character.corporation_id', function ($row) {
                return view('calendar::operation.includes.cols.confirmed.corporation', compact('row'))->render();
            })
            ->editColumn('type.typeID', function ($row) {
                return view('calendar::operation.includes.cols.confirmed.ship', compact('row'))->render();
            })
            ->make(true);
    }

}
