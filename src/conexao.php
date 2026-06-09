<?php

function getConexao() {
    $host = '127.0.0.1';
    $dbname = 'products'; 
    $user = 'root';
    $password = '';

    try {
        // Configura a conexão PDO e ativa o modo de erros para exceções
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Define o retorno padrão como array associativo
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $pdo;
    } catch (PDOException $e) {
        // Se falhar, retorna o erro em formato JSON
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(["erro" => "Falha na conexão com o banco: " . $e->getMessage()]);
        exit;
    }
}