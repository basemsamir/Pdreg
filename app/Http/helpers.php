<?php
use App\MedicalUnit;

function updateSeenAttrForSeenVisits($medical_visits){
	//dd($medical_visits);
	foreach($medical_visits as $row){
		MedicalUnit::find($row->dep_id)
				   ->visits()
				   ->where('seen',false)
				   ->updateExistingPivot($row->visit_id,array('seen'=>true));
	}

}
