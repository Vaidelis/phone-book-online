## 🚀 Docker Setup

### Build and Start the Containers

```bash
docker-compose build
docker-compose up -d
```

### Access the Container

```bash
docker exec -it laravel-vue-app bash
```

## ⚙️ Laravel Setup

Once inside the container, run:

```bash
composer install
php artisan key:generate
php artisan migrate
```

## 🌐 Web and phpMyAdmin Access

Open your browser and go to:

```
http://localhost:8081/ - web
http://localhost:8082/ - phpMyAdmin
```

## 📚 API Documentation

### Generate API Docs

```bash
php artisan scribe:generate
```

### Access the Docs

```
http://localhost:8081/docs
```

## 🔐 API Token Generation

To generate an API token, send a `POST` request to:

```
http://localhost:8081/api/tokens/create
```

### Request Body

```json
{
  "email": "your_registered@mail.com",
  "password": "your_password"
}
```

### Store Token in Frontend

Add the generated token to your `.env` file:

```env
VITE_API_TOKEN=your_token_here
```

### External Access (e.g., Postman)

Include the token in the request headers:

```
Authorization: Bearer your_token_here
```
