<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-12
 * Time: 下午4:28
 */

namespace MiotApi\Util\Jsoner;

use MiotApi\Exception\JsonException;
use MiotApi\Util\Collection\Collection;

class Jsoner extends Collection
{
    const CACHE_DIR = 'json_cache';

    public static function load($file)
    {
        try {
            $items = JsonLoader::fileToArray(self::getCacheDir() . $file);
            if (!empty($items)) {
                return self::make($items);
            }
            return false;
        } catch (JsonException $exception) {
            return false;
        }
    }

    public static function fill($data, $file)
    {
        try {
            $items = JsonLoader::dataToFile($data, self::getCacheDir() . $file);
            if (!empty($items)) {
                return self::make($items);
            }
            return false;
        } catch (JsonException $exception) {
            return false;
        }
    }

    public static function fillArray($array, $file)
    {
        try {
            $items = JsonLoader::arrayToFile($array, self::getCacheDir() . $file);
            if (!empty($items)) {
                return self::make($items);
            }
            return false;
        } catch (JsonException $exception) {
            return false;
        }
    }

    public static function getCacheDir()
    {
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . self::CACHE_DIR . DIRECTORY_SEPARATOR;
    }
}