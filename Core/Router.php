<?php

namespace Core;

/**
 * Router
 */
class Router
{
  /**
   * Associative array of routes (the routing table)
   * @var array
   */
  protected $routes = array();

  /**
   * Parameters from the matched route
   * @var array
   */
  protected $params = array();

  /**
   * Add a route to the routing table
   * 
   * @param string $route  The route URL
   * @param array  $parms  Parameters (controller, aaction, etc.)
   * 
   * @return void
   */
  public function add($route, $params = array())
  {
    // Convert the route to a regular expression: escape forward slashes
    $route = preg_replace('/\//', '\\/', $route);

    // Convert variables e.g. {controller}
    $route = preg_replace('/\{([a-z-]+)\}/', '(?<\1>[a-z-]+)', $route);

    // Convert variables with custom regular expressions e.g. {id:\d+}
    $route = preg_replace('/\{([a-z-]+):([^\}]+)\}/', '(?<\1>\2)', $route);

    // Add start and end delimiters, and case insensitive flag
    $route = '/^' . $route . '$/i';

    $this->routes[$route] = $params;
  }

  /**
   * Get all the routes from the routing table
   * 
   * @return array
   */
  public function getRoutes()
  {
    return $this->routes;
  }

  /**
   * Match the route to the routes in the routing table, setting the $params
   * property if a route is found
   * 
   * @params string $url The route URL
   * 
   * @return boolean  true if a match found, false otherwise
   */
  public function match($url)
  {
//    foreach ($this->routes as $route => $params) {
//      if ($url == $route) {
//        $this->params = $params;
//        return true;
//      }
//    }

    // Match to the fixed URL format /controller/action
//    $regEx = "/^(?<controller>[a-z-]+)\/(?<action>[a-z-]+)$/";

    foreach ($this->routes as $route => $params) {
      if (preg_match($route, $url, $matches)) {
        // Get named capture group values
//        $params = array();

        foreach ($matches as $key => $match) {
          if (is_string($key)) {
            $params[$key] = $match;
          }
        }

        $this->params = $params;
        return true;
      }
    }

    return false;
  }

  /**
   * Dispatch the route; creating the controller object and running the action
   * method
   * 
   * @param string $url  The route URL
   * 
   * @return void
   */
  public function dispatch($url)
  {
    $url = $this->removeQueryStringVariables($url);

    if ($this->match($url)) {
      $controller = $this->params['controller'];
      $controller = $this->convertToStudlyCaps($controller);
//      $controller = "App\Controllers\\$controller";
      $controller = $this->getNamespace() . $controller;

      if (class_exists($controller)) {
        $controllerObject  = new $controller($this->params);

        $action = 'index';
        if(isset($this->params['action'])) {
          $action = $this->params['action'];
        }
        $action = $this->convertToCamelCase($action);

        if (is_callable([$controllerObject, $action])) {
          $controllerObject->$action();
        } else {
          echo "Method $action (in controller $controller) not found";
        }
      } else {
        echo "Controller class $controller not found";
      }
    } else {
      echo 'No route matched.';
    }
  }

  /**
   * Convert the string with hyphens to StudlyCaps,
   * e.g. post-authors => PostAuthors
   * 
   * @param string $string The string to convert
   * 
   * @return string
   */
  public function convertToStudlyCaps($string)
  {
    return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
  }

  /**
   * Convert the string with hyphens to camelCase,
   * e.g. add-new => addNew
   * 
   * @param string $string The string to convert
   * 
   * @return string
   */
  public function convertToCamelCase($string)
  {
    return lcfirst($this->convertToStudlyCaps($string));
  }
  /**
   * Get the currently matched parameters
   * 
   * @return array
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   * Remove the query string variables from the URL (if any). As the full
   * query string is used for the route, any variables at the end will need
   * to be removed before the route is matched to the routing table. For
   * example:
   * 
   *  URL                           $_SERVER['QUERY_STRING']  Route
   *  localhost                     ''                        ''
   *  localhost/?                   ''                        ''
   *  localhost/?page=1             page=1                    ''
   *  localhost/posts?page=1        posts&page=1              posts
   *  localhost/posts/index         posts/index               posts/index
   *  localhost/posts/index?page=1  posts/index&page=1        posts/index
   * 
   * A URL of the format localhost/?page (one variable name, no value) won't
   * work however. (NB. The .htaccess file converts the first ? to a & when
   * it's passed through to the $_SERVER variable)
   * 
   * @param string $url  The full URL
   * 
   * @return string  The URL with the query string variables removed
   */
  protected function removeQueryStringVariables($url)
  {
    if ($url != '') {
      $parts = explode('&', $url, 2);

      if (strpos($parts[0], '=') === false) {
        $url = $parts[0];
      } else {
        $url = '';
      }
    }

    return $url;
  }

  /**
   * Get the namespace  for the controller class. The namespace defined in the
   * route parameters is added if present.
   * 
   * @return string  The request URL
   */
  protected function getNamespace()
  {
    $namespace = 'App\Controllers\\';

    if (array_key_exists('namespace', $this->params)) {
      $namespace .= $this->params['namespace'] . '\\';
    }

    return $namespace;
  }
}