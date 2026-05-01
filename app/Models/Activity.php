<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// 1. Allow these fields to be saved to the database safely
#[Fillable(['title', 'description', 'activity_date', 'category_id', 'pio_id'])]
class Activity extends Model
{
    use HasFactory;

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /**
     * An Activity belongs to one Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * An Activity belongs to the PIO (User) who created it.
     * We name the function 'owner' but tell Laravel to look for 'pio_id'.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pio_id');
    }

    /**
     * An Activity can have many Students (Users) who RSVP'd.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('status')->withTimestamps();
    }
}