<?php

namespace App\Models;

use Database\Factories\ConsultationRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A lead from the home page: either a meeting request or a free practice review.
 */
#[Fillable(['type', 'name', 'email', 'phone', 'practice_name', 'specialty', 'preferred_date', 'challenges'])]
class ConsultationRequest extends Model
{
    /** @use HasFactory<ConsultationRequestFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'challenges' => 'array',
        ];
    }
}
