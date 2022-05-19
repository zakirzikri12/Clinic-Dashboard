<?php

    require_once("connection.class.php");

    class Validation extends Connection
    {

        private $input  = "", $keyword = "", $min_length = "", $max_length = "";

        private $error;

        // Class constructor
        public function __construct()
        {
            $this->connection = $this->connect_db();
        }


        public function validate($input, $keyword, $required = false, $min_length = 3, $max_length = 12, $email = false)
        {
            $this->input = $input;
            $this->keyword = $keyword;
            $this->min_length = $min_length;
            $this->max_length = $max_length;

            if ($required)
            {
                $this->error = $this->required();

                if(!empty($this->error))
                {
                    return $this->error;
                }
                
            }
      
            $this->error = $this->min_max_length();
            if(!empty($this->error))
            {
                return $this->error;
            }

            if ($email)
            {
                $this->error = $this->email();
                if(!empty($this->error))
                {
                    return $this->error;
                }
            }
        }

        // Required input method
        private function required ()
        {
            if (empty($this->input))
            {
                return ucfirst($this->keyword) . ' cannot empty!';
            }
        }

        // Min and max input length method
        private function min_max_length ()
        {
            if (strlen($this->input) < $this->min_length || strlen($this->input) > $this->max_length)
            {
                return ucfirst($this->keyword) . ' must between ' . $this->min_length . ' to ' . $this->max_length . ' characters.';
            }
        }

        // Email validate method
        private function email ()
        {
            if (!filter_var($this->input, FILTER_VALIDATE_EMAIL))
            {
                return 'Invalid ' . $this->keyword . ' format!';
            }
        }



        // Cleaning up input
        public function clean_up_input ($input)
        {
            return htmlspecialchars($input);
        }

    }

    $validation = new Validation();

?>