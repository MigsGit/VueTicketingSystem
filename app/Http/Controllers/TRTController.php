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
        $trt_details = Trt::with([
            'user_details'
        ])->get();

        return $trt_details;
    }

    public function save_trt(Request $request){
        // return $request->all();
        DB::beginTransaction();

        if(isset($request->trtId)){ // EDIT
            try{
                Trt::where('id', $request->trtId)
                ->update([
                    'code' => strtoupper($request->code),
                    'duration_day' => $request->days,
                    'duration_hour' => $request->hours,
                    'description' => $request->description,
                    'created_by' => $request->session()->get('id'),
                    'updated_by' => $request->session()->get('id'),
                    'updated_at' => NOW(),
                ]);
                DB::commit();
                return response()->json(['result' => 1, 'msg' => "Successfully edited"]);

            }catch(Exception $e){
                DB::rollback();
                return $e;
            }
        }
        else{ // ADD
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

    public function get_trt_for_edit(Request $request){
        DB::beginTransaction();

        try{
            $trt_details = Trt::where('id', $request->id)->first();
            return $trt_details;
        }catch(Excemption $e){
            DB::rollback();
            return $e;
        }
    }

    public function deact_trt(Request $request){
        DB::beginTransaction();

        try {
            if($request->status == 'delete'){
                Trt::where('id', $request->id)
                ->update([
                    'deleted_at' => NOW()
                ]);
            }
            else{
                Trt::where('id', $request->id)
                ->update([
                    'deleted_at' => null
                ]);
            }

            DB::commit();

            return response()->json(['result'=> 1, 'msg' => "Status update success!"]);
        } catch (Excemption $e) {
            DB::rollback();
            return $e;
        }
    }
}
