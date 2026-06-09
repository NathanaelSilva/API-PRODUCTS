# API-PRODUCTS

🛠️ Tecnologias Utilizadas
PHP 8.x

Slim Framework 4 (Micro-framework para rotas e middleware)

Slim PSR-7 (Implementação das mensagens HTTP)

PDO (PHP Data Objects) (Abstração e segurança na conexão com o banco)

MySQL / MariaDB (Armazenamento dos dados)

Laragon (Ambiente de desenvolvimento local)

Postman (Ferramenta para testes de requisições HTTP)

⚙️ Como Configurar e Rodar o Projeto Localmente
1. Pré-requisitos
Certifique-se de ter instalado em sua máquina:

Laragon (ou outro ambiente como XAMPP/Wamp)

Composer

2. Clonar ou mover para o diretório raiz
Mova a pasta API-PRODUCTS para o diretório de projetos do seu servidor local (no Laragon, o caminho padrão é C:\\laragon\\www\\).

3. Instalar Dependências
Abra o terminal dentro da pasta do projeto (C:\\laragon\\www\\API-PRODUCTS) e execute o comando:

Bash
composer install
Caso esteja criando do zero, o comando utilizado foi composer require slim/slim:"4.*" slim/psr7.

4. Configurar o Banco de Dados
Acesse o seu gerenciador do MySQL (como o phpMyAdmin em http://localhost/phpmyadmin6/).

Crie um banco de dados chamado produtos.

Vá até a aba SQL e execute o seguinte script para criar a tabela:

SQL
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(150) NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
Verifique se o arquivo src/conexao.php está com as credenciais corretas do seu ambiente local:

PHP
$host = '127.0.0.1';
$dbname = 'produtos';
$user = 'root';
$password = ''; // Padrão vazio no Laragon
