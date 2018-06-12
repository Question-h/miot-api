<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-12
 * Time: 下午4:08
 */

namespace MiotApi\Util\Jsoner;

use MiotApi\Exception\JsonException;

class JsonLoader
{
    /**
     * Creating JSON file from data.
     *
     * @param string $data → JSON data
     * @param string $file → path to the file
     *
     * @return array
     * @throws JsonException
     */
    public static function dataToFile($data, $file)
    {
        $array = json_decode($data, true);
        return self::arrayToFile($array, $file);
    }

    /**
     * Creating JSON file from array.
     *
     * @param array $array → array to be converted to JSON
     * @param string $file → path to the file
     *
     * @return array
     * @throws JsonException
     */
    public static function arrayToFile($array, $file)
    {
        self::createDirectory($file);
        $lastError = JsonLastError::check();
        $json = json_encode($lastError ? $lastError : $array, JSON_PRETTY_PRINT);
        self::saveFile($file, $json);
        if (is_null($lastError)) {
            return $array;
        } else {
            throw new JsonException($lastError['message'] . ' ' . $file);
        }
    }

    /**
     * Create directory recursively if it doesn't exist.
     *
     *
     * @param string $file → path to the directory
     *
     * @throws JsonException → couldn't create directory
     */
    private static function createDirectory($file)
    {
        $basename = is_string($file) ? basename($file) : '';
        $path = str_replace($basename, '', $file);
        if (!empty($path) && !is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                $message = 'Could not create directory in';
                throw new JsonException($message . ' ' . $path);
            }
        }
    }

    /**
     * Save file.
     *
     *
     * @param string $file → path to the file
     * @param string $json → json string
     *
     * @throws JsonException → couldn't create file
     */
    private static function saveFile($file, $json)
    {
        if (@file_put_contents($file, $json) === false) {
            $message = 'Could not create file in';
            throw new JsonException($message . ' ' . $file);
        }
    }

    /**
     * Save to array the JSON file content.
     *
     * @param string $file → path or external url to JSON file
     *
     * @return array|false
     * @throws JsonException
     */
    public static function fileToArray($file)
    {
        if (!is_file($file) && !filter_var($file, FILTER_VALIDATE_URL)) {
            self::arrayToFile([], $file);
        }
        $json = @file_get_contents($file);
        $array = json_decode($json, true);
        $lastError = JsonLastError::check();
        return $array === null || !is_null($lastError) ? false : $array;
    }

}