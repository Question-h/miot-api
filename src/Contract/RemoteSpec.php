<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-8
 * Time: 下午5:17
 */
namespace MiotApi\Contract;

use MiotApi\Contract\Interfaces\RemoteSpec as RemoteSpecInterface;
use MiotApi\Util\Request;

class RemoteSpec implements RemoteSpecInterface
{
    private static $host = 'miot-spec.org';

    private static $namespaces = 'miot-spec-v2';

    private static $timeout = 30;

    public static function get($uri, $params = [])
    {
        $http = new Request(
            self::$host,
            self::$namespaces . '/' . $uri,
            80,
            true,
            self::$timeout);

        $result = $http
            ->setQueryParams($params)
            ->execute()
            ->getResponseText();

        if ($result) {
            return $result;
        }

        return null;
    }
}