$(document).ready(function(){
	
	date_selection_el = $("#date_selection");
	enableDatepickers(date_selection_el);
	$("#datepicker").val("");
	$("#datepicker2").val("");
	date_selection_el.change(function(){
		enableDatepickers($(this));
	});
});

function enableDatepickers(date_selection_el){
	if(date_selection_el.val() == "date_selected"){
		$("#datepicker").removeAttr('disabled');
		$("#datepicker2").removeAttr('disabled');
	}
	else{
		$("#datepicker").attr('disabled',true);
		$("#datepicker2").attr('disabled',true);
	}
}
