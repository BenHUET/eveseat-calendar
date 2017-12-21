<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 14:24
 */

namespace Seat\Kassie\Calendar\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Seat\Kassie\Calendar\Models\Pap;
use Seat\Kassie\Calendar\Models\Sde\InvType;
use Seat\Web\Http\Controllers\Controller;

class CharacterController extends Controller {

    public function paps($character_id)
    {
        $monthlyPaps = Pap::where('character_id', $character_id)
            ->select('character_id', 'year', 'month', DB::raw('count(*) as qty'))
            ->groupBy('year', 'month')
            ->get();

        $shipTypePaps = InvType::rightJoin('invGroups', 'invGroups.groupID', '=', 'invTypes.groupID')
            ->leftJoin('kassie_calendar_paps', 'ship_type_id', '=', 'typeID')
            ->where('categoryID', 6)
            ->where(function($query) use ($character_id) {
                $query->where('character_id', $character_id)
                    ->orWhere('character_id', null);
            })
            ->select('invGroups.groupID', 'categoryID', 'groupName', DB::raw('count(operation_id) as qty'))
            ->groupBy('invGroups.groupID')
            ->orderBy('groupName')
            ->get();

        return view('calendar::character.paps', compact('monthlyPaps', 'shipTypePaps'));
    }

}
