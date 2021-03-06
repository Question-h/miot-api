<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-12
 * Time: 下午4:14.
 */

namespace MiotApi\Util\Jsoner;

/**
 * The JSON last error collection.
 */
class JsonLastError
{
    /**
     * Check for errors.
     *
     * @return array|null
     */
    public static function check()
    {
        $collections = self::getCollection();
        $jsonLastError = json_last_error();

        return isset($jsonLastError) ? $collections[$jsonLastError] : $collections['default'];
    }

    /**
     * Get collection of JSON errors.
     *
     * @return array
     */
    public static function getCollection()
    {
        $collections = [
            JSON_ERROR_NONE  => null,
            JSON_ERROR_DEPTH => [
                'message'    => 'Maximum stack depth exceeded',
                'error-code' => 1,
            ],
            JSON_ERROR_STATE_MISMATCH => [
                'message'    => 'Underflow or the modes mismatch',
                'error-code' => 2,
            ],
            JSON_ERROR_CTRL_CHAR => [
                'message'    => 'Unexpected control char found',
                'error-code' => 3,
            ],
            JSON_ERROR_SYNTAX => [
                'message'    => 'Syntax error, malformed JSON',
                'error-code' => 4,
            ],
            JSON_ERROR_UTF8 => [
                'message'    => 'Malformed UTF-8 characters',
                'error-code' => 5,
            ],
            JSON_ERROR_RECURSION => [
                'message'    => 'Recursion error in value to be encoded',
                'error-code' => 6,
            ],
            JSON_ERROR_INF_OR_NAN => [
                'message'    => 'Error NAN/INF in value to be encoded',
                'error-code' => 7,
            ],
            JSON_ERROR_UNSUPPORTED_TYPE => [
                'message'    => 'Type value given cannot be encoded',
                'error-code' => 8,
            ],
            'default' => [
                'message'    => 'Unknown error',
                'error-code' => 999,
            ],
        ];
        if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
            $collections[JSON_ERROR_INVALID_PROPERTY_NAME] = [
                'message'    => 'Name value given cannot be encoded',
                'error-code' => 9,
            ];
            $collections[JSON_ERROR_UTF16] = [
                'message'    => 'Malformed UTF-16 characters',
                'error-code' => 10,
            ];
        }

        return $collections;
    }
}
