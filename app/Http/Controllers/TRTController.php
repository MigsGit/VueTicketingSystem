<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Trt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TRTController extends Controller
{
    public function get_trt(Request $request){
        $trt_details = Trt::whereNull('deleted_at')->get();

        return $trt_details;
    }

    public function save_trt(Request $request){
        // return $request->all();
        if(isset($request->trtId)){ // EDIT
           
        }
        else{ // ADD
            DB::beginTransaction();
            try{
                Trt::insert([
                    'code' => strtoupper($request->code),
                    'duration_day' => $request->days,
                    'duration_hour' => $request->hours,
                    'description' => $request->description,
                    'created_by' => $request->session()->get('id'),
                    'created_at' => NOW()
                ]); 
                DB::commit();
                return response()->json(['result' => 1, 'msg' => "Successfully Added"]);

            }catch(Exception $e){
                DB::rollback();
                return response()->json(['error' => $e], 402);
            }
        }
    }
}
