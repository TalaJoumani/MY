<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class code extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'code',
        'discount',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'code_id');
    }
}
