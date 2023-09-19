<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Ticket;
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
        
        // return ResolutionProcedureList::limit(5)->get();
        // DB::beginTransaction();
        // try{
        //     $ticket_data = Ticket::where('id', $request->id)->first();
        //     DB::commit();

        //     return response()->json(['ticketData' => $ticket_data]);
        // }catch(Exception $e){
        //     DB::rollback();
        //     return $e;
        // }
    }
    public function readResolutionByUser(Request $request){
        
        return ResolutionProcedureTitle::limit(5)->get();
        // DB::beginTransaction();
        // try{
        //     $ticket_data = Ticket::where('id', $request->id)->first();
        //     DB::commit();

        //     return response()->json(['ticketData' => $ticket_data]);
        // }catch(Exception $e){
        //     DB::rollback();
        //     return $e;
        // }
    }
    public function readResolutionTitleById(Request $request){
        // return $request->selected_resolution_title_id;
        return ResolutionProcedureTitle::with('ResolutionProcedureLists')
                                        ->where('id',$request->selected_resolution_title_id)->limit(5)->get();
        // DB::beginTransaction();
        // try{
        //     $ticket_data = Ticket::where('id', $request->id)->first();
        //     DB::commit();

        //     return response()->json(['ticketData' => $ticket_data]);
        // }catch(Exception $e){
        //     DB::rollback();
        //     return $e;
        // }
    }
}
