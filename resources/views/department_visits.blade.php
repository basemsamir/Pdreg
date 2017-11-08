@extends('layouts.app')
@section('title')
{{$medicalunit['type']=='c'? 'زيارات عيادة': 'مرضي قسم'}} {{ $medicalunit['name'] }} {{$medicalunit['type']=='c'? 'اليوم': 'الداخلي'}}
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">{{$medicalunit['type']=='c'?'عيادة':'قسم'}} {{$medicalunit['name'] }}</li>
      </ol>
	  <h1>
        {{$medicalunit['type']=='c'? 'زيارات عيادة': 'مرضي قسم'}} {{ $medicalunit['name'] }} {{$medicalunit['type']=='c'? 'اليوم': 'الداخلي'}}
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  <div id="overlay"></div>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-24">
          <!-- small box -->
			  <div class="box box-primary" dir="rtl">
				<div class="box-header with-border">
				  <h3 class="box-title"></h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					@if($errors->any())
					<div class="alert alert-danger">
						@foreach($errors->all() as $error)
							<p><b>{{ $error }}</b></p>
						@endforeach
					</div>
					@endif
					@if(Session::has('flash_message'))
						@if(Session::has('message_type'))
							<div class="alert alert-danger">
						@else
							<div class="alert alert-success">
						@endif
							<b>{{ Session::get('flash_message') }}</b>
						</div>
					@endif
					<div class="alert" id="successMessages" style="display: none"></div>
					<div class="row">
						<div class="col-lg-5">
							<div id="overlay2"></div>
							<table id="p_datatable" class="table table-bordered table-hover">
								<thead>
								<tr>
								  <th style="text-align:center">كود المريض</th>
								  <th style="text-align:center">أسم المريض</th>
								  <th style="text-align:center">تحديد</th>
								  <th style="text-align:center">سجل المريض</th>
								</tr>
								</thead>
								<tbody>
								<?php $i=0;?>
								@foreach($visits as $row)
								<tr id="row{{$i}}" >
								  <td>{{$row->id}}</td>
								  <td>{{$row->name}}</td>
								  <td><a nohref onclick="showVisitData({{$i++}},{{$row->visit_id}});" class="btn btn-primary"><i class="fa fa-plus"></i></a></td>
								  <td><a href='{{ url("/visits/printhistory/$row->id") }}'  target="_blank" class="btn btn-info"><i class="fa fa-print"></i></a></td>

								</tr>
								@endforeach
								</tbody>
							</table>
						</div> <!-- ./col-lg-4 -->

						<div class="col-lg-7">
							<ul class="nav nav-tabs" style="padding-right:0">

							  <li class="active pull-right"><a data-toggle="tab" href="#home">
							  @if($medicalunit['type']=='c') كشف مريض @else زيارة طبيب للمريض @endif</a></li>
							  @if($medicalunit['type']=='c')
							  <li class="pull-right"><a data-toggle="tab" href="#medicine">الأدوية</a></li>
							  @endif
							  <!--<li class="pull-right" ><a data-toggle="tab" href="#history">سجل المريض</a></li> -->
							  <li class="pull-right" style="display: none" ><a data-toggle="tab" href="#xrays">الأشعة</a></li>
							  @if($medicalunit['type']=='c')
								<li class="pull-right"><a data-toggle="tab" href="#conversion_clinic">التحويل الي عيادة أخري</a></li>
								<li class="pull-right"><a data-toggle="tab" href="#conversion_department">التحويل الي القسم الداخلي</a></li>
							  @endif
							  @if($medicalunit['type']=='d')
								<li class="pull-right"><a data-toggle="tab" href="#doctor_recommendation">التوصية</a></li>
							  @endif
							</ul>

							<div class="tab-content">
							  <div id="home" class="tab-pane fade in active">
							  {!! Form::open(array('class'=>'form','method' => 'POST','id'=>'patient_form_id','name'=>'patient_form')) !!}
								<div class="form-group" >
									<div class="row">
										<div class="col-lg-6">
											{!! Form::label('الكود',null) !!}
											{!! Form::text('name',NULL,array('class'=>'form-control name','readonly'=>'readonly')) !!}
										</div>
										<div class="col-lg-6">
											{!! Form::label('الأسم',null) !!}
											{!! Form::text('code',NULL,array('class'=>'form-control code','readonly'=>'readonly')) !!}
										</div>
									</div>
								</div>
								<div class="form-group" >
									{!! Form::label('الشكوى',null) !!}
									{!! Form::text('complaints',NULL,array('id'=>'complaints','class'=>'form-control')) !!}
								</div>
								<div class="form-group" >
								@if($medicalunit['type']=='c')
									{!! Form::label('التشخيص',null) !!}
								@else
									{!! Form::label('التشخيص ( عربي )',null) !!}
								@endif
									{!! Form::text('diagnoses',NULL,array('id'=>'diagnoses','class'=>'form-control')) !!}
									{!! Form::hidden('dep',$dep,array('id'=>'dep_id')) !!}
									{!! Form::hidden('visit',null,array('id'=>'visit')) !!}
									{!! Form::hidden('status',null,array('id'=>'status')) !!}
									{!! Form::hidden('formID',1) !!}
								</div>
								@if($medicalunit['type']=='d')
								<div class="form-group" >
									{!! Form::label('التشخيص ( أنجليزي )',null) !!}
									{!! Form::text('diagnoses_english',NULL,array('id'=>'diagnoses_english','class'=>'form-control','style'=>'direction:ltr')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('العلاج',null) !!}
									{!! Form::textarea('cure_description',NULL,array('rows'=>'3','id'=>'cure_description','class'=>'form-control','style'=>'direction:ltr')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('المستلزمات',null) !!}
									{!! Form::textarea('accessories',NULL,array('rows'=>'3','id'=>'accessories','class'=>'form-control')) !!}
								</div>
								@endif
								<div class="row">
									<div class="col-lg-12">
										<table id="example" class="table table-condensed complaints-table">
											<thead style="background-color: darkgrey">
											<tr>
											  <th style="text-align:center">الشكوى</th>
											  <th style="text-align:center">تاريخ التسجيل</th>
											  <th style="text-align:center">أسم المسجل</th>
											</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
										<table id="example" class="table table-condensed diagnose-table">
											<thead style="background-color: darkgrey">
											<tr>
											@if($medicalunit['type']=='c')
											  <th style="text-align:center">التشخيص </th>
											@else
											  <th style="text-align:center">التشخيص ( عربي )</th>
											  <th style="text-align:center">التشخيص ( أنجليزي )</th>
											@endif
											  <th style="text-align:center">تاريخ التسجيل</th>
											  <th style="text-align:center">أسم المسجل</th>
											</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
										@if($medicalunit['type'] == 'd')
										<table class="table table-condensed cure-table">
											<thead style="background-color: darkgrey">
											<tr>
												<th style="text-align:center">العلاج</th>
												<th style="text-align:center">المستلزمات</th>
                        <th style="text-align:center">تاريخ التسجيل</th>
                        <th style="text-align:center">أسم المسجل</th>
											</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
										@endif
									</div>
								</div>
								<a href="#" id="submitOnly" class="btn btn-primary">@if($medical_type == "c")تسجيل التشخيص أو الشكوى فقط @else تسجيل الشكوى أو التشخيص و العلاج@endif</a>
								@if($medical_type == "c")
									<a href="#" id="submitFinish"  class="btn btn-primary" onclick="return changeStatus()" >أنهاء الزيارة</a>
								@endif
							  {!! Form::close(); !!}
							  </div>

							  <div id="history" class="tab-pane fade" >

								<div class="row">
									<br>
									<div class="col-lg-12">
										<table id="example3" class="table table-striped table-bordered visits-table">
											<thead >
											<tr>
											  <th style="text-align:center">تاريخ  و وقت التسجيل</th>
											  <th style="text-align:center">اسم العيادة / اسم القسم</th>
											  <th style="text-align:center">الشكوى</th>
											  <th style="text-align:center">التشخيص</th>
											  <!-- <th style="text-align:center">صور الأشعة</th> -->
											  <th style="text-align:center">الأدوية</th>
											</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							   </div>
							  <div id="medicine" class="tab-pane fade">
								{!! Form::open(array('class'=>'form','method' => 'POST','id'=>'medicine_form')) !!}
								<div class="form-group" >
									<div class="row">
										<div class="col-lg-6">
											{!! Form::label('الكود',null) !!}
											{!! Form::text('name',NULL,array('class'=>'form-control name','readonly'=>'readonly')) !!}
										</div>
										<div class="col-lg-6">
											{!! Form::label('الأسم',null) !!}
											{!! Form::text('code',NULL,array('class'=>'form-control code','readonly'=>'readonly')) !!}
										</div>
									</div>
								</div>
								<div class="form-group" >
									{!! Form::label('الدواء',null) !!}
									{!! Form::text('medicines',NULL,array('id'=>'medicines','class'=>'form-control')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('المستلزمات',null) !!}
									{!! Form::text('accessories',NULL,array('id'=>'accessories','class'=>'form-control')) !!}

									{!! Form::hidden('dep',$dep,array('id'=>'dep_id')) !!}
									{!! Form::hidden('visit',null,array('id'=>'mvisit')) !!}
									{!! Form::hidden('formID',5) !!}
								</div>
								<div class="row">
									<div class="col-lg-12">
										<table id="example" class="table table-condensed medicine-table">
											<thead style="background-color: darkgrey">
											<tr>
											  <th style="text-align:center">أسم الدواء</th>
											  <th style="text-align:center">المستلزمات</th>
											  <th style="text-align:center">تاريخ التسجيل</th>
											  <th style="text-align:center">أسم المسجل</th>
											</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<a id="submitMedicine" class="btn btn-primary" onclick=" $('#overlay').show();">تسجيل الدواء</a>
							  {!! Form::close(); !!}
							  </div>
							  <div id="xrays" class="tab-pane fade">
								{!! Form::open(array('class'=>'form','method' => 'POST','name'=>'patient_form','onsubmit'=>
												'$("#submitXray").attr("disabled",true)')) !!}
								<div class="form-group" >
									<div class="row">
										<div class="col-lg-6">
											{!! Form::label('الكود',null) !!}
											{!! Form::text('name',NULL,array('class'=>'form-control name','readonly'=>'readonly')) !!}
										</div>
										<div class="col-lg-6">
											{!! Form::label('الأسم',null) !!}
											{!! Form::text('code',NULL,array('class'=>'form-control code','readonly'=>'readonly')) !!}
										</div>
									</div>
								</div>
								<div class="form-group" >
									{!! Form::label('أسم الجهاز',null) !!}
									{!! Form::select('device',$devices,null,['id'=>'device','class' => 'form-control']); !!}
								</div>
								<div class="form-group" >
									{!! Form::label('نوع الفحص',null) !!}
									{!! Form::select('procedure',$proc,null,['id'=>'procedure_name','class'=>'form-control']) !!}
									{!! Form::hidden('dep',$dep,array('id'=>'dep_id')) !!}
									{!! Form::hidden('visit',null,array('id'=>'xvisit')) !!}
									{!! Form::hidden('formID',2) !!}
								</div>
								<div class="row">
									<div class="col-lg-12">
										<table id="example" class="table table-condensed radiology-table">
											<thead style="background-color: darkgrey">
											<tr>
											  <th style="text-align:center">#</th>
											  <th style="text-align:center">أسم الجهاز</th>
											  <th style="text-align:center">نوع الفحص</th>
											  <th style="text-align:center">أسم المسجل</th>
											</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<button type="submit" id="submitXray" class="btn btn-primary" onclick=" $('#overlay').show();">طلب أشعة</button>
							  {!! Form::close(); !!}
							  </div>


							  <div id="conversion_clinic" class="tab-pane fade">
								{!! Form::open(array('class'=>'form','method' => 'POST','name'=>'patient_form','onsubmit'=>
												'$("#submitConversionClinic").attr("disabled",true)')) !!}
								<div class="form-group" >
									{!! Form::label('الكود',null) !!}
									{!! Form::text('name',NULL,array('class'=>'form-control name','readonly'=>'readonly')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('الأسم',null) !!}
									{!! Form::text('code',NULL,array('class'=>'form-control code','readonly'=>'readonly')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('العيادة',null) !!}
									{!! Form::select('clinic',$clinics,null,['id'=>'clinic','class' => 'form-control']); !!}
									{!! Form::hidden('visit',null,array('id'=>'cvisit')) !!}
									{!! Form::hidden('formID',3) !!}
								</div>
								<button type="submit" id="submitConversionClinic" class="btn btn-primary">تحويل</button>
								{!! Form::close(); !!}
							  </div>

							  <div id="conversion_department" class="tab-pane fade">
								{!! Form::open(array('class'=>'form','method' => 'POST','name'=>'patient_form','onsubmit'=>
												'$("#submitConversionDept").attr("disabled",true)')) !!}
								<div class="form-group" >
									{!! Form::label('الكود',null) !!}
									{!! Form::text('name',NULL,array('class'=>'form-control name','readonly'=>'readonly')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('الأسم',null) !!}
									{!! Form::text('code',NULL,array('class'=>'form-control code','readonly'=>'readonly')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('القسم',null) !!}
									{!! Form::select('department',$departments,null,['id'=>'department','class' => 'form-control']); !!}
									{!! Form::hidden('visit',null,array('id'=>'dvisit')) !!}
									{!! Form::hidden('formID',4) !!}
								</div>
								<button type="submit" id="submitConversionDept" class="btn btn-primary">تحويل</button>
								{!! Form::close(); !!}
							  </div>
							  @if($medicalunit['type'] == 'd')
							  <div id="doctor_recommendation" class="tab-pane">
								{!! Form::open(array('class'=>'form','method' => 'POST','id'=>'recommendation_form')) !!}
								<div class="form-group" >
									{!! Form::label('الكود',null) !!}
									{!! Form::text('name',NULL,array('class'=>'form-control name','readonly'=>'readonly')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('الأسم',null) !!}
									{!! Form::text('code',NULL,array('class'=>'form-control code','readonly'=>'readonly')) !!}
								</div>
								<div class="form-group" >
									{!! Form::label('التوصية',null) !!}
									{!! Form::textarea('dr_recommendation',NULL,array('rows'=>'3','id'=>'dr_recommendation','class'=>'form-control')) !!}
									{!! Form::hidden('visit',null,array('id'=>'dvisit')) !!}
									{!! Form::hidden('formID',6) !!}
								</div>
								<a href="#" id="submitRecommendation" class="btn btn-primary">تسجيل</a>
								{!! Form::close(); !!}
							  </div>
							  @endif
							</div>
						</div> <!-- ./col-lg-12 -->
					</div> <!-- ./row -->
			  </div>
            <!-- ./box -->
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

@endsection
@section('javascript')
  <script>
	var global_row_id;
	table=$('#p_datatable').DataTable({
	  "bSort": false,
	  "paging": true,
	  "lengthChange": true,
	  "searching": true,
	  "info": false,
	  "autoWidth": false,
	  "aaSorting": [[ 0, "desc" ]],
	  "columns": [
		null,null,null,
		{ className: "dt-center" },
	  ]
	});
	var seenAjax=false;
	$("#submitOnly").click(function(){
		if($("#visit").val() == "" ){
			alert("من فضلك اختر المريض");
			return false;
		}
		var url = "{{ url('visits/1') }}";

		$.ajax({
			url:url,
			type:'post',
			data:$('#patient_form_id').serialize(),
			dataType: "json",
			success: function(data){
				if(data['success'] == "true"){
					$("#successMessages").text("");
					$("#successMessages").removeClass('alert-danger');
					$("#successMessages").addClass('alert-success');
					$("#successMessages").html("<b>"+data['messages']+"</b>");
					$("#successMessages").show();
					$("#diagnoses").val("");
					$("#complaints").val("");
					$("#diagnoses_english").val("");
					$("#cure_description").val("");
					$("#accessories").val("");
					restorePatientData(global_row_id);
				}
				else{
					$("#successMessages").text("");
					$("#successMessages").removeClass('alert-success');
					$("#successMessages").addClass('alert-danger');
					for(error in data['messages'])
						$("#successMessages").append("<p><b>"+data['messages'][error]+"</b></p>");
					$("#successMessages").show();
				}
			}
		});
	});
	$("#submitMedicine").click(function(){
		var url = "{{ url('visits/1') }}";

		$.ajax({
			url:url,
			type:'post',
			data:$('#medicine_form').serialize(),
			dataType: "json",
			success: function(data){
				if(data['success'] == "true")
				{
					$("#successMessages").removeClass('alert-danger');
					$("#successMessages").show();
					$("#successMessages").addClass('alert-success');
					$("#successMessages").html("<b>"+data['messages']+"</b>");
          $("#medicines").val("");
					$("#accessories").val("");
					restorePatientData(global_row_id);
				}
				else{
					$("#successMessages").removeClass('alert-success');
					$("#successMessages").show();
					$("#successMessages").addClass('alert-danger');
					for(error in data['messages'])
						$("#successMessages").append("<p><b>"+data['messages'][error]+"</b></p>");
				}
			}
		});
	});
	$("#submitRecommendation").click(function(){
		var url = "{{ url('visits/1') }}";

		$.ajax({
			url:url,
			type:'post',
			data:$('#recommendation_form').serialize(),
			dataType: "json",
			success: function(data){
				if(data['success'] == "true")
				{
					$("#successMessages").removeClass('alert-danger');
					$("#successMessages").show();
					$("#successMessages").addClass('alert-success');
					$("#successMessages").html("<b>"+data['messages']+"</b>");
					restorePatientData(global_row_id);
				}
				else{
					$("#successMessages").removeClass('alert-success');
					$("#successMessages").show();
					$("#successMessages").addClass('alert-danger');
					for(error in data['messages'])
						$("#successMessages").append("<p><b>"+data['messages'][error]+"</b></p>");
				}
			}
		});
	});
   </script>
   <script>
    $(document).ajaxStart(function(){
		if(seenAjax == false)
			$("#overlay").show();
	});
	$(document).ajaxComplete(function(){
		if(seenAjax == false)
			$("#overlay").hide();
	});
	$(document).ready(function(){

		var availableDiagnoses = [];
		var availableComplaints = [];
		var availableMedicines = [];
		//Get all diagnoses
		var url = "{{ url('visits/getAllDiagnoses') }}";
		$.ajax({
			type: "GET",
			dataType: "json",
			url: url,
			success: function (data) {
				for(i=0;i<data['data'].length;i++)
				{
					availableDiagnoses[i]=data['data'][i].content;
				}
				$( "#diagnoses" ).autocomplete({
				  source: availableDiagnoses
				});
			},
			error: function (data) {
				alert("Error");
			}
		});
		//Get all complaints
		var url = "{{ url('visits/getAllComplaints') }}";
		$.ajax({
			type: "GET",
			url: url,
			dataType: 'json',
			success: function (data) {
				for(i=0;i<data['data'].length;i++)
				{
					availableComplaints[i]=data['data'][i].content;
				}
				$( "#complaints" ).autocomplete({
				  source: availableComplaints
				});

			},
			error: function (data) {
				alert("Error");
			}
		});
		//Get all medicines
		var url = "{{ url('visits/getAllMedicines') }}";
		$.ajax({
			type: "GET",
			url: url,
			dataType: 'json',
			success: function (data) {
				for(i=0;i<data['data'].length;i++)
				{
					availableMedicines[i]=data['data'][i].name;
				}
				$( "#medicines" ).autocomplete({
				  source: availableMedicines
				});

			},
			error: function (data) {
				alert("Error");
			}
		});
		$('#device').change(function(){
			if($('#device').val() != ""){
				var url = "/visits/getProcedures/"+$('#device').val();

				$.ajax({
					type: "GET",
					url: url,
					success: function (data) {
						$("#procedure_name option").remove();
						if(data['success']=='true'){
							for(i=0;i<data['procedures'].length;i++)
							{
								$('#procedure_name').append($('<option>', {value:data['procedures'][i].id, text:data['procedures'][i].name}));
							}
						}
					},
					error: function (data) {
						alert("Error");
					}
				});

			}
		});
	});

</script>

@if($medical_type == 'd')
<script>

	$("#diagnoses").keypress(function(evt){

		var VAL = $(this).val();
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode!=8 ){ //if the key isn't the backspace key (which we should allow)
		if (( charCode<48 || charCode>57) && (charCode < 0x0600 || charCode > 0x06FF) && charCode!=32) //if not a number or arabic
				return false //disable key press
		}


	});

	$("#diagnoses_english").keypress(function(evt){

		var VAL = $(this).val();
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode!=8 ){ //if the key isn't the backspace key (which we should allow)
		if (( charCode<48 || charCode>57) && (charCode < 97 || charCode > 122) && (charCode < 65 || charCode > 90) && charCode!=32) //if not a number or arabic
			return false //disable key press
		}


	});
	$("#cure_description").keypress(function(evt){


		var VAL = $(this).val();
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
    
		if (charCode!=8 ){ //if the key isn't the backspace key (which we should allow)
		if (( charCode<44 || charCode>45 ) && ( charCode<48 || charCode>57) && (charCode < 97 || charCode > 122) && (charCode < 65 || charCode > 90) && charCode!=32  )  //if not a number or arabic
			return false //disable key press
		}


	});
</script>
@endif
@if($medical_type == "false")
<script>


	setInterval(function(){
		var url = "{{ url('/visits/getNewVisits/') }}";
		seenAjax=true;
		$.ajax({
			type: "POST",
			url: url,
			data: { 'clinic_id': $("#dep_id").val() },
			success: function (data) {
				if(data['success']=='true'){
					for(i=0;i<data['visits'].length;i++)
					{
						last_row_id_string=$("#p_datatable tr:last").attr('id');
						last_id=0;
						if(typeof(last_row_id_string) != "undefined"){
							last_id=last_row_id_string.substring(3);
							last_id=last_id+1;
						}
						else
							last_id=1;
						row="<a href='#' onclick='showVisitData("+last_id+","+data['visits'][i].visit_id+");' class='btn btn-info'><i class='fa fa-plus'></i> تحديد </a>";
						l=table.row.add([data['visits'][i].id,data['visits'][i].name,row]).draw(false);
						table.rows(l).nodes().to$().attr("id", "row"+last_id);
					}
				}
			},
			error: function (data) {

			}
		});
		seenAjax=false;
	}, 60000);
</script>
@endif
<script>
	function showVisitData(id,vid){
		// Remove Sucess Message
		$('.alert-success').hide();
		$('.alert-danger').hide();
		$("#successMessages").hide();
		$(".name").val($("#row"+id+" td:eq(0)").text());
		$(".code").val($("#row"+id+" td:eq(1)").text());
		$("#visit").val(vid);
		$("#xvisit").val(vid);
		$("#cvisit").val(vid);
		$("#dvisit").val(vid);
		$("#mvisit").val(vid);
		$('.diagnose-table tr:gt(0)').remove();
		$('.complaints-table tr:gt(0)').remove();
		$('.medicine-table tr:gt(0)').remove();
		$('.cure-table tr:gt(0)').remove();
		restorePatientData(id);
		global_row_id=id;

	}

	function restorePatientData(row_id=''){

		var url = "{{ url('/visits/getDiagnoses/') }}";
    $('.diagnose-table tr:gt(0)').remove();
    $('.cure-table tr:gt(0)').remove();
		$.ajax({
			type: "POST",
			url: url,
			data: { 'visit_id': $('#visit').val()},
			success: function (data) {
				if(data['success']=='true'){

					for(i=0;i<data['data'].length;i++)
					{
					<?php if($medical_type == 'c'): ?>
						$('.diagnose-table').append('<tr><td>'+data['data'][i].content+'</td><td>'+data['data'][i].created_at.split(" ")[0]+'</td><td>'+data['data'][i].name+'</td></tr>');
					<?php else: ?>
					if(data['data'][i].cure_description == null)
						data['data'][i].cure_description="";
					if(data['data'][i].content_in_english == null)
						data['data'][i].content_in_english="";
					$('.diagnose-table').append('<tr><td>'+data['data'][i].content+
					'<td>'+data['data'][i].content_in_english+
					'</td><td>'+data['data'][i].created_at.split(" ")[0]+'</td><td>'+data['data'][i].name+'</td></tr>');
					$('.cure-table').append('<tr><td>'+data['data'][i].cure_description+
					'</td><td>'+data['data'][i].accessories+'</td><td>'+data['data'][i].created_at.split(" ")[0]+'</td><td>'+data['data'][i].name+'</td></tr>');
					<?php endif ?>
					}
				}
			},
			error: function (data) {
				alert("Error");
			}
		});
		var url = "{{ url('/visits/getComplaints/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'visit_id': $('#visit').val()},
			success: function (data) {
				if(data['success']=='true'){
					$('.complaints-table tr:gt(0)').remove();
					for(i=0;i<data['data'].length;i++)
					{
						$('.complaints-table').append('<tr><td>'+data['data'][i].content+'</td><td>'+data['data'][i].created_at.split(" ")[0]+'</td><td>'+data['data'][i].name+'</td></tr>');
					}
				}
			},
			error: function (data) {
				alert("Error");
			}
		});
		var url = "{{ url('/visits/getMedicine/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'visit_id': $('#visit').val()},
			success: function (data) {
				if(data['success']=='true'){
					$('.medicine-table tr:gt(0)').remove();
					for(i=0;i<data['data'].length;i++)
					{
            <?php if($medical_type == 'd'):?>
              $('.cure-table').append('<tr><td>'+data['data'][i].name+
              '</td><td>'+data['data'][i].accessories+'</td><td>'+data['data'][i].created_at.split(" ")[0]+'</td><td>'+data['data'][i].username+'</td></tr>');
            <?php else: ?>
              $('.medicine-table').append('<tr><td>'+data['data'][i].name+
              '</td><td>'+data['data'][i].accessories+'</td><td>'+data['data'][i].created_at+'</td><td>'+data['data'][i].username+'</td></tr>');
            <?php endif; ?>
          }
				}
			},
			error: function (data) {
				alert("Error");
			}
		});
		<?php if($medical_type == 'd'):?>
		var url = "{{ url('/visits/getDrRecommendation/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'visit_id': $('#visit').val()},
			success: function (data) {
				if(data['success']=='true'){
					$('#dr_recommendation').val(data['content']);
				}
			},
			error: function (data) {
				alert("Error");
			}
		});
		<?php endif;?>
		// if(row_id !== "")
		// {
			// var url = "{{ url('/visits/getVisits/') }}";
			// $.ajax({
				// type: "POST",
				// url: url,
				// data: { 'patient_id': $("#row"+row_id+" td:eq(0)").text()},
				// success: function (data) {
					// if(data['success']=='true'){

						// var t = $('#example3').DataTable();
						// t.clear().draw();
						// for(i=0;i<data['data'].length;i++)
						// {
							// if(data['data'][i].v_med!=null)
								// cure=data['data'][i].v_med.replace(/,/g,'<br>');
							// if(data['data'][i].v_cure!=null)
								// cure=data['data'][i].v_cure.replace(/,/g,'<br>');
							// t.row.add([data['data'][i].created_at,
							// (data['data'][i].medical_unit.replace(',',' ثم تم التحويل الي ')),
							// (data['data'][i].v_com!=null?data['data'][i].v_com.replace(/,/g,'<br>'):''),
							// (data['data'][i].v_dia!=null?data['data'][i].v_dia.replace(/,/g,'<br>'):''),
							// (cure)]
							// ).draw(false);
							// cure="";
						// }
					// }
				// },
				// error: function (data) {
					// alert("Error");
				// }
			// });
		// }


		// var url = "{{ url('/visits/getRadiology/') }}";
		// $.ajax({
			// type: "POST",
			// url: url,
			// data: { 'visit_id': $('#visit').val()},
			// success: function (data) {
				// if(data['success']=='true'){
					// $('.radiology-table tr:gt(0)').remove();
					// for(i=0;i<data['data'].length;i++)
					// {
						// $('.radiology-table').append('<tr><td>'+(i+1)+'</td><td>'+data['data'][i].dev_name+'</td><td>'+data['data'][i].proc_name+'</td><td>'+data['data'][i].u_name+'</td></tr>');
					// }
				// }
			// },
			// error: function (data) {
				// alert("Error");
			// }
		// });



	}
	function changeStatus(){

		if($("#visit").val() == "" ){
			alert("من فضلك اختر المريض");
			return false;
		}
		if(confirm('هل تريد أنهاء الزيارة؟')){
			$("#status").val('true');
			$("#patient_form_id").submit();
			return true;
		}
		else{

			return false;
		}

	}


  </script>
@endsection
