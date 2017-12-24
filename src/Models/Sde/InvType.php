<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 11:28
 */

namespace Seat\Kassie\Calendar\Models\Sde;


use Illuminate\Database\Eloquent\Model;

class InvType extends Model {

    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'invTypes';

    protected $primaryKey = 'typeID';

    public function group()
    {
        return $this->belongsTo(InvGroup::class, 'groupID', 'groupID');
    }

}
