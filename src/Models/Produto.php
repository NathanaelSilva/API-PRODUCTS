<?php

namespace App\Models;
use App\Database\Conexao;
use PDO;

class Produto{
    public static function listarTodos(){
        $db = Conexao::getConexao();
        return $db->query("SELECT * FROM produtos")->fetchAll();
    }


    public static function buscarPorId($id){
        $db = Conexao::getConexao();
        $stmt = $db->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();

    }

    public static function salvar($dados){
        $db = Conexao::getConexao();
        $stmt = $db->prepare("INSERT INTO produtos (nome, preco) VALUES (:nome, :preco)");
        $stmt->execute([':nome' => $dados['nome'], ':preco' => $dados['preco']]);
        return $db->lastInsertId();

    }

    public static function atualizar($id, $dados){
        $db = Conexao::getConexao();
        $stmt = $db->prepare("UPDATE produtos SET nome = :nome, preco = :preco WHERE id = :id");
        $stmt->execute([':nome' => $dados['nome'], ':preco'=> $dados['preco'], ':id' => $id]);
    }

    public static function deletar($id){
        $db = Conexao::getConexao();
        $stmt = $db->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

}