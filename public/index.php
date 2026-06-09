<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/conexao.php';

$app = AppFactory::create();

$app->setBasePath('/API-PRODUCTS');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);


$app->get('/api/produtos', function (Request $request, Response $response) {
    try {
        $db = getConexao();
        $stmt = $db->query("SELECT * FROM produtos");
        $produtos = $stmt->fetchAll();

        $response->getBody()->write(json_encode($produtos));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(["erro" => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->get('/api/produtos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    try {
        $db = getConexao();
        $stmt = $db->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $produto = $stmt->fetch();

        if (!$produto) {
            $response->getBody()->write(json_encode(["mensagem" => "Produto não encontrado"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($produto));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(["erro" => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->post('/api/produtos', function (Request $request, Response $response) {
    $dados = $request->getParsedBody();

    if (empty($dados['nome']) || empty($dados['preco'])) {
        $response->getBody()->write(json_encode(["mensagem" => "Campos obrigatórios ausentes"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    try {
        $db = getConexao();
        $sql = "INSERT INTO produtos (nome, preco) VALUES (:nome, :preco)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':preco', $dados['preco']);
        $stmt->execute();

        $idGerado = $db->lastInsertId();
        $dados['id'] = $idGerado;

        $resultado = [
            "mensagem" => "Produto criado com sucesso!",
            "produto" => $dados
        ];

        $response->getBody()->write(json_encode($resultado));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(["erro" => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->put('/api/produtos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $dados = $request->getParsedBody();

    try {
        $db = getConexao();

        $stmtCheck = $db->prepare("SELECT id FROM produtos WHERE id = :id");
        $stmtCheck->execute([':id' => $id]);
        if (!$stmtCheck->fetch()) {
            $response->getBody()->write(json_encode(["mensagem" => "Produto não encontrado"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $sql = "UPDATE produtos SET nome = :nome, preco = :preco WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':preco' => $dados['preco'],
            ':id' => $id
        ]);

        $resultado = [
            "mensagem" => "Produto atualizado com sucesso!",
            "id" => $id
        ];

        $response->getBody()->write(json_encode($resultado));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(["erro" => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->delete('/api/produtos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    try {
        $db = getConexao();

        $stmtCheck = $db->prepare("SELECT id FROM produtos WHERE id = :id");
        $stmtCheck->execute([':id' => $id]);
        if (!$stmtCheck->fetch()) {
            $response->getBody()->write(json_encode(["mensagem" => "Produto não encontrado"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $stmt = $db->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $response->getBody()->write(json_encode(["mensagem" => "Produto removido com sucesso"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(["erro" => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->run();