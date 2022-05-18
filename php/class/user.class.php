<?php

    session_start();

    date_default_timezone_set("Asia/Kuala_Lumpur");

    require_once("connection.class.php");

    class User extends Connection
    {

        // Connection
        private $connection;
        
        // For registration
        private $user_id, $reg_full_name ,$reg_ic_no, $reg_age, $reg_gender, $reg_address, $reg_email, $reg_phone_no;
        
        // For login
        private $log_ic_no, $log_pwd;



        // Constructor
        public function __construct()
        {
            $this->connection = $this->connect_db();
        }



        // Registration method
        public function register($credential, $redirectTo = "/")
        {
            $register_date = date("d/m/Y");

            $reg_sql = "INSERT INTO user (user_id, full_name, IC_no, age, gender, address, email, phone_no, register_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $reg_stmt = mysqli_stmt_init($this->connection);

            if (mysqli_stmt_prepare($reg_stmt, $reg_sql))
            {
                mysqli_stmt_bind_param($reg_stmt, "sssssssss", $credential['user_id'], $credential['full_name'], $credential['IC_no'], $credential['age'], $credential['gender'], $credential['address'], $credential['email'], $credential['phone_no'], $register_date);
                mysqli_stmt_execute($reg_stmt);

                header("Location: $redirectTo?registration=success");
                exit();
            }
            else
            {
                return "Could not prepare the registration statement!<br/>Please try again";
            }
        }



        // Login method
        public function login($credential, $redirectTo = "/")
        {
            $log_sql = "SELECT user_id, password FROM user WHERE IC_no = ?;";
            $log_stmt = mysqli_stmt_init($this->connection);

            if (mysqli_stmt_prepare($log_stmt, $log_sql))
            {
                mysqli_stmt_bind_param($log_stmt, "s", $credential['IC_no']);
                mysqli_stmt_execute($log_stmt);

                $log_res = mysqli_stmt_get_result($log_stmt);

                if (mysqli_num_rows($log_res) == 1)
                {
                    $user_data = mysqli_fetch_assoc($log_res);

                    if (password_verify($credential['password'], $user_data['password']))
                    {
                        session_regenerate_id();
                        $auth_code = session_id();

                        $auth_log_sql = "UPDATE user SET access_token = ? WHERE IC_no = ?;";
                        $auth_log_stmt = mysqli_stmt_init($this->connection);

                        if (mysqli_stmt_prepare($auth_log_stmt, $auth_log_sql))
                        {
                            mysqli_stmt_bind_param($auth_log_stmt, "ss", $auth_code, $credential['IC_no']);
                            mysqli_stmt_execute($auth_log_stmt);

                            $_SESSION['user_id'] = $user_data['user_id'];

                            header("Location: $redirectTo");
                            exit();
                        }
                        else
                        {
                            return "Could not prepare update access token statement!<br/>Please try again";
                        }
                    }
                    else
                    {
                        return "Your password did not match!<br/>Please try again";
                    }
                }
                else
                {
                    return "There is no user with this IC number!<br/>Please try again";
                }
            }
            else
            {
                return "Could not prepare check password statement!<br/>Please try again";
            }
        }



        // Read all user data method
        public function read_all_user($IC_no = null)
        {
            $read_all_user_sql = "SELECT * FROM user";

            if ($IC_no)
            {
                $read_all_user_sql .= " WHERE IC_no = '$IC_no';";
            }

            $read_all_user_res = mysqli_query($this->connection, $read_all_user_sql);

            return $read_all_user_res;
        }



        // Update user data method
        public function update_user_data($credential, $redirectTo = "/")
        {

        }



        // Delete user data method
        public function delete_user_data($IC_no)
        {
            
        }



    }

    $user = new User();

?>