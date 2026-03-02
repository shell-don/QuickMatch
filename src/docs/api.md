# API REST Documentation

## Base URL
```
http://localhost/api/v1
```

## Authentication

### Register
```http
POST /auth/register
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Utilisateur créé avec succès.",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "roles": ["user"]
  },
  "token": "..."
}
```

### Login
```http
POST /auth/login
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Connexion réussie.",
  "data": {...},
  "token": "..."
}
```

### Logout
```http
POST /auth/logout
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Déconnexion réussie."
}
```

### Get Current User
```http
GET /auth/me
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "roles": ["user"]
  }
}
```

### Get User Permissions
```http
GET /auth/permissions
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "roles": ["user"],
    "permissions": ["user.create", "user.view"]
  }
}
```

## Admin Endpoints (Requires admin role)

### List Users
```http
GET /admin/users
Authorization: Bearer {token}
```

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | int | 1 | Page number |
| per_page | int | 15 | Items per page (max 100) |
| search | string | - | Search by name or email |
| role | string | - | Filter by role |
| sort | string | created_at | Sort column |
| direction | asc/desc | desc | Sort direction |

**Response (200):**
```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 15,
    "total": 20
  }
}
```

### Create User
```http
POST /admin/users
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!",
  "roles": ["user"]
}
```

### Get User
```http
GET /admin/users/{id}
Authorization: Bearer {token}
```

### Update User
```http
PUT /admin/users/{id}
Authorization: Bearer {token}
```

### Delete User
```http
DELETE /admin/users/{id}
Authorization: Bearer {token}
```

### List Roles
```http
GET /admin/roles
Authorization: Bearer {token}
```

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["L'email est requis."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Access denied"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 422 Unprocessable Entity
```json
{
  "success": false,
  "message": "Operation failed"
}
```

### 429 Too Many Requests
```json
{
  "message": "Too many attempts."
}
```

## Rate Limiting

- Authentication endpoints: 100 requests per minute
- Other endpoints: 100 requests per minute per user

## Headers

Include in all requests:
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```
