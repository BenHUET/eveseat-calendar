<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Kassie\Calendar\Models\Pap;
use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Models\Attendee;

/**
 * Class LookupController.
 *
 * @package Seat\Kassie\Calendar\Http\Controllers
 */
class LookupController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookupCharacters(Request $request)
    {
        $characters = CharacterInfo::where('name', 'LIKE', '%' . $request->input('query') . '%')
            ->take(5)
            ->get()
            ->unique('character_id');

        $results = [];

        foreach ($characters as $character) {
            $results[] = ["value" => $character->name, "data" => $character->character_id];
        }

        return response()->json(['suggestions' => $results]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookupSystems(Request $request)
    {
        $systems = DB::table('invUniqueNames')->where([
            ['groupID', '=', 5],
            ['itemName', 'like', $request->input('query') . '%']
        ])->take(10)->get();

        $results = [];

        foreach ($systems as $system) {
            $results[] = ["value" => $system->itemName, "data" => $system->itemID];
        }

        return response()->json(['suggestions' => $results]);
    }

    /**
     * @return mixed
     */
    public function lookupAttendees(Request $request)
    {
        $attendees = Attendee::where('operation_id', $request->input('id'))
            ->with('character:character_id,name', 'character.affiliation:corporation_id')
            ->select('character_id', 'user_id', 'status', 'comment AS _comment', 'created_at', 'updated_at')
            ->get();

        return app('DataTables')::collection($attendees)
            ->removeColumn('character_id', 'main_character', 'user_id', 'status', 'character', 'created_at', 'updated_at')
            ->addColumn('_character', fn($row) => view('web::partials.character', ['character' => $row->character]))
            ->addColumn('_character_name', fn($row) => is_null($row->character) ? '' : $row->character->name)
            ->addColumn('_status', fn($row) => view('calendar::operation.includes.cols.attendees.status', ['row' => $row]))
            ->addColumn('_timestamps', fn($row) => view('calendar::operation.includes.cols.attendees.timestamps', ['row' => $row]))
            ->rawColumns(['_character', '_status', '_timestamps'])
            ->toJson();
    }

    /**
     * @return mixed
     */
    public function lookupConfirmed(Request $request)
    {
        $confirmed = Pap::with([
                'character:character_id,name',
                'character.affiliation',
                'user:id',
                'type:typeID,typeName,groupID',
                'type.group:groupID,groupName'
            ])
            ->where('operation_id', $request->input('id'))
            ->select('character_id', 'ship_type_id')
            ->get();

        return app('DataTables')::collection($confirmed)
            ->removeColumn('ship_type_id', 'character_id')
            ->editColumn('character.character_id', fn($row) => view('web::partials.character', ['character' => $row->character]))
            ->editColumn('character.corporation_id', fn($row) => view('web::partials.corporation', ['corporation' => $row->character->affiliation->corporation]))
            ->editColumn('type.typeID', fn($row) => view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName]))
            ->toJson();
    }
}
