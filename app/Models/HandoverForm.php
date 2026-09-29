<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Employee;
use App\Models\HandoverItem;
use App\Models\HandoverSignature;
use App\Models\User;

class HandoverForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'created_by', 'form_number', 'handover_date',
        'effective_exit_date', 'reason', 'status', 'notes',
        'urgent_activity', 'urgent_document', 'routine_activity', 'routine_document',
        'project_name', 'project_document', 'additional_notes', 'submitted_at', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'handover_date' => 'date',
            'effective_exit_date' => 'date',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(HandoverItem::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(HandoverSignature::class);
    }
}