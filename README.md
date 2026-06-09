# API-PRODUCTS

No seu VS Code, crie um arquivo chamado `README.md` na **raiz** do seu projeto (no mesmo lugar onde está o `.htaccess`) e cole o conteúdo abaixo:

```markdown
# 🚀 API RESTful de Produtos - Slim Framework 4

Uma API RESTful simplificada para gerenciamento de produtos (CRUD), construída em PHP utilizando o **Slim Framework 4** e persistência de dados em banco de dados **MySQL** via **PDO**. Desenvolvido em ambiente local com o **Laragon**.

## 📌 Funcionalidades

A API possui os 4 métodos HTTP principais (GET, POST, PUT, DELETE) mapeados para gerenciar o recurso `produtos`:

- **`GET /api/produtos`**: Lista todos os produtos cadastrados no banco de dados.
- **`GET /api/produtos/{id}`**: Busca os detalhes de um produto específico através do ID.
- **`POST /api/produtos`**: Cadastra um novo produto (espera um corpo em formato JSON).
- **`PUT /api/produtos/{id}`**: Atualiza os dados de um produto existente baseado no ID.
- **`DELETE /api/produtos/{id}`**: Remove um produto do banco de dados baseado no ID.

---

## 📁 Estrutura do Projeto

```text
API-PRODUCTS/
├── public/
│   └── index.php       # Ponto de entrada da aplicação (Configuração do Slim e Rotas)
├── src/
│   └── conexao.php     # Script de conexão com o banco de dados via PDO
├── vendor/             # Dependências instaladas pelo Composer
├── .htaccess           # Configuração de reescrita de URL para o Apache do Laragon
├── composer.json       # Definição de dependências do projeto
└── README.md           # Documentação do projeto
