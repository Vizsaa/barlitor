# Application Functional Requirements Guide

This document serves as a collection of implemented functional requirements and their specific code implementations for future reference.

---

## 1. Search Products on Home Page
**Requirement:** Search products on the home page (no datatable search). Use Laravel Scout with result pagination.

### Implementation Details:
The search is implemented using **Laravel Scout**, which provides a simple, driver-based solution for adding full-text search to Eloquent models.

#### Model Configuration
**File:** `app/Models/Item.php`
The `Item` model uses the `Searchable` trait and defines the searchable data array:

```php
use Laravel\Scout\Searchable;

class Item extends Model
{
    use Searchable, SoftDeletes;
    
    // ...

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'type' => $this->type,
        ];
    }

    public function getScoutKey(): mixed
    {
        return $this->item_id;
    }

    public function getScoutKeyName(): string
    {
        return 'item_id';
    }
}
```

#### Controller Logic
**File:** `app/Http/Controllers/HomeController.php`
The `index` method handles the search request, utilizes the Scout `search()` method on the Item model, eager loads relationships to prevent N+1 issues, and uses `paginate()` for result pagination.

```php
public function index(Request $request)
{
    $query = trim((string) $request->input('search', ''));

    if ($query !== '') {
        $results = Item::search($query)
            ->query(fn ($q) => $q->with(['images', 'primaryImage']))
            ->paginate(12)
            ->appends(['search' => $query]);

        return view('home', [
            'searchQuery' => $query,
            'searchResults' => $results,
            'items' => collect(),
        ]);
    }

    // ... (fallback for no search query)
}
```

This properly fulfills the requirement by bypassing standard database `LIKE` queries in favor of Scout's designated search driver and utilizing Laravel's native paginator.
