<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    public function signatures()
    {
        return $this->hasMany(Signature::class);
    }

    // A document has many users through signatures
    public function users()
    {
        return $this->belongsToMany(User::class, 'signatures');
    }
}
