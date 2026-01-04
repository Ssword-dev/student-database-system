# Router System Architecture

## Overview

The Router system is built with clear separation of concerns and uses **trie-based (prefix tree) route matching** for O(n) efficiency instead of O(n\*m). It handles HTTP request routing, pattern matching, and request dispatching.

## Components

### 1. **Router** (Core Engine)

Located in: `src/Router/Router.php`

**Responsibility**:

- Core routing logic using trie matching
- Request matching via RouteTrie
- Route dispatching
- Hook event system
- Route collection management
- Request/response handling

**Public Methods**:

- `registerRoute()` - Register a route (called by FluentAPI)
- `addGlobalMiddleware()` - Add middleware (called by FluentAPI)
- `registerHook()` - Register event hooks (called by FluentAPI)
- `match()` - Find matching route for request using trie
- `handle()` - Process request and return response
- `getRoutes()` - Get all registered routes
- `getRouteByName()` - Get route by name
- `getRouteCollection()` - Get the route collection

**Key Point**: Router is NOT directly called by users. It's the internal engine that uses RouteTrie for efficient matching.

### 2. **RouteTrie** (Trie-Based Matching)

Located in: `src/Router/RouteTrie.php`

**Responsibility**:

- Build and maintain route trie structure
- Efficient O(n) route matching (n = number of segments in path)
- Parameter extraction during matching
- Route lookup by path and method

**Algorithm**:

- Routes are organized in a trie by path segments
- Each node stores routes for HTTP methods at that location
- Matching traverses the trie following path segments
- Tries to match literal segments first, then parameters, then wildcards

**Example**:

```
Routes:
  GET /users
  GET /users/[id]
  GET /posts/[slug]

Trie:
  /
  ├── users (GET)
  │   └── [id] (GET)
  └── posts
      └── [slug] (GET)

Matching /users/123:
  / → users (literal match) → [id] (parameter match)
  Result: GET /users/[id] with {id: "123"}
```

### 3. **RouteTrieNode** (Trie Node Classes)

Located in: `src/Router/RouteTrie.php` (with other trie classes)

**Responsibility**:

- Abstract base for trie nodes
- Provides common trie node operations
- Concrete implementations for different segment types

**Node Types**:

- **LiteralSegmentNode** - Matches exact text (e.g., "users", "posts")
- **ParameterSegmentNode** - Matches parameters with optional constraints (e.g., [id], [slug])
- **WildcardSegmentNode** - Matches any remaining segments (e.g., \*)

### 4. **FluentAPI** (User-Facing Interface)

Located in: `src/Router/FluentAPI.php`

**Responsibility**:

- User-friendly route registration API
- Fluent interface implementation
- Route grouping with prefix/middleware tracking
- Forwarding calls to Router

**Public Methods**:

- `get()` - Register GET route
- `post()` - Register POST route
- `put()` - Register PUT route
- `patch()` - Register PATCH route
- `delete()` - Register DELETE route
- `register()` - Register route with multiple methods
- `group()` - Create a route group
- `middleware()` - Add global middleware
- `on()` - Register event hooks

**Key Point**: This is what users interact with. All method calls are forwarded to Router.

### 5. **Route** (Individual Route)

Located in: `src/Router/Route.php`

**Responsibility**:

- Represents a single route
- Pattern matching
- Parameter extraction
- Route metadata (name, constraints, middleware)

### 6. **RouteCollection** (Route Storage)

Located in: `src/Router/RouteCollection.php`

**Responsibility**:

- Store routes efficiently
- Index routes by method and name
- Fast lookups during routing

### 7. **RoutePattern** (Pattern Compilation)

Located in: `src/Router/RoutePattern.php`

**Responsibility**:

- Tokenize route patterns
- Compile patterns to regex
- Extract named parameters

### 8. **Request & Response** (HTTP Objects)

Located in: `src/Router/Request.php` and `src/Router/Response.php`

**Responsibility**:

