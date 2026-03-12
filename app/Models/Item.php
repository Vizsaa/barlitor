<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Item extends Model
{
    use SoftDeletes, Searchable;

    protected $table = 'item';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'title', 'description', 'cost_price', 'sell_price',
        'image_path', 'category', 'stock_quantity', 'supplier_id', 'type',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function reviews()
    {
        return $this->hasMany(ItemReview::class, 'item_id', 'item_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'item_id', 'item_id');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id', 'item_id')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ItemImage::class, 'item_id', 'item_id')
            ->where('is_primary', true)
            ->orderBy('sort_order');
    }

    public function getThumbnailAttribute(): string
    {
        $primary = $this->primaryImage;
        if ($primary) {
            return asset($primary->image_path);
        }

        $first = $this->images->first();
        if ($first) {
            return asset($first->image_path);
        }

        if ($this->image_path) {
            return asset($this->image_path);
        }

        return asset('images/default.png');
    }

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
