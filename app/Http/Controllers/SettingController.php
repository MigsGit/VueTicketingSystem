<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ResolutionProcedureList;
use App\Models\ResolutionProcedureTitle;
use DataTables;
class SettingController extends Controller
{
    //
    public function readResolutionByUserSetting(){
        $resolution_procedure_title = ResolutionProcedureTitle::with('resolutionProcedureLists')
                                                                ->where('updated_by',Auth::user()->id)->get();

        return DataTables::of($resolution_procedure_title)
        ->addColumn('resolution_procedure_lists',function($row){
            $arr_procedure_list = [];
            foreach ($row->resolutionProcedureLists as $key => $list) {
                $arr_procedure_list[] = $list->procedure_list;
            }
            return implode('<br>',$arr_procedure_list);
        })
        ->addColumn('action',function($row){
            // return $row->id;
            return $btn = '<button data-id = "'.$row->id.'"  class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalSaveResProcedure" id="editResProcedure" type="button" title="Edit"><i class="fas fa-edit"></i>Edit</button>';
            // return $btn = '<button data-id = "'.$row->id.'" id="editResProcedure" type="button" class="btn btn-info btn-sm" title="Edit"></i>Edit</button>';
        })
        ->rawColumns(['resolution_procedure_lists','action'])
        ->make(true);
    }
}
