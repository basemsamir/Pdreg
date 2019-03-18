<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Visit;
use DB;

class PharmController extends Controller
{
    //
    public function index(Request $request){

        return redirect('pharm/patient_drugs');
    }

    public function search_patient_drugs_view()
    {
        # code...
        $title = "أدوية تذكرة مريض";
        return view('pharm_views.patient_drugs',compact('title'));
    }

    public function search_patient_drugs_results(Request $request)
    {
        # code...
        $input=$request->all();
        $messages = [
            'ticket_number.required' => 'رقم التذكرة مطلوب ادخاله.',
        ];
        $this->validate($request, [
            'ticket_number' => 'required',
        ],$messages);

        $title = "أدوية تذكرة مريض";
        $data = $this->_getTicketDrugs($input['ticket_number']);
        return view('pharm_views.patient_drugs',compact('title','data'));
    }

    private function _getTicketDrugs($ticket_number)
    {
        # code...
        return Visit::join('visit_medicines','visits.id','=','visit_medicines.visit_id')
                    ->join('patients','visits.patient_id','=','patients.id')
                    ->where('ticket_number',$ticket_number)
                    ->groupBy('visits.id')
                    ->select(DB::raw("visits.patient_id, visits.ticket_number,
                    ( select GROUP_CONCAT(name separator '<br>') as name from visit_medicines  where visit_medicines.visit_id=visits.id
                    ) as medicines, patients.name"))
                    ->get();
    }

}
