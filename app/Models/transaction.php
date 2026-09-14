<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'code_id',
        'patient',
        'total_amount',
        'discount',
        'final_amount',
        'user_commission',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id'); 
    }
    public function code()
    {
        return $this->belongsTo(code::class,'code_id'); 
    }
}
