<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaPersonnel extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     * This tells Laravel the correct table name to use.
     *
     * @var string
     */
    protected $table = 'hea_personnel'; // Add this line

    protected $fillable = ['user_id', 'staff_id', 'unit'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}