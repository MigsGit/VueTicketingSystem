<?php

namespace App\Http\Controllers;

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

        $date = date('Ymd');
        $ticket_number = "SR+$date";

        $unique_number = Ticket::where('created_at', 'LIKE', date('Y')."%")->max('max_unique_no');

        if(isset($unique_number)){
            $new_unique = str_pad($unique_number, 8, "0", STR_PAD_LEFT);
        }
        else{
            $unique_number = 1;
            $new_unique = str_pad($unique_number, 8, "0", STR_PAD_LEFT);
        }
        return $new_unique;
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
        return ResolutionProcedureTitle::all();
    }

    public function readResolutionTitleById(Request $request){
        return ResolutionProcedureTitle::with('ResolutionProcedureLists')
                                        ->where('id',$request->selected_resolution_title_id)->get();
    }

    public function createNewResolution(Request $request){
        try {
            $value = $request->all();
            $value_resolution_list = $value['inputCount']['key_num'];
            $resolution_procedure_title_id = ResolutionProcedureTitle::insertGetId([
                    'updated_by' => 1,
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
            $resolution_procedure_lists = ResolutionProcedureTitle::with('ResolutionProcedureLists')
                                        ->where('id',$resolution_procedure_title_id)
                                        ->limit(5)->get();
            return (['resolution_procedure_title_id' => $resolution_procedure_title_id,'resolution_procedure_lists' => $resolution_procedure_lists]);
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
