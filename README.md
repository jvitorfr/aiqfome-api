# 🍔 aiqfome-api

API RESTful desenvolvida como parte do processo seletivo para Desenvolvedor(a) Back-end no aiqfome.  
Ela gerencia **clientes** e seus **produtos favoritos**, validando os produtos via uma API externa pública.

Repositório: [https://github.com/jvitorfr/aiqfome-api](https://github.com/jvitorfr/aiqfome-api)

---

## 🚀 Tecnologias

- PHP 8.4
- Laravel 11
- PostgreSQL 15
- Docker + Nginx
- JWT Auth (`tymon/jwt-auth`)
- OpenAPI/Swagger

---

## ⚙️ Como rodar o projeto

### Pré-requisitos

- Docker
- Docker Compose
- Git

### Instruções

```bash
# Clone o projeto
git clone https://github.com/jvitorfr/aiqfome-api.git
cd aiqfome-api

# Suba os containers
docker-compose up -d --build

# Acesse o container
docker exec -it aiqfome-api-app bash

# Instale o Laravel (se ainda não estiver na pasta)
composer create-project laravel/laravel . --prefer-dist

# Copie o arquivo de ambiente e gere a chave da aplicação
cp .env.example .env
php artisan key:generate 

# Configure o banco no .env (já ajustado para Docker)
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=aiqfome
DB_USERNAME=postgres
DB_PASSWORD=secret

# Instale dependências e rode as migrations
composer install
php artisan migrate

# Gere a chave JWT
php artisan jwt:secret
