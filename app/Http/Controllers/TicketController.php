<?php

namespace App\Http\Controllers;

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
