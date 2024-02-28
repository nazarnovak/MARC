<?php

namespace Core;

/**
 * View
 * 
 * PHP version 5.3
 */

class View
{
  /**
   * Render a view file
   * 
   * @param string $controller  The view folder
   * @param string $file  The file from the folder
   */
  public static function render($controller, $file, $args = array())
  {
    extract($args);
    $filePath = "../App/Views/$controller/$file"; // relative to Core directory

    if (is_readable($filePath)) {
      require $filePath;
    } else {
      echo "$filePath not found";
    }
  }

  /**
   * Render a view template using Twig
   * 
   * @param string $template  The template file
   * @param array $args  Associative array of data to display in the view
   * (optional)
   * 
   * @return void
   */
  public static function renderTemplate($template, $args = array())
  {
    static $twig = null;

    if ($twig === null) {
      $loader = new \Twig_Loader_Filesystem('../App/Views');
      $twig = new \Twig_Environment($loader);
    }

    echo $twig->render($template, $args);
  }
}