<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 11:17
 */

namespace Seat\Kassie\Calendar\Models\Api;


use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Character\CharacterSheet;

class EsiToken extends Model {

    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'kassie_calendar_esi_tokens';

    protected $primaryKey = 'character_id';

    protected $fillable = [
        'character_id', 'scopes', 'access_token', 'refresh_token', 'expires_at', 'active',
    ];

    public function character()
    {
        return $this->belongsTo(CharacterSheet::class, 'character_id', 'characterID');
    }

}
