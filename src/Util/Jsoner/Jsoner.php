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
    public function load($file)
    {
        try {
            $items = JsonLoader::fileToArray($file);
            return $this->make($items);
        } catch (JsonException $exception) {
            return false;
        }
    }

    public function fill($data, $file)
    {
        try {
            $items = JsonLoader::dataToFile($data, $file);
            return $this->make($items);
        } catch (JsonException $exception) {
            return false;
        }
    }

    public function fillArray($array, $file)
    {
        try {
            $items = JsonLoader::arrayToFile($array, $file);
            return $this->make($items);
        } catch (JsonException $exception) {
            return false;
        }
    }
}