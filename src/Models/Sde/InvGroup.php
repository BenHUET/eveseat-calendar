<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 17:52
 */

namespace Seat\Kassie\Calendar\Models\Sde;


use Illuminate\Database\Eloquent\Model;

class InvGroup extends Model {

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'invGroups';

    protected $primaryKey = 'groupID';

}
