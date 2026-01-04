# Authentication Architecture

## Overview

The Authentication system handles user identity verification, session management, and access control. It provides a secure way to authenticate users and maintain their sessions throughout the application.

## Purpose

The Auth system:

- Verify user credentials
- Manage user sessions
- Provide access control
- Handle password security
- Track authenticated state

## Core Components

### 1. **Auth** (Main Class)

Located in: `src/Auth.php`

**Responsibility**:

- User authentication
- Session management
- Access control
- Password verification
- User state tracking

**Public Methods**:

- `check()` - Get currently authenticated user
- `authenticate($email, $password)` - Login user
- `logout()` - Logout user
- `isAuthenticated()` - Check if user is logged in
- `hasRole($role)` - Check user role
- `can($permission)` - Check permission

### 2. **AuthException**

Located in: `src/Exceptions/AuthException.php`

**Responsibility**:

- Authentication-specific exceptions
- Clear error messages
- Error categorization

## Authentication Flow

```
┌──────────────────────┐
│  User Submits Login  │
│  (email, password)   │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  API Handler         │
│  POST /api/auth/login│
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  Validate Input      │
│  Check fields        │
└──────────┬───────────┘
           │
           ├─ Invalid
           │   ↓
           │  ┌──────────────┐
           │  │Error Response│
           │  └──────────────┘
           │
           └─ Valid
               ↓
┌──────────────────────┐
│  Auth::authenticate()│
│  Verify credentials  │
└──────────┬───────────┘
           │
           ├─ Invalid credentials
           │   ↓
           │  ┌──────────────┐
           │  │Login failed  │
           │  └──────────────┘
           │
           └─ Credentials valid
               ↓
┌──────────────────────┐
│  Create Session      │
│  Set auth cookie     │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  Return User Data    │
│  Success response    │
└──────────────────────┘
```

## Session Management

```
┌──────────────────────────────┐
│  User Logs In                │
│  Creates session             │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│  Session Started             │
│  $_SESSION['user_id'] = id   │
│  $_SESSION['user'] = user    │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│  Subsequent Requests         │
│  Session maintained          │
│  Auth::check() returns user  │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│  User Logs Out               │
│  Session destroyed           │
│  $_SESSION = []              │
└──────────────────────────────┘
```

## Usage Patterns

### Checking Authentication

```php
// In any handler
$user = Auth::check();

if (!$user) {
    return Response::json(['error' => 'Unauthorized'], 401);
}

// Use authenticated user
$userId = $user->id;
$userEmail = $user->email;
$userRole = $user->role;
```

### Authenticating User

```php
// In login handler
try {
    $user = Auth::authenticate($email, $password);
    // User is now authenticated
    return Response::json([
        'success' => true,
        'user' => $user
    ]);
} catch (AuthException $e) {
    return Response::json([
        'error' => $e->getMessage()
    ], 401);
}
```

### Checking Permissions

```php
$user = Auth::check();

if ($user->hasRole('admin')) {
    // Admin-only code
}

if ($user->can('edit_classes')) {
    // Permission-granted code
}
```

### Logging Out

```php
Auth::logout();
return Response::redirect('/');
```

## Protected Routes

Routes can be protected with authentication middleware:

```php
// Protect entire group
$routes->group(['middleware' => ['auth']], function ($routes) {
    $routes->get('/dashboard', DashboardController::class);
    $routes->post('/api/students', StudentController::class);
});

// Protect single route
$routes->get('/profile', ProfileController::class)
    ->middleware('auth');
```

## User Model

The authentication system works with user entities:

```php
class User {
    public $id;
    public $email;
    public $name;
    public $password; // hashed
    public $role;
    public $status;
}
```

## Password Security

Passwords are hashed using PHP's password hashing:

```php
// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Verify password
if (password_verify($plainPassword, $hashedPassword)) {
    // Password is correct
}
```

## Roles & Permissions

### Roles

- **admin** - Full system access
- **teacher** - Can manage classes and scores
- **student** - Can view their own data

### Permissions

- `edit_students`
- `edit_teachers`
- `edit_classes`
- `edit_scores`
- `view_reports`
- `manage_activities`

## Session Storage

Sessions are stored in PHP's default session storage:

```
/tmp/sess_[session_id]   (Unix/Linux)
C:\Windows\Temp\         (Windows)
```

Or custom session handlers can be configured in `bootstrap.php`.

## Authentication Middleware

The auth middleware checks if user is authenticated:

```php
// In middleware
$router->registerHook('beforeHandle', function ($request) {
    if (needsAuth($request->path)) {
        if (!Auth::check()) {
            return Response::redirect('/login');
        }
    }
});
```

## API Authentication

For API endpoints, authentication is checked before processing:

```php
$routes->group(['prefix' => '/api', 'middleware' => ['auth']], function ($routes) {
    $routes->get('/users', function ($request) {
        $user = Auth::check();
        // API-specific code
    });
});
```

## Status

✅ Authentication system fully implemented with session management
