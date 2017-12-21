<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 11:24
 */

namespace Seat\Kassie\Calendar\Models;


use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Character\CharacterSheet;
use Seat\Kassie\Calendar\Models\Sde\InvType;

class Pap extends Model {

    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'kassie_calendar_paps';

    protected $primaryKey = [
        'operation_id', 'character_id'
    ];

    protected $fillable = [
        'operation_id', 'character_id', 'ship_type_id', 'join_time',
    ];

    public function save( array $options = [] ) {
        if (array_key_exists('join_time', $this->attributes)) {
            $dt = carbon($this->getAttributeValue('join_time'));
            $this->setAttribute('month', $dt->month);
            $this->setAttribute('year', $dt->year);
        }

        return parent::save($options);
    }

    public function character()
    {
        return $this->belongsTo(CharacterSheet::class, 'character_id', 'characterID');
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
