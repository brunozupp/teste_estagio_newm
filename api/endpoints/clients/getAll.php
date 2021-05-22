<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");

    require_once '../database.php';
    require_once '../../domain/responseSuccess.php';
	
    $connection = (new Database())->getConnection();

    // Pegando as pessoas
    $stmt = $connection->prepare("SELECT * FROM clients");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pegando os endereços das pessoas
    $stmt = $connection->prepare("SELECT * FROM addresses");
    $stmt->execute();
    $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i=0; $i < count($clients); $i++) {

        $clients[$i]["address"] = null;
        
        for ($j=0; $j < count($addresses); $j++) {
            if($addresses[$j]["clientId"] == $clients[$i]["id"]) {
                $clients[$i]["address"] = $addresses[$j];
                unset($clients[$i]["address"]["clientId"]);
                unset($clients[$i]["address"]["id"]);
                break;
            }
        }

    }
    
    echo json_encode(new ResponseSuccess($clients));
?>