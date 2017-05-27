<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 27/05/2017
 * Time: 18:20
 */

namespace Seat\Kassie\Calendar\Http\Controllers;

use Seat\Kassie\Calendar\Models\Operation;

class AjaxController
{
    public function getDetail($operation_id)
    {
        if (auth()->user()->has('calendar.view', false)) {
            $op = Operation::find($operation_id)->load('tags');
            return view('calendar::ajax.detail_body', compact('op'));
        }

        return redirect()->back('error', 'An error occurred while processing the request.');
    }
}