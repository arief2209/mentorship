<?php

namespace App\Models;

use Attribute;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory, Sluggable, HasUuids;

    protected $fillable = [
        'is_public',
        'slug',
        'name',
        'description',
        'number_of_days'
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // laravel 10
    // public function numberOfNights(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn($value, $attributes) => $attributes['number_of_days'] - 1
    //     );
    // }

    public function getNumberOfNightsAttribute()
    {
        return $this->number_of_days - 1;
    }

    // alaternative is using this method (route model binding)
    // Route::get('travels/{travel}/tours', [TourController::class, 'index']);
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
}
