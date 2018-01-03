<?php
use App\MedicalUnit;
use Carbon\Carbon;
function updateSeenAttrForSeenVisits($medical_visits){
	//dd($medical_visits);
	foreach($medical_visits as $row){
		MedicalUnit::find($row->dep_id)
				   ->visits()
				   ->where('seen',false)
				   ->updateExistingPivot($row->visit_id,array('seen'=>true));
	}

}
function calculateAge($birthdate){
	Carbon::setLocale('ar');
	$current_date = Carbon::today();
	$birthdate = Carbon::parse($birthdate);
	return $current_date->diffForHumans($birthdate,true);
}
function return_birthdate(){
	if(func_num_args()>1){
		$birthdate=strtotime("-".(func_get_arg(0)==""?0:func_get_arg(0))." day",time());
		$birthdate=strtotime("-".(func_get_arg(1)==""?0:func_get_arg(1))." month",$birthdate);
		$birthdate=strtotime("-".(func_get_arg(2)==""?0:func_get_arg(2))." year",$birthdate);
		return date('Y-m-d',$birthdate);
	}
	if(func_get_arg(0)[0] == 2)
		$prifx_year="19";
	else if(func_get_arg(0)[0] == 3)
		$prifx_year="20";
	else if(func_get_arg(0)[0] == 4)
		$prifx_year="21";

	$year=$prifx_year."".func_get_arg(0)[1]."".func_get_arg(0)[2];
	$month=func_get_arg(0)[3]."".func_get_arg(0)[4];
	$day=func_get_arg(0)[5]."".func_get_arg(0)[6];
	return $year."-".$month."-".$day;
}
