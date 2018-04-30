<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 11:24
 */

namespace Seat\Kassie\Calendar\Models;


use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Kassie\Calendar\Models\Sde\InvType;

class Pap extends Model {

    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'kassie_calendar_paps';

    protected $primaryKey = [
        'operation_id', 'character_id'
    ];

    protected $fillable = [
        'operation_id', 'character_id', 'ship_type_id', 'join_time', 'value',
    ];

    public function save( array $options = [] ) {

        $operation = Operation::find($this->getAttributeValue('operation_id'));

        if (is_null($this->getAttributeValue('value')))
            $this->setAttribute('value', 0);

        if (! is_null($operation) && $operation->tags->count() > 0)
            $this->setAttribute('value', $operation->tags->max('quantifier'));

        if (array_key_exists('join_time', $this->attributes)) {
            $dt = carbon($this->getAttributeValue('join_time'));
            $this->setAttribute('week', $dt->weekOfMonth);
            $this->setAttribute('month', $dt->month);
            $this->setAttribute('year', $dt->year);
        }

        return parent::save($options);
    }

    public function character()
    {
        return $this->belongsTo(CharacterInfo::class, 'character_id', 'character_id');
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id', 'id');
    }

    public function type()
    {
        return $this->hasOne(InvType::class, 'typeID', 'type_id');
    }

}
