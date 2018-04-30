<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 03/01/2018
 * Time: 11:08
 */

namespace Seat\Kassie\Calendar\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Seat\Kassie\Calendar\Models\Pap;
use Seat\Web\Http\Controllers\Controller;

class CorporationController extends Controller {

    public function getPaps(int $corporation_id)
    {
	    $today = carbon();

	    $weeklyRanking = Pap::join('character_infos', 'kassie_calendar_paps.character_id', 'character_infos.character_id')
	                        ->where('corporation_id', $corporation_id)
	                        ->where('week', $today->weekOfMonth)
	                        ->where('month', $today->month)
	                        ->where('year', $today->year)
	                        ->select('kassie_calendar_paps.character_id', DB::raw('sum(value) as qty'))
	                        ->groupBy('character_id')
	                        ->orderBy('qty', 'desc')
	                        ->get();

	    $monthlyRanking = Pap::join('character_infos', 'kassie_calendar_paps.character_id', 'character_infos.character_id')
	                         ->where('corporation_id', $corporation_id)
	                         ->where('month', $today->month)
	                         ->where('year', $today->year)
	                         ->select('kassie_calendar_paps.character_id', DB::raw('sum(value) as qty'))
	                         ->groupBy('character_id')
	                         ->orderBy('qty', 'desc')
	                         ->get();

	    $yearlyRanking = Pap::join('character_infos', 'kassie_calendar_paps.character_id', 'character_infos.character_id')
	                        ->where('corporation_id', $corporation_id)
	                        ->where('year', $today->year)
	                        ->select('kassie_calendar_paps.character_id', DB::raw('sum(value) as qty'))
	                        ->groupBy('character_id')
	                        ->orderBy('qty', 'desc')
	                        ->get();

        return view('calendar::corporation.paps', compact('weeklyRanking', 'monthlyRanking', 'yearlyRanking'));
    }

    public function getYearPapsStats(int $corporation_id)
    {
        $year = request()->query('year');
        $grouped = request()->query('grouped');

        if (is_null($year))
            $year = carbon()->year;

        if (is_null($grouped))
            $grouped = false;

        if (!$grouped)
            return response()->json(
                Pap::where('year', intval($year))
                   ->where('corporation_id', $corporation_id)
                   ->leftJoin('character_infos', 'character_id', 'character_id')
                   ->select('character_id', 'name', DB::raw('sum(value) as qty'))
                   ->groupBy('character_id', 'name')
                   ->orderBy('qty', 'desc')
                   ->orderBy('name', 'asc')
                   ->get());

        return response()->json(
            Pap::where('year', intval($year))
                ->where('corporation_id', $corporation_id)
                ->select('ci.character_id', 'name', DB::raw('sum(value) as qty'))
                ->join('character_infos as ci', 'ci.character_id', 'kassie_calendar_paps.character_id')
                ->join('group_user as gu', 'ci.character_id', 'gu.user_id')
                ->groupBy('group_id')
                ->orderBy('qty', 'desc')
	            ->orderBy('name', 'asc')
                ->get());
    }

    public function getMonthlyStackedPapsStats(int $corporation_id)
    {
        $year = request()->query('year');
        $month = request()->query('month');
        $grouped = request()->query('grouped');

        if (is_null($year))
            $year = carbon()->year;

        if (is_null($month))
            $month = carbon()->month;

        if (is_null($grouped))
            $grouped = false;

        $paps = Pap::where('year', intval($year))
                   ->where('month', intval($month))
                   ->join('character_infos as ci', 'kassie_calendar_paps.character_id', 'ci.character_id')
                   ->join('calendar_operations as co', 'co.id', 'operation_id')
                   ->join('calendar_tag_operation as cto', 'cto.operation_id', 'co.id')
                   ->join('calendar_tags as ct', 'ct.id', 'cto.tag_id');

        if ($grouped)
	        $paps = $paps->where('corporation_id', $corporation_id )
	                     ->join('group_user as gu', 'gu.user_id', 'ci.character_id' )
	                     ->select('ci.character_id', 'ci.name', 'cto.operation_id', 'analytics', 'value' )
	                     ->groupBy('group_id', 'cto.operation_id', 'analytics', 'value' );
        else
	        $paps = $paps->where('corporation_id', $corporation_id)
	                     ->select('ci.character_id', 'ci.name', 'cto.operation_id', 'analytics', 'value')
	                     ->groupBy('ci.character_id', 'cto.operation_id', 'analytics', 'value');

        return response()->json(
        	DB::table(DB::raw("({$paps->toSql()}) as paps"))
		        ->mergeBindings($paps->getQuery())
		        ->select('analytics', 'character_id', 'name', DB::raw('sum(value) as qty'))
		        ->groupBy('character_id', 'name', 'analytics')
                ->orderBy('qty', 'desc')
                ->orderBy('name', 'asc')
		        ->distinct()
                ->get());
    }
}
