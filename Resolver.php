<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2021-04-06 22:26:17
 * @modify date 2021-04-06 22:26:17
 * @license MIT
 * @desc 
 * 
 * Resolver is a separate tool for make RachRouter works in filtering input.
 */

class Resolver
{
    // set template
    public static $template = ['{:alpha}' => '/^[a-zA-Z\-]*$/', '{:num}' => '/^[0-9]*$/', '{:alphanum}' => '/^[a-zA-Z0-9\-]*$/'];

    /**
     * isRegexPattern
     *
     * @param string $string
     * @return boolean
     */
    public static function isRegexPattern($string)
    {
        // check if 
        if (preg_match('/{+\w+}/', $string)) return false;
        // manipulate string
        $string = self::checkTemplate($string);
        // check match
        return (bool)preg_match("/^\/.+\/[a-z]*$/i", $string);
    }

    /**
     * Regeular Expression Test
     *
     * @param string $pattern
     * @param string $value
     * @return boolean
     */
    public static function regexTest($pattern, $value)
    {
        return (bool)preg_match($pattern, $value);
    }

    /**
     * Just for check 
     *
     * @param string $criteria
     * @param string $value
     * @return boolean
     */
    public static function testCondition($criteria, $value)
    {
        return ($criteria === $value);
    }

    /**
     * Template check
     *
     * @param string $key
     * @return string
     */
    public static function checkTemplate($key)
    {
        return isset(self::$template[$key]) ? self::$template[$key] : str_replace(['{','}'], '/', $key);
    }
}