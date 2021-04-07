<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2021-04-06 22:30:29
 * @modify date 2021-04-06 22:30:29
 * @desc 
 * 
 * Rach Helper.
 * 
 * just for help :)
 */

function dump($mix)
{
    echo '<pre>';
    var_dump($mix);
    echo '</pre>';
}

function slashExploder($mix)
{
    return explode('/', trim($mix, '/'));
}

function doubleDotExploder($mix)
{
    return explode('::', trim($mix));
}

function classParser($mixString)
{
    $exp = explode('@', trim($mixString));

    return $exp;
}

function requireFile($filePath, $withDS = true)
{
    if ($withDS)
    {
        require __DIR__.DS.$filePath;
    }
    else
    {
        require $filePath;
    }
}