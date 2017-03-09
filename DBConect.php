<?php

require 'config.php';

class DBConect {

    /**
     * Get a connection
     * @return \mysqli connection
     */
    public static function getConnection() {
        $conn = new mysqli($GLOBALS["servername"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["dbname"]);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        mysqli_set_charset($conn,"utf8");
        return $conn;
    }

}
