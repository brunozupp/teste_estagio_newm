<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: PUT");
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
	
    $resultado = validateData($data, false);

    if(count($resultado) > 0) {
        echo json_encode(new ResponseError($resultado));
        exit;
    }

    $connection = (new Database())->getConnection();

    // Pegando as pessoas
    $stmt = $connection->prepare("SELECT * FROM clients WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($results) > 0) {
        echo json_encode(new ResponseNotFound("Não foi possível achar o cliente"));
        exit;
    }

    $stmt = $connection->prepare("UPDATE clients SET name = :name, birthDate = :birthDate, cpf = :cpf, phone = :phone, email = :email, observation = :observation WHERE id = :id");
    $stmt->bindParam(':id', $data["id"], PDO::PARAM_INT);
    $stmt->bindParam(':name', $data["name"], PDO::PARAM_STR);
    $stmt->bindParam(':birthDate', $data["birthDate"], PDO::PARAM_STR);
    $stmt->bindParam(':cpf', $data["cpf"], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $data["phone"], PDO::PARAM_STR);
    $stmt->bindParam(':email', $data["email"], PDO::PARAM_STR);
    $stmt->bindParam(':observation', $data["observation"], PDO::PARAM_STR);

    if($stmt->execute()) {
        
        $address = $data["address"];

        $stmt = $connection->prepare("UPDATE addresses SET cep = :cep, street = :street, number = :number, neighborhood = :neighborhood, complement = :complement, city = :city, state = :state WHERE clientId = :clientId");
        $stmt->bindParam(':clientId', $data["id"], PDO::PARAM_INT);
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
        
        echo json_encode(new ResponseError(["Não foi possível editar os dados do endereço do cliente"]));
        exit;
    }

    echo json_encode(new ResponseError(["Não foi possível editar o cliente"]));
    exit;
?>



