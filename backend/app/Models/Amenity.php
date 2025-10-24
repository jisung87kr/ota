<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'category',
    ];

    /**
     * Get accommodations that have this amenity
     */
    public function accommodations(): BelongsToMany
    {
        return $this->belongsToMany(Accommodation::class)->withTimestamps();
    }

    /**
     * Get rooms that have this amenity
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenity')->withTimestamps();
    }
}
