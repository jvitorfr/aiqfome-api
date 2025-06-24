
# 🍔 aiqfome-api

API RESTful desenvolvida como parte do processo seletivo para Desenvolvedor(a) Back-end no aiqfome.  
Ela gerencia **clientes** e seus **produtos favoritos**, validando os produtos via uma API externa pública (FakeStore API).

Repositório: [https://github.com/jvitorfr/aiqfome-api](https://github.com/jvitorfr/aiqfome-api)

---

## 🚀 Tecnologias

- PHP 8.4
- Laravel 11
- PostgreSQL 15
- REDIS
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

# Rode as migrations
php artisan migrate

# Gere a chave JWT
php artisan jwt:secret

# Popule com dados de teste (opcional)
php artisan db:seed

# Gere a documentação Swagger
php artisan l5-swagger:generate
```

---

## 🔐 Autenticação

### Clientes (auth:api – JWT)
- POST `/api/client/login`
- POST `/api/client/register`
- Requisições autenticadas devem conter: `Authorization: Bearer {token}`

### Usuários/Admins (auth:sanctum)
- POST `/api/admin/login`
- Requisições autenticadas devem conter: `Authorization: Bearer {token}`
- Usuário de teste criado com: `php artisan db:seed`

---

## 📍 Endpoints disponíveis

### 📦 Clientes (admin)
| Método | Rota                         | Descrição               |
|--------|------------------------------|-------------------------|
| GET    | /api/admin/clients           | Lista clientes          |
| POST   | /api/admin/clients           | Cria cliente            |
| GET    | /api/admin/clients/{id}      | Mostra cliente          |
| PUT    | /api/admin/clients/{id}      | Atualiza cliente        |
| DELETE | /api/admin/clients/{id}      | Remove cliente          |

### ⭐ Favoritos (admin)
| Método | Rota                                           | Descrição                        |
|--------|------------------------------------------------|----------------------------------|
| GET    | /api/admin/clients/{client}/favorites          | Lista favoritos do cliente       |
| POST   | /api/admin/clients/{client}/favorites/{product}| Adiciona favorito ao cliente     |
| DELETE | /api/admin/clients/{client}/favorites/{product}| Remove favorito do cliente       |

### 👤 Autenticação Admin
| Método | Rota               | Descrição             |
|--------|--------------------|-----------------------|
| POST   | /api/admin/login   | Login de admin        |
| GET    | /api/admin/me      | Dados do admin logado |
| POST   | /api/admin/logout  | Logout do admin       |

### 👨‍💻 Cliente autenticado
| Método | Rota                     | Descrição                         |
|--------|--------------------------|-----------------------------------|
| POST   | /api/client/register     | Registro de cliente               |
| POST   | /api/client/login        | Login de cliente                  |
| GET    | /api/client/me           | Dados do cliente logado           |
| POST   | /api/client/logout       | Logout do cliente                 |

### 📦 Produtos (cliente logado)
| Método | Rota                          | Descrição              |
|--------|-------------------------------|------------------------|
| GET    | /api/client/products          | Lista todos os produtos|
| GET    | /api/client/products/{id}     | Detalhes do produto    |
| PUT    | /api/client/products/{id}     | Atualiza produto       |
| DELETE | /api/client/products/{id}     | Remove produto         |

### ⭐ Favoritos (cliente logado)
| Método | Rota                            | Descrição                      |
|--------|---------------------------------|--------------------------------|
| GET    | /api/client/favorites           | Lista favoritos                |
| POST   | /api/client/favorites/{product} | Adiciona favorito              |
| DELETE | /api/client/favorites/{product} | Remove favorito                |

---

## 🔗 Integração com API Externa

Todos os produtos são validados via FakeStore API:

- `GET https://fakestoreapi.com/products`
- `GET https://fakestoreapi.com/products/{id}`

---

## 🧪 Dados de Teste

### Admin
```json
{
  "email": "admin@aiqfome.com",
  "password": "senhaSegura123"
}
```

### Cliente
```json
{
  "email": "cliente1@teste.com",
  "password": "senha123"
}
```

---

## 📄 Documentação Swagger

Documentação disponível em:  
🔗 `http://localhost:8080/api/documentation`
