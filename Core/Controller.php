<?php

namespace Core;

/**
 * Base controller
 * 
 * PHP version 5.6
 */
abstract class Controller
{
  /**
   * Parameters from the matched route
   * @var array
   */
  protected $routeParams = [];

  /**
   * Class constructor
   * 
   * @param array $routeParams Parameters from the route
   * 
   * @return void
   */
  public function __construct($routeParams)
  {
    $this->routeParams = $routeParams;
  }

  /**
   * 
   * @param array $args  Arguments passed to the method
   * 
   * @return void
   */
  public function __call($name, $args)
  {
    $method = $name . 'Action';

    if (method_exists($this, $method)) {
      if ($this->before() !== false) {
        call_user_func_array([$this, $method], $args);
        $this->after();
      }
    } else {
      echo "Method $method not found in controller " . get_class($this);
    }
  }

  /**
   * Before filter - called before an action method.
   * 
   * @return void
   */
  protected function before()
  {
//    
  }

  /**
   * After filter - called after an action method.
   * 
   * @return void
   */
  protected function after()
  {
//    
  }
}