<?php

    session_start();

    require_once("connection.class.php");

    class User extends Connection {
        
        public function __construct()
        {
            $this->connection = $this->connect_db();
        }

    }

    $user = new User();

?>