<?php
namespace App\Controllers;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Produto;

class ProdutoController{

    public function listar(Request $request, Response $response){
        $produtos = Produto::listarTodos();
        $response->getBody()->write(json_encode($produtos));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function buscar(Request $request, Response $response, array $args){
        $produto = Produto::buscarPorId($args['id']);
        
        if(!$produto){
            $response->getBody()->write(json_encode(["mensagem" => "Produto não encontrado"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($produto));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function criar(Request $request, Response $response){
        $dados = $request->getParsedBody();

        if(empty($dados['nome']) || empty($dados['preco'])){
            $response->getBody()->write(json_encode(["mensagem"=> "Campos inválidos"]));
            return $response->withHeader('Content-Type','application/json')->withStatus(400);
        }

        $id = Produto::salvar($dados);
        $dados['id'] = $id;

        $response->getBody()->write(json_encode(["mensagem" => "Criado!", "produto" =>$dados]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    
    public function atualizar(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $dados = $request->getParsedBody();

       
        $produtoExistente = Produto::buscarPorId($id);
        if (!$produtoExistente) {
            $response->getBody()->write(json_encode(["mensagem" => "Produto não encontrado"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        if (empty($dados['nome']) || empty($dados['preco'])) {
            $response->getBody()->write(json_encode(["mensagem" => "Campos nome e preco são obrigatórios"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        Produto::atualizar($id, $dados);

        $response->getBody()->write(json_encode([
            "mensagem" => "Produto atualizado com sucesso!",
            "id" => $id
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    }

    public function deletar(Request $request, Response $response, array $args) {
        $id = $args['id'];

        
        $produtoExistente = Produto::buscarPorId($id);
        if (!$produtoExistente) {
            $response->getBody()->write(json_encode(["mensagem" => "Produto não encontrado"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

    
        Produto::deletar($id);

        $response->getBody()->write(json_encode(["mensagem" => "Produto removido com sucesso"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    }
}