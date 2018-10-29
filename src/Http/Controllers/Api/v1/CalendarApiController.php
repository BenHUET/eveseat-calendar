<?php
namespace Seat\Kassie\Calendar\Http\Controllers\Api\v1;
use Seat\Api\Http\Controllers\Api\v2\ApiController;
use Seat\Kassie\Calendar\Models\Operation;
/**
 * Class CalendarApiController
 * @package Seat\Kassie\Calendar\Http\Controllers\Api\v1
 */
class CalendarApiController extends ApiController
{
    /**
     * @SWG\Get(
     *     path="/calendar/operations",
     *     tags={"Calendar"},
     *     summary="Get a list of all calendar operations",
     *     description="Returns list of all calendar operations",
     *     security={"ApiKeyAuth"},
     *     @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Operation")
     *          ),
     *          examples={"application/json":
     *              {
     *                  {"title": "MOON MINING", "start_at": null, "end_at": null, "importance": "1", "description": "20% tax to Mining Lord", "staging_sys": "Jita", "staging_sys_id": 30000000, "staging_info": null, "is_cancelled": 0, "fc": "The Mittani", "fc_character_id": 12413371, "role_name": "Members" }
     *              }
     *          }
     *     ),
     *     @SWG\Response(response=400, description="Bad request"),
     *     @SWG\Response(response=401, description="Unauthorized"),
     *    )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllOperations()
    {
        $operations = Operation::all();
        return response()->json($operations->map(
            function($item){
                return [
                    'title' => $item->title,
                    'start_at' => $item->start_at,
                    'end_at' => $item->end_at,
                    'importance' => $item->importance,
                    'integration_id' => $item->integration_id,
                    'description' => $item->description,
                    'description_new' => $item->description_new,
                    'staging_sys' => $item->staging_sys,
                    'staging_sys_id' => $item->staging_sys_id,
                    'staging_info' => $item->staging_info,
                    'is_cancelled' => $item ->is_cancelled,
                    'fc' => $item->fc,
                    'fc_character_id' => $item->fc_character_id,
                    'role_name' => $item->role_name
                ];
            })
        );
    }
}
