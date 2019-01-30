<?php
/**
 * Created by IntelliJ IDEA.
 * User: jakub.polomsky
 * Date: 30.01.2019
 * Time: 07:53
 */

namespace jakubpolomsky\phpRequirementsChecker;


class StringFunctions
{
    /**
     * @param string $jsonString
     * @return bool
     */
    public static function isValidJson($jsonString)
    {
        if ( ! empty($jsonString)) {
            @json_decode($jsonString);
            return (json_last_error() === JSON_ERROR_NONE);
        }

        return false;
    }

    /**
     * @param string $versionRequired
     * @param string|false $versionAvailable
     * @return bool
     */
    public static function compareVersions($versionRequired, $versionAvailable)
    {
        $versionRequired = trim($versionRequired);

        if ($versionAvailable === false) {
            return true;
        }

        if ($versionRequired === '*') {
            return true;
        }

        preg_match('/(>=)|(<=)|(>)|(<)|(!=)|(<>)+/i', $versionRequired, $symbol);

        if (count($symbol)) {
            $symbol = $symbol[0];
        } else {
            $symbol = null;
        }

        $version = isset($symbol) ? explode($symbol, $versionRequired)[1] : $versionRequired;

        if (!isset($symbol)) {
            $symbol = '==';
        }
        return version_compare($versionAvailable, $version, $symbol);
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param string $search
     * @param string $replace
     * @param string $subject
     * @return string
     */
    public static function replaceFirstOccurence($search, $replace, $subject)
    {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }
}