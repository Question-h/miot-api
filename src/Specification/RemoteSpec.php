<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-8
 * Time: 下午5:17
 */
namespace Yeelight\Specification;

use Yeelight\Specification\Interfaces\RemoteSpec as RemoteSpecInterface;
use GuzzleHttp\Client;

class RemoteSpec implements RemoteSpecInterface
{
    private $baseUrl = 'http://miot-spec.org/miot-spec-v2/';

    public static function get($url)
    {
        $client = new Client();
        $res = $client->request('GET', $url);

        if ($res->getStatusCode() == 200) {
            return $res->getBody();
        }

        return null;
    }
}