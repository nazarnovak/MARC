<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 * 
 * PHP version 5.6
 */
class Home extends \Core\Controller
{
  /**
   * Before filter
   * 
   * @return void
   */
  protected function before()
  {
//    echo "(before) ";
  }

  /**
   * After filter
   * 
   * @return void
   */
  protected function after()
  {
//    echo " (after)";
  }

  /**
   * Show the index page
   * 
   * @return void
   */
  public function indexAction()
  {
//    View::render('Home', 'index.php', array(
//      'name'    => 'Dave',
//      'colours' => array('red', 'green', 'blue')
//    ));
    View::renderTemplate('Home/index.html', array(
      'name'    => 'Dave',
      'colours' => array('red', 'green', 'blue')
    ));
  }
}