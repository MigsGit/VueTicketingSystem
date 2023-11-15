<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketCloseDetail;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\ResolutionProcedureList;
use App\Models\ResolutionProcedureTitle;

class TicketController extends Controller
{
    public function get_tickets(Request $request){
        DB::beginTransaction();
        try{
            $ticket_list = Ticket::where('created_by', $request->session()->get('id'))
            ->get();
            DB::commit();
            return $ticket_list;

        }
        catch(Exception $e){
            DB::rollback();
            return $e;
        }

    }
    public function save_ticket(Request $request){
        date_default_timezone_set('Asia/Manila');

        if(isset($request->ticketId)){ // * UPDATE
            // return $request->all();
            Ticket::where('id', $request->ticketId)
            ->update([
                'subject' => $request->ticket_subject,
                'message' => $request->ticket_message,
            ]);
            return response()->json(['result' => 1, 'msg' => 'Ticket Successfully Edited!']);

        }
        else{  // * CREATE
            $date = date('Ymd');
            $ticket_number = "SR+$date";

            $unique_number = Ticket::where('created_at', 'LIKE', date('Y')."%")->max('max_unique_no');

            // return $unique_number;
            if(isset($unique_number)){
                $unique_number++;
                $new_unique = str_pad($unique_number, 8, "0", STR_PAD_LEFT);
            }
            else{
                $unique_number = 1;
                $new_unique = str_pad($unique_number, 8, "0", STR_PAD_LEFT);
            }
            $ticket_number = "SR+$date$new_unique";

            $insert_array = array(
                'ticket_no'         => $ticket_number,
                'max_unique_no'     => $new_unique,
                'subject'           => $request->ticket_subject,
                'message'           => $request->ticket_message,
                'created_by'        => $request->session()->get('id'),
                'created_at'        => NOW()
            );

            Ticket::insert(
                $insert_array
            );

            return response()->json(['result' => 1, 'msg' => 'Ticket Successfully Added!']);
        }
    }
    public function get_ticket_info(Request $request){
        DB::beginTransaction();
        try{
            $ticket_data = Ticket::where('id', $request->id)->first();
            DB::commit();

            return response()->json(['ticketData' => $ticket_data]);
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
    }

    public function closingTicket(Request $request){
        // return $request->ticket_id;
        try {
            TicketCloseDetail::insert([
                'ticket_id'=> $request->ticket_id,
                'resolution_procedure_title_id'=>$request->resolution_procedure_title_id,
                'initial_assessement_summary'=>$request->initial_assessement_summary,
                'root_cause'=>$request->root_cause,
                'date_time_closed'=>$request->date_time_closed,
                'date_time_resolved'=>$request->date_time_resolved,
                'reference_link'=>$request->reference_link,
                'is_close'=>$request->is_close,
                'conformance_mode'=>$request->conformance_mode,
                'root_cause'=>$request->root_cause,
            ]);
            Ticket::where('id',$request->ticket_id)->update(['status' => 3]);
            //Request validation
            //email to the user if closed ticket
            $get_data = '';
            $message = '';
            $pkid = 1;
            return Mail::send('mail.iqc_send_email', $get_data, function($message) use($pkid){
                $message->to('mclegaspi@pricon.ph')->cc('')->subject('TEST EMAIL FOR NEW SYSTEM');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function readResolutionByUser(Request $request){
        return ResolutionProcedureTitle::where('updated_by',Auth::user()->id)->get();
    }

    public function readResolutionTitleById(Request $request){
        return ResolutionProcedureTitle::with('resolutionProcedureLists')
                                        ->where('id',$request->selected_resolution_title_id)->get();
    }

    public function createNewResolution(Request $request){
        date_default_timezone_set('Asia/Manila');
        try {
            /**
             * TODO: Edit Procedure List if procedure_title_id exist
             * TODO: Modal OOP for SettingProcList & Assigned Ticket Add New Resolution List
             *
             */
            $value = $request->all();
            $value_resolution_list = $value['inputCount']['key_num'];
            $procedure_title_id = $request->procedure_title_id;
            if($procedure_title_id != null || isset( ($procedure_title_id) ) ){
                ResolutionProcedureTitle::where('id',$procedure_title_id)->update([
                    'updated_by' => 11,
                    'procedure_title' => $request->procedure_title,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                ResolutionProcedureList::where('resolution_procedure_title_id',$procedure_title_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                foreach($value_resolution_list as $value_resolution_list_key_num){
                    foreach($value_resolution_list_key_num as $arr_value_resolution_list){
                        ResolutionProcedureList::insert([
                            'resolution_procedure_title_id' => $procedure_title_id,
                            'procedure_list'=>$arr_value_resolution_list
                        ]);
                    }
                }
                return (['is_success'=>'true']);
            }else{
                $resolution_procedure_title_id = ResolutionProcedureTitle::insertGetId([
                        'updated_by' => 11,
                        'procedure_title' => $request->procedure_title
                ]);
                foreach($value_resolution_list as $value_resolution_list_key_num){
                    foreach($value_resolution_list_key_num as $arr_value_resolution_list){
                        ResolutionProcedureList::insert([
                            'resolution_procedure_title_id' => $resolution_procedure_title_id,
                            'procedure_list'=>$arr_value_resolution_list
                        ]);
                    }
                }
                $resolution_procedure_lists = ResolutionProcedureTitle::with('resolutionProcedureLists')
                                            ->where('id',$resolution_procedure_title_id)
                                            ->limit(5)->get();
                return (['is_success'=>'true','resolution_procedure_title_id' => $resolution_procedure_title_id,'resolution_procedure_lists' => $resolution_procedure_lists]);
            }

        }catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAssignedTickets(){
        return Ticket::where('assigned_to',Auth::user()->id)->get();

    }

    public function sendEmail(){
        return 'success';
    }
}
