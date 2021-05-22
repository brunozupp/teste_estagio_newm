<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");

    require_once '../database.php';
    require_once '../../domain/responseSuccess.php';
    require_once '../../domain/responseError.php';
    require_once '../../domain/responseNotFound.php';

    if(!isset($_GET['id'])) {
        echo json_encode(new ResponseError(["É preciso informar o id"]));
        exit;
    }

    if(!filter_var($_GET['id'], FILTER_VALIDATE_INT) || $_GET['id'] <= 0) {
        echo json_encode(new ResponseError(["É preciso informar um número válido e que seja maior que 0"]));
        exit;
    }
	
    $connection = (new Database())->getConnection();

    $id = $_GET['id'];

    // Pegando as pessoas
    $stmt = $connection->prepare("SELECT * FROM clients WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($results) == 0) {
        echo json_encode(new ResponseNotFound("Não foi possível achar o cliente"));
        exit;
    }

    $client = $results[0];

    // Pegando os endereços das pessoas
    $stmt = $connection->prepare("SELECT * FROM addresses WHERE clientId = :clientId");
    $stmt->bindParam(':clientId', $client["id"], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($results) == 0) {
        echo json_encode(new ResponseNotFound("Não foi possível achar o endereço do cliente"));
        exit;
    }

    $client["address"] = $results[0];
    unset($client["address"]["clientId"]);
    unset($client["address"]["id"]);

    echo json_encode(new ResponseSuccess($client));
?>