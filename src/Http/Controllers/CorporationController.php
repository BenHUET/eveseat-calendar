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
        return view('calendar::corporation.paps');
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
                   ->where('corporationID', $corporation_id)
                   ->leftJoin('character_character_sheets', 'characterID', 'character_id')
                   ->select('character_id', 'name', DB::raw('sum(value) as qty'))
                   ->groupBy('character_id', 'name')
                   ->orderBy('qty', 'desc')
                   ->orderBy('name', 'asc')
                   ->get());

        return response()->json(
            Pap::where('year', intval($year))
                ->where('aakic2.corporationID', $corporation_id)
                ->select('main_character_id as character_id', 'main_character_name as name', DB::raw('sum(value) as qty'))
                ->join('account_api_key_info_characters as aakic', 'character_id', 'aakic.characterID')
                ->join('person_members as pm', 'aakic.keyID', 'pm.key_id')
                ->join('people as p', 'pm.person_id', 'p.id')
                ->join('account_api_key_info_characters as aakic2', 'main_character_id', 'aakic2.characterID')
                ->groupBy('main_character_id', 'main_character_name')
                ->orderBy('qty', 'desc')
	            ->orderBy('main_character_name', 'asc')
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
                   ->join('account_api_key_info_characters as aakic', 'character_id', 'aakic.characterID')
                   ->join('calendar_operations as co', 'co.id', 'operation_id')
                   ->join('calendar_tag_operation as cto', 'cto.operation_id', 'co.id')
                   ->join('calendar_tags as ct', 'ct.id', 'cto.tag_id');

        if ($grouped)
	        $paps = $paps->where( 'aakic2.corporationID', $corporation_id )
	                     ->join( 'person_members as pm', 'aakic.keyID', 'pm.key_id' )
	                     ->join( 'people as p', 'pm.person_id', 'p.id' )
	                     ->join( 'account_api_key_info_characters as aakic2', 'main_character_id', 'aakic2.characterID' )
	                     ->select( 'main_character_id as character_id', 'main_character_name as name', 'cto.operation_id', 'analytics', 'value' )
	                     ->groupBy( 'main_character_id', 'main_character_name', 'cto.operation_id', 'analytics', 'value' );
        else
	        $paps = $paps->where('corporationID', $corporation_id)
	                     ->select('character_id', 'characterName as name', 'cto.operation_id', 'analytics', 'value')
	                     ->groupBy('character_id', 'characterName', 'cto.operation_id', 'analytics', 'value');

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