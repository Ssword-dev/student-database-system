# Repositories Architecture

## Overview

The Repositories layer implements the Data Access layer, providing an abstraction between the application and the database. It handles all database operations and provides a clean interface for data persistence.

## Purpose

Repositories:

- Abstract database operations
- Provide CRUD operations (Create, Read, Update, Delete)
- Handle query logic
- Decouple business logic from data access
- Enable easier testing through mock repositories

## Core Components

### 1. **BaseRepository**

Located in: `src/Repositories/BaseRepository.php`

**Responsibility**:

- Provides base functionality for all repositories
- Common database operations
- Connection management
- Query building
- Transaction handling

**Common Methods**:

- `findById($id)` - Get entity by ID
- `all()` - Get all entities
- `store($model)` - Insert new entity
- `update($model)` - Update existing entity
- `delete($id)` - Delete entity
- `query()` - Start a query
- `paginate($page, $limit)` - Get paginated results

### 2. **Entity Repositories**

Located in: `src/Repositories/`

- **StudentRepository** - Manages Student data
- **TeacherRepository** - Manages Teacher data
- **ClassRepository** - Manages Class data
- **ScoreRepository** - Manages Score data
- **ActivityRepository** - Manages Activity data
- **ActivityTypeRepository** - Manages ActivityType data

Each repository can extend BaseRepository and add entity-specific methods.

## Repository Hierarchy

```
┌────────────────────────┐
│   BaseRepository       │
│  • CRUD operations     │
│  • Query building      │
│  • Transaction handling│
└────────────┬───────────┘
             │
    ┌────────┼────────┬──────────┬──────────┬──────────────┐
    │        │        │          │          │              │
    ▼        ▼        ▼          ▼          ▼              ▼
┌──────┐ ┌──────┐ ┌────────┐ ┌────────┐ ┌──────────┐ ┌──────────────┐
│Student│ │Teacher│ │ Class  │ │ Score  │ │ Activity │ │ActivityType  │
│Repo   │ │Repo   │ │ Repo   │ │ Repo   │ │ Repo     │ │ Repo         │
└──────┘ └──────┘ └────────┘ └────────┘ └──────────┘ └──────────────┘
```

## Data Flow

```
┌──────────────────┐
│  Application     │
│  (Controllers)   │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│  Repository      │
│  (Data Access)   │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│  Database        │
│  (Persistence)   │
└──────────────────┘
```

## Usage Patterns

### Basic CRUD Operations

```php
$studentRepo = new StudentRepository();

// Create
$student = new StudentModel();
$student->name = 'John Doe';
$student->email = 'john@example.com';
$studentRepo->store($student);

// Read
$student = $studentRepo->findById(1);

// Read All
$students = $studentRepo->all();

// Update
$student->name = 'Jane Doe';
$studentRepo->update($student);

// Delete
$studentRepo->delete(1);
```

### Custom Queries

```php
// Entity-specific methods
$activeStudents = $studentRepo->findByStatus('active');
$studentsByClass = $studentRepo->findByClass($classId);

// Query builder pattern
$results = $studentRepo->query()
    ->where('status', 'active')
    ->where('class_id', 5)
    ->get();
```

### Pagination

```php
$page = 1;
$limit = 20;
$results = $studentRepo->paginate($page, $limit);

// Returns:
// {
//   'data' => [...],
//   'total' => 150,
//   'page' => 1,
//   'pages' => 8
// }
```

## Key Design Principles

### 1. **Separation of Concerns**

- Models: Data structure
- Repositories: Data access
- Controllers: Business logic

### 2. **Single Responsibility**

- Each repository handles one entity type
- Each method has one clear purpose

### 3. **Dependency Injection**

- Repositories are injected into controllers
- Enables testing with mock repositories

### 4. **Query Abstraction**

- Repository provides query interface
- Hides database-specific SQL

## Benefits

1. **Testability**: Easy to mock repositories for unit tests
2. **Flexibility**: Can swap database implementations
3. **Maintainability**: Centralized data access logic
4. **Reusability**: Same repository methods across application
5. **Consistency**: Uniform way to access data

## Database Integration

Each repository maps to a database table:

- StudentRepository ↔ students table
- TeacherRepository ↔ teachers table
- ClassRepository ↔ classes table
- ScoreRepository ↔ scores table
- ActivityRepository ↔ activities table
- ActivityTypeRepository ↔ activity_types table

## Transaction Support

```php
$studentRepo = new StudentRepository();

$studentRepo->beginTransaction();
try {
    $studentRepo->store($student1);
    $studentRepo->store($student2);
    $studentRepo->commit();
} catch (Exception $e) {
    $studentRepo->rollback();
    throw $e;
}
```

## Status

✅ Repositories layer fully implemented with all entities
