# API Architecture

## Overview

The API layer provides RESTful endpoints for client applications. It handles HTTP requests, processes data, and returns JSON responses. The API is built on top of the Router system and uses Models and Repositories for data access.

## Purpose

The API layer:

- Provides REST endpoints for client applications
- Handles request validation and authentication
- Returns structured JSON responses
- Separates API concerns from web UI concerns
- Enables integration with frontend frameworks

## Structure

```
src/Api/
  ├── auth/
  │   ├── login.php
  │   └── signup.php
  └── sections/
      └── paginated.php
```

## Core API Endpoints

### Authentication API

Located in: `src/Api/auth/`

#### Login Endpoint

- **Route**: `POST /api/auth/login`
- **Purpose**: Authenticate user and return session/token
- **Request Body**:
  ```json
  {
    "email": "user@example.com",
    "password": "password123"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "user": {
      "id": 1,
      "email": "user@example.com",
      "role": "student"
    },
    "message": "Login successful"
  }
  ```

#### Signup Endpoint

- **Route**: `POST /api/auth/signup`
- **Purpose**: Register new user
- **Request Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "student"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "message": "Registration successful"
  }
  ```

### Data API

Located in: `src/Api/sections/`

#### Paginated Endpoint

- **Route**: `GET /api/sections/[type]`
- **Purpose**: Get paginated data for any resource type
- **Query Parameters**:
  - `page` - Page number (default: 1)
  - `limit` - Items per page (default: 20)
  - `sort` - Sort field
  - `order` - Sort order (asc/desc)
- **Response**:
  ```json
  {
    "success": true,
    "data": [...],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 150,
      "pages": 8
    }
  }
  ```

## API Data Flow

```
┌─────────────────────┐
│  Client Request     │
│  (JSON/Form Data)   │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Router             │
│  Match API route    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  API Handler        │
│  (auth/login, etc)  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Validation         │
│  Check input data   │
└──────────┬──────────┘
           │
           ├─ Validation failed
           │   ↓
           │  ┌──────────────┐
           │  │Error Response│
           │  └──────────────┘
           │
           └─ Validation passed
               ↓
┌─────────────────────┐
│  Repository         │
│  CRUD operations    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Response Builder   │
│  Build JSON response│
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Response::json()   │
│  Send to client     │
└─────────────────────┘
```

## Response Format

All API responses follow a consistent format:

### Success Response

```json
{
  "success": true,
  "data": {...},
  "message": "Operation successful"
}
```

### Error Response

```json
{
  "success": false,
  "error": "Error message",
  "message": "What went wrong"
}
```

### Paginated Response

```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 150,
    "pages": 8
  }
}
```

## Authentication

API endpoints use the Auth system for security:

```php
// In API handler
$user = Auth::check();
if (!$user) {
    return Response::json(['error' => 'Unauthorized'], 401);
}

// Use authenticated user
$studentId = $user->id;
```

## Route Organization

```
/api
├── /auth
│   ├── POST /login
│   └── POST /signup
├── /students
│   ├── GET / (list)
│   ├── GET /[id]
│   ├── POST / (create)
│   ├── PUT /[id] (update)
│   └── DELETE /[id] (delete)
├── /teachers
│   └── ... (same as students)
├── /classes
│   └── ... (same as students)
├── /scores
│   └── ... (same as students)
└── /activities
    └── ... (same as students)
```

## Common API Patterns

### List with Pagination

```php
$routes->get('/api/students', function ($request) {
    $page = $request->query->get('page', 1);
    $limit = $request->query->get('limit', 20);

    $repo = new StudentRepository();
    $students = $repo->paginate($page, $limit);

    return Response::json([
        'success' => true,
        'data' => $students['data'],
        'pagination' => [
            'page' => $students['page'],
            'limit' => $limit,
            'total' => $students['total'],
            'pages' => $students['pages']
        ]
    ]);
});
```

### Get Single Item

```php
$routes->get('/api/students/[id]', function ($request) {
    $id = $request->routeParams->get('id');
    $repo = new StudentRepository();
    $student = $repo->findById($id);

    if (!$student) {
        return Response::json(['error' => 'Not found'], 404);
    }

    return Response::json(['success' => true, 'data' => $student]);
});
```

### Create Item

```php
$routes->post('/api/students', function ($request) {
    $data = $request->request->all();

    // Validation
    if (!$data['name'] || !$data['email']) {
        return Response::json(['error' => 'Missing fields'], 400);
    }

    $student = new StudentModel();
    $student->name = $data['name'];
    $student->email = $data['email'];

    $repo = new StudentRepository();
    $repo->store($student);

    return Response::json(
        ['success' => true, 'data' => $student],
        201
    );
});
```

### Update Item

```php
$routes->put('/api/students/[id]', function ($request) {
    $id = $request->routeParams->get('id');
    $data = $request->request->all();

    $repo = new StudentRepository();
    $student = $repo->findById($id);

    if (!$student) {
        return Response::json(['error' => 'Not found'], 404);
    }

    $student->name = $data['name'] ?? $student->name;
    $student->email = $data['email'] ?? $student->email;

    $repo->update($student);

    return Response::json(['success' => true, 'data' => $student]);
});
```

### Delete Item

```php
$routes->delete('/api/students/[id]', function ($request) {
    $id = $request->routeParams->get('id');

    $repo = new StudentRepository();
    $repo->delete($id);

    return Response::json(['success' => true, 'message' => 'Deleted']);
});
```

## Error Handling

API endpoints should return appropriate HTTP status codes:

- `200 OK` - Successful GET, PUT, PATCH
- `201 Created` - Successful POST
- `204 No Content` - Successful DELETE
- `400 Bad Request` - Invalid input
- `401 Unauthorized` - Authentication required
- `403 Forbidden` - Permission denied
- `404 Not Found` - Resource not found
- `500 Internal Server Error` - Server error

## Status

✅ API layer fully implemented with auth and pagination endpoints
