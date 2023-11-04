<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResolutionProcedureTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResolutionProcedureList extends Model
{
    public function belongsToResolutionProcedureTitle(){
        return $this->belongsTo(ResolutionProcedureTitle::class,'id')->whereNull('deleted_at');
    }
}
