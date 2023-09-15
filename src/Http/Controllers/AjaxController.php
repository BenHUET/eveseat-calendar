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
        $operations = Operation::with('tags', 'fleet_commander', 'attendees', 'staging')
                               ->where('start_at', '<', carbon()->now())
                               ->where(function ($query): void {
                                   $query->where('end_at', '>', carbon()->now());
                                   $query->orWhereNull('end_at');
                               })
                               ->where(function ($query): void {
                                   if (! auth()->user()->isAdmin()) {
                                       $query->whereIn('role_name', auth()->user()->roles->pluck('title')->toArray());
                                       $query->orWhereNull('role_name');
                                   }
                               })
                               ->where('is_cancelled', false);

        return $this->buildOperationDataTable($operations);
    }

    /**
     * @return mixed
     */
    public function getIncoming()
    {
        $operations = Operation::with('tags', 'fleet_commander', 'attendees', 'staging')
            ->where(function ($query): void {
                if (! auth()->user()->isAdmin()) {
                    $query->whereIn('role_name', auth()->user()->roles->pluck('title')->toArray());
                    $query->orWhereNull('role_name');
                }
            })
            ->where('start_at', '>', carbon()->now())
            ->where('is_cancelled', false);

        return $this->buildOperationDataTable($operations);
    }

    /**
     * @return mixed
     */
    public function getFaded()
    {
        $operations = Operation::with('tags', 'fleet_commander', 'attendees', 'staging')
                               ->where(function ($query): void {
                                   $query->where('start_at', '<', carbon()->now())
                                         ->where('end_at', '<', carbon()->now());
                               })
                               ->where(function ($query): void {
                                   if (! auth()->user()->isAdmin()) {
                                       $query->whereIn('role_name', auth()->user()->roles->pluck('title')->toArray());
                                       $query->orWhereNull('role_name');
                                   }
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
        if (auth()->user()->can('calendar.view')) {
            $op = Operation::with('tags')->find($operation_id);
            return view('calendar::operation.modals/details.content', ['op' => $op]);
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @return mixed
     */
    private function buildOperationDataTable(Builder $operations)
    {
        return app('datatables')::of($operations)
            ->editColumn('title', fn($row) => view('calendar::operation.partials.title', ['row' => $row]))
            ->editColumn('tags', fn($row) => view('calendar::operation.partials.tags', ['op' => $row]))
            ->editColumn('importance', fn($row) => view('calendar::operation.partials.importance', ['op' => $row]))
            ->editColumn('start_at', fn($row): string => sprintf('<span data-toggle="tooltip" title="%s">%s</span>',
                $row->start_at, human_diff($row->start_at)))
            ->editColumn('end_at', function ($row) {
                if ($row->end_at) {
                    return sprintf('<span data-toggle="tooltip" title="%s">%s</span>',
                        $row->end_at, human_diff($row->end_at));
                } else {
                    return '<span data-toggle="tooltip" title="no end set"></span>';
                }
            })
            ->editColumn('fleet_commander', fn($row) => view('calendar::operation.partials.fleet_commander', ['op' => $row]))
            ->addColumn('duration', fn($row): string => sprintf('<span data-toggle="tooltip" title="%s">%s</span>',
                $row->end_at, $row->duration))
            ->editColumn('staging_sys', fn($row) => view('calendar::operation.partials.staging', ['op' => $row]))
            ->addColumn('subscription', fn($row) => view('calendar::operation.partials.registration', ['op' => $row]))
            ->addColumn('actions', fn($row) => view('calendar::operation.partials.actions.actions', ['op' => $row]))
            ->setRowClass(fn($row): string => $row->is_cancelled == 0 ? 'text-muted' : 'danger text-muted')
            ->addRowAttr('data-attr-op', fn($row) => $row->id)
            ->rawColumns(['title', 'tags', 'importance', 'start_at', 'end_at', 'duration',
                          'fleet_commander', 'staging_sys', 'subscription', 'actions'])
            ->make(true);
    }
}
