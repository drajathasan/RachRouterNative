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

    public static function call($mixPath, $params, $defaultControllerPath = '')
    {
        $path = doubleDotExploder($mixPath);

        switch ($path[0]) {
            case 'Include':
                if (file_exists($path[1])) include $path[1]; exit;
                break;
            
            case 'Class':
                $parse = classParser($path[1]);
                if (!empty($defaultControllerPath) && file_exists($defaultControllerPath.$parse[0].'.class.php'))
                {
                    // include class
                    include $defaultControllerPath.$parse[0].'.class.php';
                    // call back
                    if (class_exists($parse[0])) call_user_func_array([(new $parse[0]()), $parse[1]], [$params]); exit;
                }
                break;
            default:
                echo $mixPath; exit;
                break;
        }
    }
}