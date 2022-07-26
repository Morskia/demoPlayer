<?php

namespace Mini\Core;

use PDO;

class Model
{
    /**
     * @var null Database Connection
     */
    public $db = null;

    /**
     * Whenever model is created, open a database connection.
     */
    function __construct() {

        try {
            self::openDatabaseConnection();
        } catch (\PDOException $e) {
            try {
                $connection = "mysql:host=localhost;charset=UTF8";
                $pdo = new PDO($connection, 'root', '');
                $sql = "CREATE DATABASE tv";
                $query = $pdo->prepare($sql);
                $query->execute();
                try {
                    self::openDatabaseConnection();
                } catch (PDOException $e) {
                    echo "Моля свържете се с представител на MBOX STUDIOS";
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
//    private function openDatabaseConnection()
////    {
////        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
////        // "objects", which means all results will be objects, like this: $result->user_name !
////        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
////        // @see http://www.php.net/manual/en/pdostatement.fetch.php
////        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
////
////        // setting the encoding is different when using PostgreSQL
////        if (DB_TYPE == "pgsql") {
////            $databaseEncodingenc = " options='--client_encoding=" . DB_CHARSET . "'";
////        } else {
////            $databaseEncodingenc = "; charset=" . DB_CHARSET;
////        }
////
////        // generate a database connection, using the PDO connector
////        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
////        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . $databaseEncodingenc, DB_USER, DB_PASS, $options);
////    }
///
    private function openDatabaseConnection() {
        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
        // "objects", which means all results will be objects, like this: $result->user_name !
        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

        // setting the encoding is different when using PostgreSQL
        if (DB_TYPE == "pgsql") {
            $databaseEncodingenc = " options='--client_encoding=" . DB_CHARSET . "'";
        } else {
            $databaseEncodingenc = "; charset=" . DB_CHARSET;
        }

        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . $databaseEncodingenc, DB_USER, DB_PASS, $options);
    }
}
