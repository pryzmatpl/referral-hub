<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents User Weight
 */
class UserWeight extends Model
{
    // Set the table name to match the migration
    protected $table = 'user_weights';

    // Only these fields are mass assignable
    protected $fillable = [
        'weights',
        'userid',
        'keywords'
    ];

    // Automatically cast these JSON columns into arrays
    protected $casts = [
        'weights'  => 'array',
        'keywords' => 'array'
    ];
}