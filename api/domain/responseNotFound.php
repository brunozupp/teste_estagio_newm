<?php

    require_once 'responseBase.php';

    class ResponseNotFound extends ResponseBase {

        public $message;

        function __construct($message) {
            parent::__construct(false,false,true);
            $this->message = $message;
        }
    }

?>