<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-8
 * Time: 下午5:17
 */
namespace MiotApi\Contract;

use MiotApi\Util\Jsoner\Jsoner;
use MiotApi\Util\Request;

class RemoteSpec extends Jsoner
{
    private static $host = 'miot-spec.org';

    private static $namespaces = 'miot-spec-v2';

    private static $timeout = 30;

    const INSTANCES = 'instances';

    public static function instances()
    {
        $instances = Jsoner::load(self::INSTANCES);
        if (!$instances) {
            $instances = self::fetch(self::INSTANCES);
            Jsoner::fill($instances, self::INSTANCES);
        }
        return $instances;
    }

    public static function fetch($uri, $params = [])
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