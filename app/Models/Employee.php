<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Department;
use App\Models\HandoverForm;
use App\Models\User;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'department_id', 'employee_number', 'name', 'email',
        'position', 'joined_at', 'left_at',
    ];

    protected function casts(): array
    {
        return ['joined_at' => 'date', 'left_at' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function handoverForms(): HasMany
    {
        return $this->hasMany(HandoverForm::class);
    }
}