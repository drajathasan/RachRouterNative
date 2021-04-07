<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2021-04-06 22:06:58
 * @modify date 2021-04-06 22:06:58
 * @license MIT
 * @desc 
 * 
 * Rach Router is simple PHP router for handling web request, include filtering per HTTP method, 
 * Regular Expression filter by custom or by Rach Router template.
 */

//  Load helper and resolver
requireFile('Resolver.php');

class Rachrouter 
{
    // set variable
    private $route;
    private $method;
    private $expCriteria = [];
    private $expRequest = [];
    private $params;
    private $defaultControllerPath = 'Controller';

    // make a construct
    public function __construct()
    {
        // setup method
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * RemoveNonRoute
     *
     * Escape all non route character
     * 
     * @param string $requestUri
     * @return string
     */
    private function removeNonRoute($requestUri)
    {
        if (preg_match('/\/index.php/i', $_SERVER['SCRIPT_NAME']) && count(slashExploder($_SERVER['SCRIPT_NAME'])) > 1)
        {
            $beforeIndex = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
            return str_replace($beforeIndex, '', $requestUri);
        }
        else 
        {
            return $requestUri;
        }
    }

    /**
     * Resolver
     *
     * Short way to call Resolver inside this object
     * 
     * @param string $criteria
     * @param string $request
     * @return boolean
     */
    private function resolver($criteria, $request)
    {
        if (Resolver::isRegexPattern($criteria))
        {
            $template = Resolver::checkTemplate($criteria);
            return Resolver::regexTest($template, $request);
        }
        else
        {
            return Resolver::testCondition($criteria, $request);
        }
    }

    /**
     * isRoot
     *
     * Root route check
     * 
     * @return boolean
     */
    private function isRoot()
    {
        if (isset($_SERVER['SERVER_SOFTWARE']) && preg_match('/(Development Server)/i', $_SERVER['SERVER_SOFTWARE']) && ($_SERVER['REQUEST_URI'] === '/'))
        {
            return true;
        }
        else if(($_SERVER['REQUEST_URI'] === '/') || count(slashExploder($_SERVER['REQUEST_URI'])) === 1)
        {
            return true;
        }

        return false;
    }

    /* Request Area */
    public function defaultRoute($callback = '', $allowMethod = ['GET', 'POST', 'OPTIONS'])
    {
        // store into mix
        $this->mix('/', (empty($callback)) ? 'Root' : $callback, $allowMethod);
    }

    public function get($criteria, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$this->isRoot())
        {
            $this->route['GET'][$criteria] = $callback;
        }
    }

    public function post($criteria, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$this->isRoot())
        {
            $this->route['POST'][$criteria] = $callback;
        }
    }

    public function mix($criteria, $callback, $mixMethod = ['GET', 'POST'])
    {
        foreach ($mixMethod as $method) { 
            $this->route[$method][$criteria] = $callback;
        }
    }

    /* End Request Area */

    /**
     * Route Matching
     *
     * @param string $criteria
     * @return boolean
     */
    private function matchRoute($criteria)
    {
        $this->expCriteria = slashExploder($criteria);
        $this->expRequest = slashExploder($this->removeNonRoute($_SERVER['REQUEST_URI']));

        $match = 0;
        
        foreach ($this->expRequest as $pos => $path) {
            if (isset($this->expCriteria[$pos]) && isset($this->expRequest[$pos]))
            {
                if ($this->resolver($this->expCriteria[$pos], $this->expRequest[$pos]))
                {
                    $match++;
                }
            }
        }
        return ($match === count($this->expCriteria)) ? true : false;
    }

    /**
     * Run router
     *
     * @return mix
     */
    public function run()
    {
        if (isset($this->route[$this->method]) && count($this->route[$this->method]) > 0)
        {
            $isMatch = false;
            foreach ($this->route[$this->method] as $criteria => $callback) {
                $match = $this->matchRoute($criteria);

                if ($match)
                {
                    $isMatch = true;
                    // set params
                    $this->params = $this->expRequest;
                    // callback function, view, file etc. :)
                    Resolver::call($callback, $this->params, $this->defaultControllerPath);
                }
            }

            // If you wish to change this message, change it with your style :D.
            if (!$isMatch) echo 'Route Not Match';
        }
        else
        {
            // If you wish to change this message, change it with your style :D.
            echo 'Root';
        }
    }

    /**
     * Get Route Parametter
     *
     * call it after run method
     * 
     * @param string $key
     * @return mix
     */
    public function getParams($key = '')
    {
        if (is_numeric($key))
        {
            return isset($this->params[$key]) ? $this->params[$key] : null;
        }

        return $this->params;
    }

    public function setDefaultControllerPath($path)
    {
        $this->defaultControllerPath = $path;
    }
}