- Represent HTTP requests/responses
- Provide clean API for accessing request data
- Parameter bags for different data types

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                      User Code (routes.php)                      │
│            Uses FluentAPI to register routes                     │
└──────────────────────┬──────────────────────────────────────────┘
                       │
                       │ $api->get('/path', $handler)
                       ▼
        ┌──────────────────────────────┐
        │      FluentAPI               │
        │  • Routes registration API   │
        │  • Route groups              │
        │  • Middleware management     │
        └──────────────┬───────────────┘
                       │
                       │ $this->router->registerRoute()
                       ▼
        ┌──────────────────────────────┐
        │       Router                 │
        │  • Core routing logic        │
        │  • Request matching          │
        │  • Route dispatching         │
        │  • Hook events               │
        └──────────────┬───────────────┘
                       │
                       ▼
        ┌──────────────────────────────┐
        │    RouteCollection           │
        │  • Stores & indexes routes   │
        │  • Fast lookups              │
        └──────────────────────────────┘
```

## Request Processing Flow

```
┌───────────────────┐
│  HTTP Request     │
└────────┬──────────┘
         │
         ▼
┌─────────────────────────────────────┐
│  Request::fromGlobals()             │
│  Creates Request object             │
└────────┬────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│  Router::handle($request)           │
│  1. Emit 'beforeHandle' hook        │
│  2. Call match()                    │
└────────┬────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│  Router::match($request)            │
│  1. Emit 'beforeMatch' hook         │
│  2. Find matching route             │
│  3. Emit 'afterMatch' hook          │
│  4. Return Route or null            │
└────────┬────────────────────────────┘
         │
         ├─── No match ──────────────┐
         │                           │
         │                    ┌──────▼─────────┐
         │                    │ 404 Response   │
         │                    └────────────────┘
         │
         └─── Match found ─────┐
                               │
                               ▼
                    ┌──────────────────────┐
                    │ dispatch()           │
                    │ Resolve handler      │
                    │ Execute handler      │
                    │ Return Response      │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │ Emit 'afterHandle'   │
                    │ Return Response      │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │ Response::send()     │
                    │ Send to client       │
                    └──────────────────────┘
```

## Integration Points

### Bootstrap (src/bootstrap.php)

```php
// Initialize Router singleton
$router = Router::getInstance();

// Create FluentAPI wrapper
$api = new FluentAPI($router);

// Load routes using FluentAPI
$routeBootstrap = require __DIR__ . '/routes.php';
$routeBootstrap($api);  // Pass FluentAPI, not Router

// Export Router for handling
return $router;
```

### Routes File (src/routes.php)

```php
return function (FluentAPI $routes) {
    // User only interacts with FluentAPI
    $routes->get('/', $handler)->name('home');
    $routes->post('/users', $handler)->name('users.store');

    $routes->group(['prefix' => '/api'], function ($routes) {
        $routes->get('/users', $handler);
    });
};
```

### Front Controller (index.php)

```php
// Get Router from bootstrap (it has all registered routes)
$router = require __DIR__ . '/src/bootstrap.php';

// Handle request with Router
$request = Request::fromGlobals();
$response = $router->handle($request);
$response->send();
```

## Benefits of This Architecture

### Separation of Concerns

1. **Clean Separation**: Router handles core logic, FluentAPI provides user interface
2. **Maintainability**: Easy to modify Router without affecting user API
3. **Testability**: Can test Router independently
4. **Extensibility**: Can extend or replace FluentAPI without changing Router
5. **Clear Intent**: Code shows what it does (registration vs. processing)
6. **Single Responsibility**: Each class has one clear purpose

### Performance (Trie-Based Routing)

1. **O(n) Matching**: Route matching is O(n) where n = path segments, not O(n\*m) where m = number of routes
2. **Scalability**: Adding more routes doesn't degrade matching performance
3. **Memory Efficient**: Trie structure shares common path prefixes, reducing memory overhead
4. **Fast Parameter Extraction**: Parameters are extracted during traversal, no regex compilation needed
5. **Optimal For Large Route Sets**: Especially beneficial for applications with hundreds of routes

## .htaccess Configuration

The `.htaccess` file in the root directory handles URL rewriting:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Exclude static files and directories
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]

    # Rewrite all requests to index.php
    RewriteRule ^(.*)$ /index.php/$1 [L]
</IfModule>
```

