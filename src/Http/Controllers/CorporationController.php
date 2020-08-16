<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 03/01/2018
 * Time: 11:08
 */

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Kassie\Calendar\Models\Pap;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class CorporationController.
 *
 * @package Seat\Kassie\Calendar\Http\Controllers
 */
class CorporationController extends Controller
{
    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPaps(CorporationInfo $corporation)
    {
	    $today = carbon();

	    $weeklyRanking = Pap::with('character', 'character.affiliation')
            ->whereHas('character.affiliation', function ($query) use ($corporation) {
                $query->where('corporation_id', $corporation->corporation_id);
            })
            ->where('week', $today->weekOfMonth)
            ->where('month', $today->month)
            ->where('year', $today->year)
            ->select('character_id')
            ->selectRaw('SUM(value) as qty')
            ->groupBy('character_id')
            ->orderBy('qty', 'desc')
            ->get();

	    $monthlyRanking = Pap::with('character', 'character.affiliation')
            ->whereHas('character.affiliation', function ($query) use ($corporation) {
                $query->where('corporation_id', $corporation->corporation_id);
            })
            ->where('month', $today->month)
            ->where('year', $today->year)
            ->select('character_id')
            ->selectRaw('SUM(value) as qty')
            ->groupBy('character_id')
            ->orderBy('qty', 'desc')
            ->get();

	    $yearlyRanking = Pap::with('character', 'character.affiliation')
            ->whereHas('character.affiliation', function ($query) use ($corporation) {
                $query->where('corporation_id', $corporation->corporation_id);
            })
            ->where('year', $today->year)
            ->select('character_id')
            ->selectRaw('SUM(value) as qty')
            ->groupBy('character_id')
            ->orderBy('qty', 'desc')
            ->get();

        return view('calendar::corporation.paps', compact('weeklyRanking', 'monthlyRanking', 'yearlyRanking', 'corporation'));
    }

    /**
     * @param int $corporation_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getYearPapsStats(int $corporation_id)
    {
        $year = request()->query('year');
        $grouped = request()->query('grouped');

        if (is_null($year))
            $year = carbon()->year;

        if (is_null($grouped))
            $grouped = false;

        if (! $grouped)
            return response()->json(
                Pap::with('character', 'character.affiliation')
                    ->whereHas('character.affiliation', function ($query) use ($corporation_id) {
                        $query->where('corporation_id', $corporation_id);
                    })
                    ->where('year', intval($year))
                    ->select('character_id')
                    ->selectRaw('SUM(value) as qty')
                    ->groupBy('character_id')
                    ->orderBy('qty', 'desc')
                    ->get()
                    ->map(function ($pap) {
                        return [
                            'character_id' => $pap->character_id,
                            'name'         => $pap->character->name,
                            'qty'          => $pap->qty,
                        ];
                    })
                    ->sortBy('name')
                    ->values());

        return response()->json(
            Pap::with('character', 'character.affiliation', 'character.user')
                ->whereHas('character.affiliation', function ($query) use ($corporation_id) {
                    $query->where('corporation_id', $corporation_id);
                })
                ->where('year', intval($year))
                ->select('character_id')
                ->selectRaw(DB::raw('SUM(value) as qty'))
                ->groupBy('character_id')
                ->orderBy('qty', 'desc')
                ->get()
                ->groupBy('character.user.id')
                ->map(function ($user) {
                    $pap = [
                        'character_id' => 0,
                        'name'         => trans('web::seat.unknown'),
                        'qty'          => 0,
                    ];

                    foreach ($user as $character) {
                        $pap['character_id'] = $character->user->main_character_id;
                        $pap['name'] = $character->user->name;
                        $pap['qty'] += $character->qty;
                    }

                    return $pap;
                })
                ->sortBy('name')
                ->values());
    }

    /**
     * @param int $corporation_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyStackedPapsStats(int $corporation_id)
    {
        $year = is_null(request()->query('year')) ? carbon()->year : intval(request()->query('year'));
        $month = is_null(request()->query('month')) ? carbon()->month : intval(request()->query('month'));
        $grouped = request()->query('grouped') ?: false;

        $paps = Pap::select('ci.character_id', 'cto.operation_id', 'analytics', 'value')
                   ->join('character_infos as ci', 'kassie_calendar_paps.character_id', 'ci.character_id')
                   ->join('character_affiliations as ca', 'ci.character_id', 'ca.character_id')
                   ->join('calendar_tag_operation as cto', 'cto.operation_id', 'kassie_calendar_paps.operation_id')
                   ->join('calendar_tags as ct', 'ct.id', 'cto.tag_id')
                   ->where('year', $year)
                   ->where('month', $month)
                   ->where('corporation_id', $corporation_id);

        if ($grouped) {
            return response()->json(
                DB::table(DB::raw("({$paps->toSql()}) as paps"))
                    ->join('refresh_tokens as rt', 'paps.character_id', 'rt.character_id')
                    ->join('users as u', 'rt.user_id', 'u.id')
                    ->mergeBindings($paps->getQuery())
                    ->select('analytics', 'main_character_id as character_id', 'name')
                    ->selectRaw('SUM(value) as qty')
                    ->groupBy('analytics', 'main_character_id', 'name')
                    ->orderBy('qty', 'desc')
                    ->orderBy('name', 'asc')
                    ->get()
            );
        }

        return response()->json(
        	DB::table(DB::raw("({$paps->addSelect('ci.name')->toSql()}) as paps"))
		        ->mergeBindings($paps->getQuery())
		        ->select('analytics', 'character_id', 'name')
                ->selectRaw('SUM(value) as qty')
		        ->groupBy('analytics', 'character_id', 'name')
                ->orderBy('qty', 'desc')
                ->orderBy('name', 'asc')
                ->get());
    }
}
