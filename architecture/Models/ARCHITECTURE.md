# Models Architecture

## Overview

The Models layer represents the application's data entities and business logic. Models define the structure of data and provide methods for data manipulation and validation.

## Purpose

Models in this architecture:

- Define data entities (Student, Teacher, Class, Score, Activity, ActivityType)
- Provide data structure and type safety
- Contain entity-specific business logic
- Work in conjunction with Repositories for data persistence

## Core Components

### 1. **BaseModel**

Located in: `src/Models/BaseModel.php`

**Responsibility**:

- Provides base functionality for all models
- Common attributes and methods
- Attribute management
- Model behavior definition

### 2. **Entity Models**

Located in: `src/Models/`

- **StudentModel** - Represents a student entity
- **TeacherModel** - Represents a teacher entity
- **ClassModel** - Represents a class entity
- **ScoreModel** - Represents a student's score
- **ActivityModel** - Represents an activity
- **ActivityTypeModel** - Represents an activity type

## Model Hierarchy

```
┌─────────────────────────┐
│     BaseModel           │
│  • Common attributes    │
│  • Common methods       │
└────────────┬────────────┘
             │
    ┌────────┼────────┬──────────┬──────────┬─────────────┐
    │        │        │          │          │             │
    ▼        ▼        ▼          ▼          ▼             ▼
┌──────┐ ┌──────┐ ┌────────┐ ┌────────┐ ┌──────────┐ ┌──────────────┐
│Student│ │Teacher│ │ Class  │ │ Score  │ │ Activity │ │ActivityType  │
│Model  │ │Model  │ │ Model  │ │ Model  │ │ Model    │ │ Model        │
└──────┘ └──────┘ └────────┘ └────────┘ └──────────┘ └──────────────┘
```

## Model-Repository Relationship

```
┌─────────────┐
│   Model     │
│  • Data     │
│  • Structure│
│  • Validation│
└──────┬──────┘
       │ works with
       ▼
┌──────────────┐
│  Repository  │
│  • Persist   │
│  • Query     │
│  • Fetch     │
└──────────────┘
```

Models define the "what" (data structure), while Repositories handle the "how" (persistence operations).

## Usage Pattern

### Creating a Model Instance

```php
$student = new StudentModel();
$student->name = 'John Doe';
$student->email = 'john@example.com';
```

### Using with Repository

```php
$studentRepo = new StudentRepository();

// Pass model to repository for persistence
$studentRepo->store($student);

// Retrieve model from repository
$student = $studentRepo->findById(1);
$student->name = 'Jane Doe';
$studentRepo->update($student);
```

## Key Design Patterns

1. **Active Record-like**: Models can contain some behavior
2. **Data Transfer Object**: Models carry data between layers
3. **Repository Pattern**: Models work with Repositories for data access

## Database Integration

Models correspond to database tables:

- StudentModel ↔ students table
- TeacherModel ↔ teachers table
- ClassModel ↔ classes table
- ScoreModel ↔ scores table
- ActivityModel ↔ activities table
- ActivityTypeModel ↔ activity_types table

## Status

✅ Models layer fully implemented and integrated with Repositories