**What it does**:

1. Enables the rewrite engine
2. Excludes actual files and directories (static assets)
3. Rewrites all other requests to `index.php`
4. The original path is available as part of `REQUEST_URI`

**Example**:

- Request: `/api/users/123`
- Rewritten to: `/index.php/api/users/123`
- Router extracts path from `REQUEST_URI` and matches to routes

## Class Hierarchy & Relationships

```
┌──────────────────────────────────────────────────────────────┐
│                  Singleton Trait                             │
│  (Ensures only one instance of Router exists)                │
└─────────────────┬────────────────────────────────────────────┘
                  │
                  ▼
     ┌────────────────────────────┐
     │       Router               │
     │  ┌──────────────────────┐  │
     │  │ private:             │  │
     │  │ - routes: RouteCollection│  │
     │  │ - globalMiddleware[] │  │
     │  │ - hooks: array[]     │  │
     │  └──────────────────────┘  │
     │  ┌──────────────────────┐  │
     │  │ public methods:      │  │
     │  │ + registerRoute()    │  │
     │  │ + addGlobalMiddleware│  │
     │  │ + registerHook()     │  │
     │  │ + match()            │  │
     │  │ + handle()           │  │
     │  └──────────────────────┘  │
     └────────────┬───────────────┘
                  ▲
                  │ wraps & delegates to
                  │
     ┌────────────────────────────┐
     │      FluentAPI             │
     │  ┌──────────────────────┐  │
     │  │ private:             │  │
     │  │ - router: Router     │  │
     │  │ - groupPrefix: str   │  │
     │  │ - groupMiddleware[]  │  │
     │  └──────────────────────┘  │
     │  ┌──────────────────────┐  │
     │  │ public methods:      │  │
     │  │ + get()              │  │
     │  │ + post()             │  │
     │  │ + put()              │  │
     │  │ + patch()            │  │
     │  │ + delete()           │  │
     │  │ + group()            │  │
     │  │ + middleware()       │  │
     │  │ + on()               │  │
     │  └──────────────────────┘  │
     └────────────────────────────┘
```

## Route Matching Algorithm

The router uses a **trie-based approach** for O(n) matching efficiency:

```
RouteTrie::match(path, method) {
    1. Parse path into segments: [segment1, segment2, ...]
    2. Start at root node of trie
    3. For each segment, traverse trie:
        ├─ Try exact literal matches first
        ├─ If literal fails, try parameter nodes
        │   └─ Extract parameter value
        ├─ If parameter fails, try wildcard nodes
        │   └─ Extract remaining path
        └─ Continue traversal
    4. At final node, check if route exists for method
        ├─ If found: Return [route, extracted_parameters]
        └─ If not found: Return null
}
```

**Performance**: O(n) where n = number of path segments (not number of routes!)

**Example Trie Structure**:

```
Registered Routes:
  GET  /users
  GET  /users/[id]
  POST /users
  GET  /posts/[slug]
  GET  /admin/*

Trie:
  /
  ├── users
  │   ├── (GET, POST at this node)
  │   └── [id]
  │       └── (GET at this node)
  ├── posts
  │   └── [slug]
  │       └── (GET at this node)
  └── admin
      └── *
          └── (GET at this node)

Matching GET /users/123:
  Traverse: / → users → [id] → Found GET
  Extract: {id: "123"}
  Result: Route with {id: "123"}
```

## Event Hooks

The Router emits several hooks for extensibility:

- **beforeHandle**: Before request processing starts
- **beforeMatch**: Before route matching begins
- **afterMatch**: After route is successfully matched
- **notFound**: When no route matches the request
- **afterHandle**: After handler execution completes

## Status

✅ Router system fully implemented with trie-based routing for optimal performance!
