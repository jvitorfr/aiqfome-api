# 🍔 aiqfome-api

API RESTful desenvolvida como parte do processo seletivo para Desenvolvedor(a) Back-end no aiqfome.  
Ela gerencia **clientes** e seus **produtos favoritos**, validando os produtos via uma API externa pública (FakeStore API).

Repositório: [https://github.com/jvitorfr/aiqfome-api](https://github.com/jvitorfr/aiqfome-api)

---

## 🚀 Tecnologias

- PHP 8.4
- Laravel 11
- PostgreSQL 15
- Docker + Nginx
- JWT Auth (`tymon/jwt-auth`) para autenticação de **clientes**
- Laravel Sanctum para autenticação de **usuários (admins)**
- OpenAPI/Swagger (`l5-swagger`) para documentação automática
- Symfony HTTP Client (integração externa)
- FAKESTORE API

---

## ⚙️ Como rodar o projeto

### ✅ Pré-requisitos

- Docker
- Docker Compose
- Git

### 🧪 Instruções

```bash
# Clone o projeto
git clone https://github.com/jvitorfr/aiqfome-api.git
cd aiqfome-api

# Suba os containers
docker-compose up -d --build

# Acesse o container da aplicação
docker exec -it aiqfome-api-app bash

# Instale dependências
composer install

# Copie o .env e gere a chave da aplicação
cp .env.example .env
php artisan key:generate 

# Configure o banco (pré-configurado para Docker)
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=aiqfome
DB_USERNAME=postgres
DB_PASSWORD=secret

# Rode as migrations
php artisan migrate

# Gere a chave JWT
php artisan jwt:secret

# Popule com dados de teste (opcional)
php artisan db:seed

# Gere a documentação Swagger
php artisan l5-swagger:generate

🔐 Autenticação
Clientes (auth:api – JWT)
POST /api/client/login

POST /api/client/register

Requisições autenticadas devem conter:
Authorization: Bearer {token}

Usuários/Admins (auth:sanctum)
POST /api/admin/login

php artisan db:seed cria usuário de teste

Requisições autenticadas devem conter:
Authorization: Bearer {token}

📍 Endpoints disponíveis
📦 Clientes (/api/client)
| Método | Rota      | Descrição               |
| ------ | --------- | ----------------------- |
| POST   | /register | Criação de cliente      |
| POST   | /login    | Login (JWT)             |
| GET    | /me       | Dados do cliente logado |
| POST   | /logout   | Logout do cliente       |

Produtos (Cliente logado)
| Método | Rota           | Descrição                  |
| ------ | -------------- | -------------------------- |
| GET    | /products      | Lista produtos disponíveis |
| GET    | /products/{id} | Detalhes do produto        |
| PUT    | /products/{id} | Atualiza produto           |
| DELETE | /products/{id} | Remove produto             |

Favoritos (Cliente logado)
| Método | Rota             | Descrição                         |
| ------ | ---------------- | --------------------------------- |
| GET    | /favorites       | Lista favoritos do cliente logado |
| POST   | /favorites/plus  | Adiciona ou incrementa favorito   |
| POST   | /favorites/minus | Decrementa ou remove favorito     |

🛠️ Usuários administradores Admin (/api/admin)
| Método | Rota    | Descrição               |
| ------ | ------- | ----------------------- |
| POST   | /login  | Login admin (Sanctum)   |
| GET    | /me     | Dados do usuário logado |
| POST   | /logout | Logout                  |

Ações sobre favoritos de qualquer cliente
| Método | Rota                              | Descrição                     |
| ------ | --------------------------------- | ----------------------------- |
| GET    | /clients/{client}/favorites       | Lista favoritos de um cliente |
| POST   | /clients/{client}/favorites/plus  | Adiciona/incrementa favorito  |
| POST   | /clients/{client}/favorites/minus | Decrementa ou remove favorito |

🔗 Integração com API Externa
Todos os produtos são validados via FakeStore API:

Listar produtos: GET https://fakestoreapi.com/products

Buscar por ID: GET https://fakestoreapi.com/products/{id}

🧪 Dados de Teste
Admin

{
  "email": "admin@aiqfome.com",
  "password": "senhaSegura123"
}

Cliente
{
  "email": "cliente1@teste.com",
  "password": "senha123"
}
Ambos são criados com:

php artisan db:seed

📄 Documentação Swagger
A documentação completa da API está disponível em:

🔗 http://localhost:8080/api/documentation

