<?php

namespace Xcelerate\Space;

use Xcelerate\Models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Implementation taken from Akaunting - https://github.com/akaunting/akaunting
trait SiteApi
{
    protected static function getRemote($url, $data = [], $token = null)
    {
        $client = new Client(['verify' => false, 'base_uri' => config('xcelerate.base_url').'/']);

        $headers['headers'] = [
            'Accept' => 'application/json',
            'Referer' => url('/'),
            'xcelerate' => Setting::getSetting('version'),
            'Authorization' => "Bearer {$token}",
        ];

        $data['http_errors'] = false;

        $data = array_merge($data, $headers);

        try {
            $result = $client->get($url, $data);
        } catch (RequestException $e) {
            $result = $e;
        }

        return $result;
    }
}
