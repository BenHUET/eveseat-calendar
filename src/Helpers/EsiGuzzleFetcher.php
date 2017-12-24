<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 11:55
 */

namespace Seat\Kassie\Calendar\Helpers;


use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Fetchers\GuzzleFetcher;

class EsiGuzzleFetcher extends GuzzleFetcher {

    public function __construct( EsiAuthentication $authentication = null ) {
        parent::__construct( $authentication );
        $this->sso_base = env('CALENDAR_SSO_BASE');
    }

}
