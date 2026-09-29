<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\HandoverForm;

class HandoverItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'handover_form_id', 'category', 'item_name', 'asset_code', 'quantity',
        'condition_before', 'condition_after', 'is_returned', 'notes',
    ];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'is_returned' => 'boolean'];
    }

    public function handoverForm(): BelongsTo
    {
        return $this->belongsTo(HandoverForm::class);
    }
}