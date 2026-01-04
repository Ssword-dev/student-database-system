# Application Architecture Overview

This directory contains comprehensive documentation of the application's architecture. Each subdirectory covers a specific architectural layer or component.

## Architecture Layers

### 1. [Router Architecture](Router/ARCHITECTURE.md)

The routing system that handles HTTP requests and dispatches them to appropriate handlers. Includes the core Router engine and the user-facing FluentAPI.

**Key Components**:

- Router (core engine)
- FluentAPI (user interface)
- Route pattern matching
- Request/Response objects
- Event hooks system

**Status**: ✅ Fully implemented

---

### 2. [Models Architecture](Models/ARCHITECTURE.md)

Data entities representing the application's core business objects (Students, Teachers, Classes, Scores, Activities).

**Key Components**:

- BaseModel (common functionality)
- StudentModel, TeacherModel, ClassModel
- ScoreModel, ActivityModel, ActivityTypeModel
- Entity-specific business logic

**Status**: ✅ Fully implemented

---

### 3. [Repositories Architecture](Repositories/ARCHITECTURE.md)

Data access layer providing abstraction between application and database. Handles CRUD operations and query logic.

**Key Components**:

- BaseRepository (common CRUD operations)
- Entity-specific repositories
- Query builder interface
- Pagination support

**Status**: ✅ Fully implemented

---

### 4. [Layout Architecture](Layout/ARCHITECTURE.md)

Page rendering and template system providing consistent structure across pages.

**Key Components**:

- Layout base class
- DashboardLayout
- Page builder
- Page caching system

**Status**: ✅ Fully implemented

---

### 5. [API Architecture](Api/ARCHITECTURE.md)

RESTful API endpoints for client applications with JSON responses.

**Key Components**:

- Authentication endpoints (/api/auth/\*)
- Data endpoints (paginated access to resources)
- Consistent response format
- Error handling

**Status**: ✅ Fully implemented

---

### 6. [Authentication Architecture](Auth/ARCHITECTURE.md)

User authentication and session management system.

**Key Components**:

- Auth class (main interface)
- Session management
- User verification
- Role & permission checking

**Status**: ✅ Fully implemented

---

## Application Data Flow

```
HTTP Request
    ↓
.htaccess (URL rewriting)
    ↓
index.php (front controller)
    ↓
bootstrap.php (initialize)
    ↓
Router (match request)
    ↓
Route Handler (controller/closure)
    ↓
├── May use Repositories
│   ↓
│   └── Interact with Models
│       ↓
│       └── Database
├── May use Layout system
│   ↓
│   └── Render views
└── May use Auth system
    ↓
    └── Check permissions

Handler returns Response
    ↓
Response sent to client
```

## Layer Interactions

```
┌─────────────────────────────────────────┐
│  Presentation Layer (Views)             │
│  • Layouts (Layout/)                    │
│  • Views (src/Views/)                   │
└──────────────┬──────────────────────────┘
               │ renders
               ▼
┌─────────────────────────────────────────┐
│  Application Layer (Routes/Handlers)    │
│  • Router system (Router/)              │
│  • API endpoints (Api/)                 │
│  • Route handlers                       │
└──────────────┬──────────────────────────┘
               │ uses
               ▼
┌─────────────────────────────────────────┐
│  Domain Layer (Business Logic)          │
│  • Models (Models/)                     │
│  • Authentication (Auth/)               │
└──────────────┬──────────────────────────┘
               │ delegates to
               ▼
┌─────────────────────────────────────────┐
│  Data Access Layer                      │
│  • Repositories (Repositories/)         │
└──────────────┬──────────────────────────┘
               │ accesses
               ▼
┌─────────────────────────────────────────┐
│  Database Layer                         │
│  • MySQL database                       │
│  • Database schema                      │
└─────────────────────────────────────────┘
```

## Architecture Principles

### 1. **Separation of Concerns**

Each layer has a specific responsibility:

- Router: Request routing
- Models: Data structure
- Repositories: Data access
- Handlers: Business logic
- Views: Presentation

### 2. **Abstraction**

Each layer abstracts the layer below:

- Repositories abstract database
- Models abstract data structure
- Router abstracts HTTP
- Layout abstracts rendering

### 3. **Single Responsibility**

Each class/module does one thing well:

- StudentModel: Student entity
- StudentRepository: Student data access
- StudentController: Student business logic

### 4. **Dependency Injection**

Dependencies are passed in, not created:

- Repositories injected into handlers
- Models used by repositories
- Services injected into handlers

### 5. **DRY (Don't Repeat Yourself)**

Common functionality is centralized:

- BaseModel, BaseRepository for common operations
- FluentAPI for consistent route registration
- Layout for consistent page structure

## File Organization

```
src/
├── Router/              # Routing system
│   ├── Router.php
│   ├── FluentAPI.php
│   ├── Route.php
│   ├── RouteCollection.php
│   └── ...
├── Models/              # Data entities
│   ├── BaseModel.php
│   ├── StudentModel.php
│   ├── TeacherModel.php
│   └── ...
├── Repositories/        # Data access
│   ├── BaseRepository.php
│   ├── StudentRepository.php
│   └── ...
├── Layout/              # Page rendering
│   ├── Layout.php
│   ├── DashboardLayout.php
│   └── ...
├── Views/               # Templates
│   ├── dashboard.php
│   ├── auth/
│   └── dashboard/
├── Api/                 # API endpoints
│   ├── auth/
│   └── sections/
├── Auth.php             # Authentication
├── Database.php         # Database connection
├── bootstrap.php        # Application initialization
└── routes.php           # Route definitions
```

## Quick Links

- [Router Documentation](Router/ARCHITECTURE.md) - How requests are routed
- [Models Documentation](Models/ARCHITECTURE.md) - Data entity definitions
- [Repositories Documentation](Repositories/ARCHITECTURE.md) - Database access
- [Layout Documentation](Layout/ARCHITECTURE.md) - Page rendering
- [API Documentation](Api/ARCHITECTURE.md) - REST endpoints
- [Auth Documentation](Auth/ARCHITECTURE.md) - User authentication

## Development Guidelines

### Adding a New Feature

1. **Define the Model** - Create entity in `Models/`
2. **Create Repository** - Add data access in `Repositories/`
3. **Register Routes** - Add routes in `src/routes.php`
4. **Create Handler** - Implement business logic
5. **Add Views** - Create templates in `Views/`
6. **Add Tests** - Test each layer independently

### Working with the Router

- Use FluentAPI for route registration
- Use Route groups for organization
- Use middleware for cross-cutting concerns
- Use hooks for event-driven behavior

### Working with Data

- Use Models to represent entities
- Use Repositories for data access
- Inject repositories into handlers
- Keep business logic separate from persistence

### Working with Views

- Use Layout system for consistency
- Create reusable view components
- Use PageBuilder for dynamic pages
- Cache pages when appropriate

## Status

✅ **All layers fully implemented and integrated**

- Router system: Complete with clean URL support
- Models & Repositories: All entities implemented
- Layout system: Rendering infrastructure ready
- API endpoints: Authentication and data access working
- Authentication: Session management implemented

The application is ready for feature development!
