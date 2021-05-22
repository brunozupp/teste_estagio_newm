<?php

    class ResponseBase {

        public $success;
        public $error;
        public $notFound;
        
        function __construct($success, $error, $notFound) {
            $this->success = $success;
            $this->error = $error;
            $this->notFound = $notFound;
        }
    }

?>