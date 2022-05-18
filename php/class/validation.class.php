<?php

    require_once("connection.class.php");

    class Validation extends Connection
    {

        public function __construct()
        {
            $this->connection = $this->connect_db();
        }

    }

    $validation = new Validation();

?>