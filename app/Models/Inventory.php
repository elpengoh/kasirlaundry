<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'stock', 'minimum_stock'];

    protected $casts = [
        'stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }
}