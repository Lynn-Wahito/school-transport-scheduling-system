<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDriver extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'availability_status',
    ];

    /**
     * Inverse one-to-one relationship between School Driver and School Vehicle
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolVehicle(){
        return $this->belongsTo(SchoolVehicle::class);
    }
}