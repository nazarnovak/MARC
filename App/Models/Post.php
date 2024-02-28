<?php

namespace App\Models;

use PDO;

/**
 * Posts model
 * 
 * PHP version 5.3
 */
class Post extends \Core\Model
{
  /**
   * Get all the posts as an associative array
   * 
   * @return array
   */
  public static function getAll()
  {
//    $host = 'localhost';
//    $dbname = 'marc';
//    $username = 'root';
//    $password = '';

    try {
//      $db = new PDO(
//        sprintf("mysql:host=%s;dbname=%s;charset=utf8", $host, $dbname), 
//        $username, 
//        $password
//      );

      $db = static::getDB();

      $stmt = $db->query('SELECT id, title, content FROM posts
                          ORDER BY created_at');
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $results;

    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}