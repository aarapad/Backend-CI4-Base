# API Documentation

## Authentication Endpoints

### POST /api/login
Authentication endpoint for users.

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "userpassword"
}

Response (200 OK):
```json
{
    "token": "eyJ0eXAiOiJKV1...",
    "user": {
        "id": "1",
        "email": "user@example.com",
        "name": "User Name",
        "role": "admin"
    }
}

### POST /api/logout
Logout endpoint (requires authentication).

Headers:

Authorization: Bearer {token}

Response (200 OK):
```json
{
    "message": "Sesión cerrada correctamente"
}