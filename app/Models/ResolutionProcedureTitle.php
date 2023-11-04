<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ProcedureTitleFactory;
use App\Models\ResolutionProcedureList;


class ResolutionProcedureTitle extends Model
{
    use HasFactory;

    public function resolutionProcedureLists(){
        return $this->hasMany(ResolutionProcedureList::class,'resolution_procedure_title_id')->whereNull('deleted_at');
    }
}
