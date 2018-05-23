<?php
  class DB {
    // Connect to database
      public static function connection(){
        try {
              $connection = new PDO("mysql:host=localhost;dbname=mydb", "root", "");

            $connection->exec('SET NAMES UTF8');

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch (PDOException $e) {
            die('Error in database connection: ' . $e->getMessage());
        }
        return $connection;
      }
  }