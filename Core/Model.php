<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 * 
 * PHP version 5.3
 */

abstract class Model
{
  /**
   * Get the PDO database connection
   * 
   * @return mixed
   */
  public static function getDB()
  {
    static $db = null;

    if ($db === null) {
//      $host = 'localhost';
//      $dbname = 'marc';
//      $username = 'root';
//      $password = '';

      try {
        $db = new PDO(
          sprintf("mysql:host=%s;dbname=%s;charset=utf8",
          Config::DB_HOST, Config::DB_NAME), 
          Config::DB_USER, 
          Config::DB_PASSWORD
        );

        return $db;

      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }
  }
}