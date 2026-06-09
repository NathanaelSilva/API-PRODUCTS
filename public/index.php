<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/conexao.php'; // Importa a função de conexão

$app = AppFactory::create();

// 🛑 ADICIONE ESTA LINHA AQUI EMBAIXO (Ajuste o nome exato da sua pasta):
$app->setBasePath('/API-PRODUCTS');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);


// ----------------------------------------------------
// MÉTODO GET: Listar todos os produtos
// ----------------------------------------------------
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

// Buscar apenas um produto por ID
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

// ----------------------------------------------------
// MÉTODO POST: Inserir um produto
// ----------------------------------------------------
$app->post('/api/produtos', function (Request $request, Response $response) {
    $dados = $request->getParsedBody();
    
    // Validação simples
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

        // Pega o ID que o banco acabou de gerar
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

// ----------------------------------------------------
// MÉTODO PUT: Atualizar um produto
// ----------------------------------------------------
$app->put('/api/produtos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $dados = $request->getParsedBody();

    try {
        $db = getConexao();
        
        // Primeiro verifica se o produto existe
        $stmtCheck = $db->prepare("SELECT id FROM produtos WHERE id = :id");
        $stmtCheck->execute([':id' => $id]);
        if (!$stmtCheck->fetch()) {
            $response->getBody()->write(json_encode(["mensagem" => "Produto não encontrado"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        // Executa o update
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

// ----------------------------------------------------
// MÉTODO DELETE: Eliminar um produto
// ----------------------------------------------------
$app->delete('/api/produtos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    try {
        $db = getConexao();
        
        // Verifica se existe antes de apagar
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