<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 14:24
 */

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Kassie\Calendar\Models\Pap;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class CharacterController.
 *
 * @package Seat\Kassie\Calendar\Http\Controllers
 */
class CharacterController extends Controller
{
    /**
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paps(CharacterInfo $character)
    {
        $today = carbon();

        $monthlyPaps = Pap::where('character_id', $character->character_id)
            ->select('character_id', 'year', 'month', DB::raw('sum(value) as qty'))
            ->groupBy('character_id', 'year', 'month')
            ->get();

        $shipTypePaps = InvType::rightJoin('invGroups', 'invGroups.groupID', '=', 'invTypes.groupID')
            ->leftJoin('kassie_calendar_paps', 'ship_type_id', '=', 'typeID')
            ->where('categoryID', 6)
            ->where(function($query) use ($character) {
                $query->where('character_id', $character->character_id)
                    ->orWhere('character_id', null);
            })
            ->select('invGroups.groupID', 'categoryID', 'groupName', DB::raw('sum(value) as qty'))
            ->groupBy('invGroups.groupID', 'categoryID', 'groupName')
            ->orderBy('groupName')
            ->get();

        $weeklyRanking = Pap::where('week', $today->weekOfMonth)
                         ->where('month', $today->month)
                         ->where('year', $today->year)
                         ->select('character_id', DB::raw('sum(value) as qty'))
                         ->groupBy('character_id')
                         ->orderBy('qty', 'desc')
                         ->get();

        $monthlyRanking = Pap::where('month', $today->month)
                          ->where('year', $today->year)
                          ->select('character_id', DB::raw('sum(value) as qty'))
                          ->groupBy('character_id')
                          ->orderBy('qty', 'desc')
                          ->get();

        $yearlyRanking = Pap::where('year', $today->year)
                         ->select('character_id', DB::raw('sum(value) as qty'))
                         ->groupBy('character_id')
                         ->orderBy('qty', 'desc')
                         ->get();

        return view('calendar::character.paps', compact('monthlyPaps', 'shipTypePaps',
            'weeklyRanking', 'monthlyRanking', 'yearlyRanking', 'character'));
    }
}
