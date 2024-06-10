<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'document_id'];

    // A signature belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A signature belongs to a document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
