<?php

    require_once 'responseBase.php';

    class ResponseSuccess extends ResponseBase {

        public $content;

        function __construct($content) {
            parent::__construct(true,false,false);
            $this->content = $content;
        }
    }

?>