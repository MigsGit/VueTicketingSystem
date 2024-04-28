<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
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
        try{
            $ticket_list = Ticket::whereNull('deleted_at')->where('created_by', $request->session()->get('id'))->get();
            return DataTables::of($ticket_list)
            ->addColumn('action',function($row){

                $result = '';
                // $result .= '<center>';
                $result .='
                <div class="row">
                    <div class="col-sm-3 mr-3">
                        <button type="button" class="btn btn-info btn-sm"  id="btnEditTicket" data-bs-toggle="modal" data-bs-target="#ModalTicket" ticket-id = "'.$row->id.'" title="Edit"><i class="fas fa-user-edit"></i></button>
                    </div>
                    <div class="col-sm-3 mr-3">
                    </div>
                    <div class="col-sm-3 mr-3">
                        <button type="button" class="btn btn-outline-info btn-sm"  id="btnViewTicket" data-bs-toggle="modal" data-bs-target="#ModalTicket" ticket-id = "'.$row->id.'" title="View"><i class="fas fa-edit"></i></button>
                    </div>
                </div>';
                // $result .= '</center>';
                return $result;
                // return $btn = '<button data-id = "'.$row->id.'" data-bs-toggle="modal" data-bs-target="#modalSaveResProcedure" id="editResProcedure" type="button" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-edit"></i>Edit</button>';
                // return $btn = '<button data-id = "'.$row->id.'" id="editResProcedure" type="button" class="btn btn-info btn-sm" title="Edit"></i>Edit</button>';
            })
            ->addColumn('get_status',function($row){
                // return $row->id;
                switch ($row->status) {
                    case 0:
                        $status = 'Pending';
                        $bg_color = 'bg-info';
                        break;
                    case 1:
                        $status = 'Pending';
                        $bg_color = 'bg-info';
                        break;
                    case 2:
                        $status = '2';
                        $bg_color = 'bg-warning';
                        break;
                    case 3:
                        $status = 'Closed';
                        $bg_color = 'bg-success';
                        break;
                    default:
                        $status = 'Unknown';
                        $bg_color = 'bg-warning';
                        break;
                }
                $result = '';
                $result .='<center>';
                $result .='<span class="badge '.$bg_color.'"> '.$status.' </span>';
                $result .='</center>';
                return $result;
            })
            ->rawColumns(['action','get_status'])
            ->make(true);
        }
        catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
    }
    public function save_ticket(Request $request){
        date_default_timezone_set('Asia/Manila');
        try {
            DB::beginTransaction();
            //?: EDIT ALLOWED IN USER THEN EDIT OF ASSIGNED ALLOW IN ISS, if user is ISS
            if(isset($request->ticketId)){  // * UPDATE
                // if (Auth::user()->roles == 1){
                //     Ticket::where('id', $request->ticketId)
                //     ->update([
                //         'assigned_to' => implode(',',$request->assignedPerson),
                //         'trt_id' => $request->trtId,
                //     ]);
                //     DB::commit();
                //     return response()->json(['result' => 1, 'msg' => 'Ticket Successfully Edited!']);
                // }
                Ticket::where('id', $request->ticketId)
                ->update([
                    'subject' => $request->subject,
                    'message' => $request->message,
                ]);
                DB::commit();
                return response()->json(['result' => 1, 'msg' => 'Ticket Successfully Edited!']);
            }else{  // * CREATE
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
                // DB::rollback();
                DB::commit();
                return response()->json(['result' => 1, 'msg' => 'Ticket Successfully Added!']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }
    public function get_ticket_info(Request $request){
        DB::beginTransaction();
        try{
            $ticket_data = Ticket::where('id', $request->id)->whereNull('deleted_at')->first();
            DB::commit();
            return response()->json(['result' => 1, 'ticketData' => $ticket_data,'assigned_to' => explode(',',$ticket_data['assigned_to']), 'user_roles' => Auth::user()->roles]);
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
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
            DB::beginTransaction();

            $value = $request->all();
            $value_resolution_list = $value['inputCount']['key_num'];
            $procedure_title_id = $request->procedure_title_id;
            if($procedure_title_id != null || isset( $procedure_title_id ) ){
                // return 'true';
                ResolutionProcedureTitle::where('id',$procedure_title_id)->update([
                    'updated_by' => 1,
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
                DB::commit();
                return (['is_success'=>'true']);
            }else{
                // return 'false';
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
                $resolution_procedure_lists = ResolutionProcedureTitle::with('resolutionProcedureLists')
                                            ->where('id',$resolution_procedure_title_id)
                                            ->limit(5)->get();
                DB::commit();
                // DB::rollback();
                return (['is_success'=>'true','resolution_procedure_title_id' => $resolution_procedure_title_id,'resolution_procedure_lists' => $resolution_procedure_lists]);
            }

        }catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getAssignedTickets(){
        return Ticket::where('assigned_to',Auth::user()->id)->whereNull('deleted_at')->get();
    }

    public function sendEmail(){
        return 'success';
    }
}
