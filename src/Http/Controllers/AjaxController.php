<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 27/05/2017
 * Time: 18:20
 */

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Seat\Kassie\Calendar\Models\Operation;

/**
 * Class AjaxController.
 * @package Seat\Kassie\Calendar\Http\Controllers
 */
class AjaxController
{
    /**
     * @return mixed
     */
    public function getOngoing()
    {
        $operations = Operation::with('tags', 'fleet_commander', 'attendees')
                               ->where('start_at', '<', carbon()->now())
                               ->where('end_at', '>', carbon()->now())
                               ->where('is_cancelled', false);

        return $this->buildOperationDataTable($operations);
    }

    /**
     * @return mixed
     */
    public function getIncoming()
    {
        $operations = Operation::with('tags', 'fleet_commander', 'attendees')
            ->where('start_at', '>', carbon()->now())
            ->where('is_cancelled', false);

        return $this->buildOperationDataTable($operations);
    }

    /**
     * @return mixed
     */
    public function getFaded()
    {
        $operations = Operation::with('tags', 'fleet_commander', 'attendees')
                               ->where(function ($query) {
                                   $query->where('start_at', '<', carbon()->now())
                                         ->where('end_at', '<', carbon()->now());
                               })
                               ->orWhere('is_cancelled', true);


        return $this->buildOperationDataTable($operations);
    }

    /**
     * @param $operation_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDetail($operation_id)
    {
        if (auth()->user()->has('calendar.view', false)) {
            $op = Operation::find($operation_id)->load('tags');
            return view('calendar::ajax.detail_body', compact('op'));
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param Builder $operations
     * @return mixed
     */
    private function buildOperationDataTable(Builder $operations)
    {
        return app('datatables')::of($operations)
            ->editColumn('title', function ($row) {
                return sprintf('<span>%s</span><span class="pull-right">%s</span>',
                    $row->title, view('calendar::operation.includes.attendees', ['op' => $row]));
            })
            ->editColumn('tags', function ($row) {
                return view('calendar::operation.includes.tags', ['op' => $row]);
            })
            ->editColumn('importance', function ($row) {
                return view('calendar::operation.includes.importance', ['op' => $row]);
            })
            ->editColumn('start_at', function ($row) {
                return sprintf('<span data-toggle="tooltip" title="%s">%s</span>',
                    $row->start_at, human_diff($row->start_at));
            })
            ->editColumn('end_at', function ($row) {
                return sprintf('<span data-toggle="tooltip" title="%s">%s</span>',
                    $row->end_at, human_diff($row->end_at));
            })
            ->editColumn('fleet_commander', function ($row) {
                return view('calendar::operation.includes.fleet_commander', ['op' => $row]);
            })
            ->addColumn('duration', function ($row) {
                return sprintf('<span data-toggle="tooltip" title="%s">%s</span>',
                    $row->end_at, $row->duration);
            })
            ->editColumn('staging_sys', function ($row) {
                return view('calendar::operation.includes.staging', ['op' => $row]);
            })
            ->addColumn('subscription', function ($row) {
                return view('calendar::operation.includes.subscription', ['op' => $row]);
            })
            ->addColumn('actions', function ($row) {
                return view('calendar::operation.includes.actions', ['op' => $row]);
            })
            ->setRowClass(function ($row) {
                return $row->is_cancelled == 0 ? 'text-muted' : 'danger text-muted';
            })
            ->addRowAttr('data-attr-op', function ($row) {
                return $row->id;
            })
            ->rawColumns(['title', 'tags', 'importance', 'start_at', 'end_at', 'duration',
                          'fleet_commander', 'staging_sys', 'subscription', 'actions'])
            ->make(true);
    }
}
