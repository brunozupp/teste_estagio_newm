<?php

    require_once 'responseBase.php';

    class ResponseError extends ResponseBase {

        public $messages;

        function __construct($messages) {
            parent::__construct(false,true,false);
            $this->messages = $messages;
        }
    }

?>