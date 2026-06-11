<?php

namespace App\Database;
use PDO;


class Conexao{

    public static function getConexao(){
        $host = '127.0.0.1';
        $dbname = 'products';
        $user = "root";
        $password = "";

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;

    }

}

