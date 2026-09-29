<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\HandoverForm;

class HandoverSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'handover_form_id', 'role', 'name', 'position', 'signature_path', 'signed_at',
    ];

    protected function casts(): array
    {
        return ['signed_at' => 'datetime'];
    }

    public function handoverForm(): BelongsTo
    {
        return $this->belongsTo(HandoverForm::class);
    }
}