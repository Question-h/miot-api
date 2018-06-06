<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午5:58
 */

namespace Yeelight\Validator;

class Urn
{
    const URN_REGEXP = '/^urn:[a-z0-9][a-z0-9-]{1,31}:([a-z0-9()+,-.:=@;$_!*\']|%(0[1-9a-f]|[1-9a-f][0-9a-f]))+$/i';

    /**
     * Validate a URN according to RFC 2141.
     *
     * @param $urn
     * @return TRUE when the URN is valid, FALSE when invalid
     * @internal param the $urn URN to validate
     */
    public static function validate($urn)
    {
        return (bool) preg_match(self::URN_REGEXP, $urn);
    }
}