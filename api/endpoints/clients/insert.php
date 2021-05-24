<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once '../database.php';
    require_once './validators/data_validator.php';

    require_once '../../domain/responseSuccess.php';
    require_once '../../domain/responseError.php';
    require_once '../../domain/responseNotFound.php';

    // Dados vindo do body da requisição
    // Usando true para transformar em um array associativo. Por padrão retorna um objeto
    $data = json_decode(file_get_contents("php://input"), true);
	
    $resultado = validateData($data);

    if(count($resultado) > 0) {
        echo json_encode(new ResponseError($resultado));
        exit;
    }

    $connection = (new Database())->getConnection();

    $stmt = $connection->prepare("INSERT INTO clients(name,birthDate,cpf,phone,email,observation) VALUES(:name,:birthDate,:cpf,:phone,:email,:observation)");
    $stmt->bindParam(':name', $data["name"], PDO::PARAM_STR);
    $stmt->bindParam(':birthDate', $data["birthDate"], PDO::PARAM_STR);
    $stmt->bindParam(':cpf', $data["cpf"], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $data["phone"], PDO::PARAM_STR);
    $stmt->bindParam(':email', $data["email"], PDO::PARAM_STR);
    $stmt->bindParam(':observation', $data["observation"], PDO::PARAM_STR);

    if($stmt->execute()) {

        $clientId = $connection->lastInsertId();

        $address = $data["address"];

        $stmt = $connection->prepare("INSERT INTO addresses(clientId,cep,street,number,neighborhood,complement,city,state) VALUES(:clientId,:cep,:street,:number,:neighborhood,:complement,:city,:state)");
        $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
        $stmt->bindParam(':cep', $address["cep"], PDO::PARAM_INT);
        $stmt->bindParam(':street', $address["street"], PDO::PARAM_STR);
        $stmt->bindParam(':number', $address["number"], PDO::PARAM_STR);
        $stmt->bindParam(':neighborhood', $address["neighborhood"], PDO::PARAM_STR);
        $stmt->bindParam(':complement', $address["complement"], PDO::PARAM_STR);
        $stmt->bindParam(':city', $address["city"], PDO::PARAM_STR);
        $stmt->bindParam(':state', $address["state"], PDO::PARAM_STR);

        if($stmt->execute()) {
            echo json_encode(new ResponseSuccess("Bem sucedido"));
            exit;
        }
        
        echo json_encode(new ResponseError(["Não foi possível salvar os dados do endereço do cliente"]));
        exit;
    }

    echo json_encode(new ResponseError(["Não foi possível salvar o cliente"]));
    exit;
?>



