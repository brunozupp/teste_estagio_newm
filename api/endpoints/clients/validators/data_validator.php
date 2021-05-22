<?php

    require_once './validators/cpf_validator.php';

    function validateData($data, $insert = true) : array {

        $mensagens = [];

        if(!$insert) {
            if(empty($data['id'])) {
                array_push($mensagens, "Id da pessoa é obrigatório");
            } else {
                if(intval($data['id']) <= 0) {
                    array_push($mensagens, "Id da pessoa precisa ser maior que 0");
                }
            }
        }

        if(empty($data['name'])) {
            array_push($mensagens, "Nome é obrigatório");
        }

        if(empty($data['birthDate'])) {
            array_push($mensagens, "Data de nascimento é obrigatório");
        } else {

            $parts = explode("-", $data['birthDate']);

            if(!checkdate(intval($parts[1]), intval($parts[2]), intval($parts[0]))) {
                array_push($mensagens, "Data de nascimento é inválida");
            }
        }

        if(empty($data['cpf'])) {
            array_push($mensagens, "CPF é obrigatório");
        } else {

            if(!validateCPF($data['cpf'])) {
                array_push($mensagens, "CPF é inválido");
            }
        }

        if(empty($data['phone'])) {
            array_push($mensagens, "Celular  é obrigatório");
        }

        if(empty($data['email'])) {
            array_push($mensagens, "Email é obrigatório");
        }

        if(strlen($data['observation']) > 300) {
            array_push($mensagens, "Observação deve conter no máximo 300 caracteres");
        }

        if(!isset($data['address']) || is_null($data['address'])) {
            array_push($mensagens, "Endereço é obrigatório");
        }

        if(empty($data['address']['cep'])) {
            array_push($mensagens, "Endereço - CEP  é obrigatório");
        }

        if(empty($data['address']['street'])) {
            array_push($mensagens, "Endereço - CEP  é obrigatório");
        }

        if(empty($data['address']['number'])) {
            array_push($mensagens, "Endereço - Número  é obrigatório");
        }

        if(empty($data['address']['neighborhood'])) {
            array_push($mensagens, "Endereço - Bairo  é obrigatório");
        }

        if(empty($data['address']['complement'])) {
            array_push($mensagens, "Endereço - Complemento  é obrigatório");
        }

        if(empty($data['address']['city'])) {
            array_push($mensagens, "Endereço - Cidade  é obrigatório");
        }

        if(empty($data['address']['state'])) {
            array_push($mensagens, "Endereço - Estado  é obrigatório");
        }

        return $mensagens;
    }
?>