<?php

class Connection
{

    private $database;
    private $database_user = "root";
    private $database_host = "localhost";
    private $database_name = "clinic_db";
    private $database_password = "";

    public function connect_db()
    {
        $this->database = mysqli_connect(
            $this->database_host,
            $this->database_user,
            $this->database_password,
            $this->database_name
        );

        if (mysqli_connect_error())
        {
            die("Database could not connect! " . mysqli_connect_errno() . ' ' . mysqli_connect_error());
        }
        else
        {
            return $this->database;
        }
    }

    function __construct()
    {
        $this->connect_db();
    }

}

?>