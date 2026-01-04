# Layout Architecture

## Overview

The Layout system provides the framework for consistent page rendering and template management. It handles the visual structure of pages, including headers, sidebars, footers, and content areas.

## Purpose

The Layout system:

- Provides reusable page templates
- Manages page structure and sections
- Handles caching of rendered pages
- Separates layout from content
- Enables consistent branding across pages

## Core Components

### 1. **Layout** (Base Class)

Located in: `src/Layout/Layout.php`

**Responsibility**:

- Base layout template
- Define page structure
- Manage layout sections
- Render layout parts

### 2. **DashboardLayout**

Located in: `src/Layout/DashboardLayout.php`

**Responsibility**:

- Dashboard-specific layout
- Sidebar management
- Dashboard header
- Dashboard-specific styling

### 3. **Page**

Located in: `src/Layout/Page.php`

**Responsibility**:

- Represents a page to be rendered
- Contains page content
- Manages page metadata
- Tracks page state

### 4. **PageBuilder**

Located in: `src/Layout/PageBuilder.php`

**Responsibility**:

- Fluent interface for building pages
- Page configuration
- Content assembly
- Layout selection

### 5. **PageCache**

Located in: `src/Layout/PageCache.php`

**Responsibility**:

- Cache rendered pages
- Cache invalidation
- Performance optimization
- Cache key management

## Layout Hierarchy

```
┌──────────────────────┐
│   Layout             │
│  • Base template     │
│  • Sections          │
│  • Rendering         │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  DashboardLayout     │
│  • Dashboard styles  │
│  • Sidebar structure │
│  • Dashboard header  │
└──────────────────────┘
```

## Page Rendering Flow

```
┌──────────────────────────┐
│  Application Handler     │
│  (Route Handler)         │
└───────────┬──────────────┘
            │
            ▼
┌──────────────────────────┐
│  PageBuilder             │
│  • Create page           │
│  • Set content           │
│  • Select layout         │
└───────────┬──────────────┘
            │
            ▼
┌──────────────────────────┐
│  Page Object             │
│  • Contains content      │
│  • References layout     │
│  • Metadata              │
└───────────┬──────────────┘
            │
            ▼
┌──────────────────────────┐
│  PageCache (optional)    │
│  • Check cache           │
│  • Return cached page    │
└───────────┬──────────────┘
            │
            ├─ Cache hit
            │   ↓ Return cached
            │
            └─ Cache miss
                ↓
┌──────────────────────────┐
│  Layout::render()        │
│  • Render structure      │
│  • Render content        │
│  • Include partials      │
└───────────┬──────────────┘
            │
            ▼
┌──────────────────────────┐
│  HTML Output             │
│  Sent to client          │
└──────────────────────────┘
```

## Usage Pattern

### Using PageBuilder

```php
$page = PageBuilder::make()
    ->setLayout(DashboardLayout::class)
    ->setTitle('Dashboard')
    ->setContent($content)
    ->render();

return new Response($page);
```

### Creating a Layout

```php
class DashboardLayout extends Layout {
    public function render() {
        return "
            <html>
                <head>{$this->renderHead()}</head>
                <body>
                    <aside>{$this->renderSidebar()}</aside>
                    <main>{$this->content}</main>
                </body>
            </html>
        ";
    }
}
```

### Using PageCache

```php
$cacheKey = 'page_dashboard_' . Auth::id();
$page = PageCache::get($cacheKey);

if (!$page) {
    $page = PageBuilder::make()
        ->setLayout(DashboardLayout::class)
        ->setTitle('Dashboard')
        ->setContent($content)
        ->render();

    PageCache::set($cacheKey, $page, 3600); // Cache for 1 hour
}

return new Response($page);
```

## Section Management

Layouts define sections that can be filled with content:

```
┌──────────────────────────┐
│   Layout                 │
│                          │
│  ┌────────────────────┐  │
│  │  Head Section      │  │
│  └────────────────────┘  │
│  ┌────────────────────┐  │
│  │  Header Section    │  │
│  └────────────────────┘  │
│  ┌────────────────────┐  │
│  │  Content Section   │  │
│  └────────────────────┘  │
│  ┌────────────────────┐  │
│  │  Footer Section    │  │
│  └────────────────────┘  │
└──────────────────────────┘
```

## Key Design Patterns

### 1. **Template Method Pattern**

- Layout defines structure
- Subclasses fill in details

### 2. **Builder Pattern**

- PageBuilder fluent interface
- Progressive page construction

### 3. **Cache Pattern**

- PageCache for performance
- Transparent caching

### 4. **Strategy Pattern**

- Pluggable layouts
- Different layout implementations

## Template File Integration

Layouts work with view files in `src/Views/`:

```
src/Views/
  ├── dashboard.php
  ├── index.php
  ├── auth/
  │   ├── login.php
  │   ├── logout.php
  │   └── signup.php
  └── dashboard/
      ├── students.php
      ├── teachers.php
      ├── classes.php
      └── ...
```

## Benefits

1. **Consistency**: Unified page structure
2. **Reusability**: Layouts used across pages
3. **Performance**: Caching reduces rendering
4. **Maintainability**: Changes in one place
5. **Flexibility**: Multiple layout options

## Status

✅ Layout system fully implemented and integrated
