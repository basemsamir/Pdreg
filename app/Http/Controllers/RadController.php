<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Visit;
use DB;

class RadController extends Controller
{
    //
    private $title = "أشعة تذكرة مريض";
    private $default_dic = "rad_views";

    public function index(Request $request){

        return redirect('rad/patient_rads');
    }

    public function search_patient_rads_view()
    {
        # code...
        $title = $this->title;
        return view($this->default_dic.'.patient_rads',compact('title'));
    }

    public function search_patient_rads_results(Request $request)
    {
        # code...
        $input=$request->all();
        $messages = [
        'ticket_number.required' => 'رقم التذكرة مطلوب ادخاله.',
        ];
        $this->validate($request, [
            'ticket_number' => 'required',
        ],$messages);

        $title = $this->title;
        $data = $this->_getTicketRads($input['ticket_number']);
        return view($this->default_dic.'.patient_rads',compact('title','data'));
    }

    private function _getTicketRads($ticket_number)
    {
        # code...
        return Visit::join('medical_order_items','visits.id','=','medical_order_items.visit_id')
                    ->join('patients','visits.patient_id','=','patients.id')
                        ->where('ticket_number',$ticket_number)
                        ->groupBy('visits.id')
                        ->select(DB::raw("visits.patient_id, visits.ticket_number,
                        GROUP_CONCAT(proc_name separator '<br>') as proc_name, name"))
                        ->get();
    }
}
