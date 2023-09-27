<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trt extends Model
{
    use HasFactory;
    
    protected $table        = "trts";
    protected $connection   = "mysql";

    public function user_details(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
