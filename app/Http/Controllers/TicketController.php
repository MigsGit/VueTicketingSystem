<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

        }
        else{  // * CREATE
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
            $ticket_number = "SR+$date$new_unique";

            $insert_array = array(
                'ticket_no'         => $ticket_number,
                'max_unique_no'     => $new_unique,
                'subject'           => $request->ticket_subject,
                'message'           => $request->ticket_message,
                'created_by'        => $request->session()->get('id')
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
            $ticket_data = Ticket::where('id', $request->id)->get();
            DB::commit();

            return response()->json(['ticketData' => $ticket_data]);
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
    }
}
