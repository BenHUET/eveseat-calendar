<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 13:12
 */

namespace Seat\Kassie\Calendar\Http\Controllers\Service;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Seat\Eveapi\Models\Character\CharacterSheet;
use Seat\Kassie\Calendar\Exceptions\MissingCharacterException;
use Seat\Kassie\Calendar\Models\Api\EsiToken;
use Seat\Web\Http\Controllers\Controller;

class EsiController extends Controller {

    public function redirectToAuth()
    {
        if (is_null(env('CALENDAR_SSO_BASE')) || env('CALENDAR_SSO_BASE') == '')
            return redirect()
                ->back()
                ->with('error', 'Administrator did not end the calendar setup. CALENDAR_SSO_BASE has not been set.');

        if (is_null(env('CALENDAR_EVE_CLIENT_ID')) || env('CALENDAR_EVE_CLIENT_ID') == '')
            return redirect()
                ->back()
                ->with('error', 'Administrator did not end the calendar setup. CALENDAR_EVE_CLIENT_ID has not been set.');

        if (is_null(env('CALENDAR_EVE_CLIENT_SECRET')) || env('CALENDAR_EVE_CLIENT_SECRET') == '')
            return redirect()
                ->back()
                ->with('error', 'Administrator did not end the calendar setup. CALENDAR_EVE_CLIENT_SECRET has not been set.');

        if (is_null(env('CALENDAR_ESI_SERVER')) || env('CALENDAR_ESI_SERVER') == '')
            return redirect()
                ->back()
                ->with('error', 'Administrator did not end the calendar setup. CALENDAR_ESI_SERVER has not been set.');

        return redirect($this->buildAuthUri());
    }

    public function authCallback()
    {
        $code = request()->query('code');
        $state = request()->query('state');

        if (is_null($state) || $state != session('calendar-auth-state'))
            return redirect()
                ->route('operation.index')
                ->with('error', 'No state transferred or state does not match.');

        if (is_null($code) || $code == '')
            return redirect()
                ->route('operation.index')
                ->with('error', 'No code transferred or code is empty.');

        try {
            $token = $this->exchangeCode( $code );
        } catch (MissingCharacterException $e) {
            return redirect()
                ->route('operation.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('operation.index')
            ->with('success', sprintf('Character %s has been successfully coupled.',
                $token->character->name));
    }

    /**
     * @param string $code
     *
     * @return EsiToken
     * @throws MissingCharacterException
     */
    private function exchangeCode(string $code) : EsiToken
    {
        $request_time = Carbon::now('UTC');

        $client = new Client([
            'base_uri' => env('CALENDAR_SSO_BASE'),
            'headers'  => [
                'User-Agent' => sprintf('eveseat-calendar/%s (Kassie Yvo;Daerie Inc.;Get Off My Lawn)',
                    config('calendar.config.version')),
            ],
        ]);

        $response = $client->request('POST', '/oauth/token', [
            'auth' => [
                env('CALENDAR_EVE_CLIENT_ID'),
                env('CALENDAR_EVE_CLIENT_SECRET'),
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ],
        ]);

        $token = json_decode($response->getBody());

        $response = $client->request('GET', '/oauth/verify', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token->access_token,
            ],
        ]);

        $verify = json_decode($response->getBody());

        $character = CharacterSheet::find($verify->CharacterID);
        if (is_null($character))
            throw new MissingCharacterException('Character ' . $verify->CharacterID . ' has not been registered yet.');

        $esiToken = EsiToken::create([
            'character_id' => $verify->CharacterID,
            'scopes' => 'esi-fleets.read_fleet.v1',
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'expires_at' => $request_time->addSeconds($token->expires_in),
        ]);

        return $esiToken;
    }

    private function buildAuthUri() : string
    {
        $url = parse_url(env('CALENDAR_SSO_BASE'));

        $base_uri = $url['scheme'] . '://' . $url['host'];

        if (array_key_exists('port', $url) && $url['port'] != 80)
            $base_uri .= ':' . $url['port'];

        $path = '/oauth/authorize';
        $scopes = [
            'esi-fleets.read_fleet.v1',
        ];
        $state = base64_encode(time() . implode(' ', $scopes));
        $query_parameters = [
            'response_type' => 'code',
            'redirect_uri'  => route('calendar.auth.callback'),
            'client_id' => env('CALENDAR_EVE_CLIENT_ID'),
            'scope' => implode(' ', $scopes),
            'state' => $state,
        ];

        session(['calendar-auth-state' => $state]);

        return $base_uri . $path . '?' . http_build_query($query_parameters);
    }

}
