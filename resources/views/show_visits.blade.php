@extends('layouts.app')
@section('title')
@if($role_name == 'Entrypoint')
	زيارات مكتب الدخول
@elseif(isset($ticket_and_entry))
	بيانات مرضي الدخول
@elseif($role_name == 'Desk')
	زيارات مكتب الاستقبال
@else
	زيارات مكتب حجز التذاكر
@endif
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">
				@if($role_name == 'Entrypoint')
					زيارات مكتب الدخول
				@elseif(isset($ticket_and_entry))
					بيانات مرضي الدخول
				@elseif($role_name == 'Desk')
					زيارات مكتب الاستقبال
				@else
					زيارات مكتب حجز التذاكر
				@endif

		</li>
      </ol>
	  <h1>
			@if($role_name == 'Entrypoint')
				زيارات مكتب الدخول
			@elseif(isset($ticket_and_entry))
				بيانات مرضي الدخول
			@elseif($role_name == 'Desk')
				زيارات مكتب الاستقبال
			@else
				زيارات مكتب حجز التذاكر
			@endif

      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-24">
          <!-- small box -->
			  <div class="box box-primary" dir="rtl">
				<div class="box-header with-border">
				  <h3 class="box-title">{{$table_header}}</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					@if(Session::has('error'))
					<div class="alert alert-danger" >
						<b>{{ Session::get('error') }}</b>
					</div>
					@endif
					@if(Session::has('success'))
						<div class="alert alert-success">
							<b>{{ Session::get('success') }}</b>
						</div>
					@endif
					<div class="row">
						<div class="col-lg-12">
							<table id="visits_datatable" class="table table-bordered table-hover">
								<thead>
								<tr>
									@if($role_name != 'Entrypoint')
									 <th style="text-align:center">رقم التذكرة</th>
									@endif
									@if($role_name == 'Desk')
									 <th style="text-align:center">نوع التذكرة</th>
									@endif
								  <th style="text-align:center">كود المريض</th>
								  <th style="text-align:center">أسم المريض</th>
								  <th style="text-align:center">{{ $role_name  == 'Entrypoint' || isset($ticket_and_entry) ?'أسم القسم':'أسم العيادة' }}</th>
								  @if($role_name  == 'Entrypoint' ||  isset($ticket_and_entry))
									<th style="text-align:center">ساعة دخول المريض</th>
									<th style="text-align:center">تاريخ الدخول</th>
								  @else
									<th style="text-align:center">تاريخ الكشف</th>
								  @endif
								  @if($role_name  == 'Entrypoint' || isset($ticket_and_entry))

									<th style="text-align:center">تاريخ الخروج</th>
									@if($role_name  != 'Receiption')
									<th style="text-align:center">رقم الغرفة</th>
									<th style="text-align:center">رقم الملف</th>
									<th style="text-align:center">نوع العلاج</th>
									<th style="text-align:center">جهة التعاقد</th>
									  @if($role_name  != 'Receiption')
										<th style="text-align:center">طباعة</th>
										@if(is_null($sub_type_entrypoint) || ($sub_type_entrypoint == "entry_only" || $sub_type_entrypoint == "entry_and_exit"))
										<th style="text-align:center">تحديث ملف المريض</th>
										@endif
										@if(!is_null($sub_type_entrypoint) && ($sub_type_entrypoint == "exit_only" || $sub_type_entrypoint == "entry_and_exit"))
										<th style="text-align:center">تسجيل خروج</th>
										@endif
									  @endif
									@endif
								  @else
									<th style="text-align:center">تسجيل كشف جديد</th>
								  @if($role_name  == "Receiption" )
									<th style="text-align:center">التحويل الي مكتب الاستقبال</th>
							      @endif
									<th style="text-align:center">ألغاء الحجز</th>
								  @endif
								</tr>
								</thead>
								<tbody>
								<?php $i=0; ?>
								@foreach($data as $row)
								<tr id="row{{$i}}">
									@if($role_name  != 'Entrypoint' )
										 <td>{{$row->ticket_number}}</td>
									@endif
									@if($role_name  == 'Desk' )
										 <td>{{$row->ticket_type!=null?($row->ticket_type=="G"?'استقبال عام':'استقبال اصابات'):""}}</td>
									@endif
								  <td>{{$row->id}}</td>
								  <td>{{$row->name}}</td>
								  <td>{{$row->dept_name}}</td>
									@if($role_name  == 'Entrypoint' ||  isset($ticket_and_entry))
										<td>
												@if (strrpos($row->entry_time,"AM") !== false)
														{{ str_replace('AM', 'ص', $row->entry_time) }}
												@elseif (strrpos($row->entry_time,"PM") !== false)
														{{ str_replace('PM', 'م', $row->entry_time) }}
												@endif
										</td>
										<td>{{$row->entry_date}}</td>
									@elseif($role_name=="Desk")
										<td>{{$row->registration_datetime}}</td>
									@else
										<td>{{$row->created_at}}</td>
									@endif
									@if($role_name  == 'Entrypoint' ||  isset($ticket_and_entry) )

									 <td>{{$row->ed}}</td>
									 @if($role_name  != 'Receiption')
									 <td>{{$row->room_number}}</td>
									 <td>{{$row->file_number}}</td>
									 <td>{{$row->cure_type_name}}</td>
									 <td>{{$row->contract}}</td>
										@if($role_name  != 'Receiption')
										 <td style="text-align:center">
											<div class="btn-group">
											<a class="btn btn-info dropdown-toggle" title="طباعة" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="caret"></span> <i class="fa fa-print"></i> </a>
											 <ul class="dropdown-menu">
												@if(is_null($sub_type_entrypoint) || ($sub_type_entrypoint == "entry_only" || $sub_type_entrypoint == "entry_and_exit"))
													<li><a href='{{ url("visits/showinpatient_file/{$row->visit_id}") }}'  target="_blank" >بيانات الملف</a></li>
													<li><a href='{{ url("printpatientdata/$row->id&$row->visit_id") }}'  target="_blank" >اذن الدخول</a></li>
												@endif
												@if(!is_null($sub_type_entrypoint) && $sub_type_entrypoint == "entry_and_exit")
													<li><a href='{{ url("visits/showinpatient_diagnoses/{$row->visit_id}") }}'  target="_blank" >ملف التشخيص</a></li>
												@endif
												@if(!is_null($sub_type_entrypoint) && ($sub_type_entrypoint == "exit_only" || $sub_type_entrypoint == "entry_and_exit"))
													@if($row->closed == true)
													<li><a href='{{ url("patients/exit_visit_report/{$row->visit_id}") }}' target="_blank">الخروج</a></li>
													@endif
												@endif
											  </ul>
											 </div>
										 </td>
										<!-- <td>@if($row->closed == true) {{$row->updated_at}} @endif</td> -->
											@if(is_null($sub_type_entrypoint) || ($sub_type_entrypoint == "entry_only" || $sub_type_entrypoint == "entry_and_exit"))

											 <td style="text-align:center">
												<a href='{{ url("/patients/visits/$row->id&$row->visit_id") }}'
												@if($row->closed == true && $sub_type_entrypoint == "entry_only"))
													class="btn btn-success disabled" title="تحديث ملف المريض"
												@else
													class="btn btn-success" title="تحديث ملف المريض"
												@endif
												><i class="fa fa-edit"></i></a>
											 </td>
											 @endif
											 @if(!is_null($sub_type_entrypoint) && ($sub_type_entrypoint == "exit_only" || $sub_type_entrypoint == "entry_and_exit"))
											 <td style="text-align:center">
												<a href='{{ url("patients/visit_exit/$row->id&$row->visit_id") }}'
												@if($row->closed == true && $sub_type_entrypoint == "exit_only")
													class="btn btn-danger disabled" title="تسجيل خروج"
												@else
													class="btn btn-danger" title="تسجيل خروج"
												@endif
												>
													<i class="fa fa-sign-out"></i>
												</a>
											 </td>
											 @endif
										@endif
									  @endif
									@else
									<td style="text-align:center">
										<a href='@if($role_name  == "Desk" ) {{ url("/patients/desk/$row->id") }} @else {{ url("/patients/reserve/$row->id") }} @endif' title="تسجيل كشف جديد" class="btn btn-success"><i class="fa fa-plus"></i></a></td>
									</td>
									@if($role_name  == "Receiption" )
									<td style="text-align:center">
										<div class="btn-group">
											<button class="btn btn-info dropdown-toggle" @if($row->closed == true ) disabled title='تم أنهاء الزيارة'
											@elseif($row->created_at->format('Y-m-d') < date('Y-m-d',time())) disabled title='تاريخ الزيارة قد سبق'
											@elseif($row->convert_to_entry_id) disabled title="تم التحويل الي مكتب استقبال" @else title="التحويل الي مكتب الاستقبال" @endif data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="caret"></span> <i class="fa fa fa-arrow-left"></i> </button>
											 <ul class="dropdown-menu">
												@foreach($desks as $desk)
													<li><a href='{{ url("patients/convertvisit/$desk->id&$row->visit_id") }}'
														onclick="if(confirm('هل تريد تحويل المريض الي مكتب الاستقبال ؟')){return true;}else{return false;}"
														>{{$desk->name}}</a></li>
												@endforeach
											  </ul>
										 </div>
								    </td>
									@endif
									<td style="text-align:center">
										<a href='{{ url("/patients/cancelvisit/$row->visit_id") }}'
										@if($row->closed == true )
											 title="تم أنهاء الزيارة"
										@elseif($row->created_at->format('Y-m-d') < date('Y-m-d',time()))
										  title="تاريخ الزيارة قد سبق"
										@elseif($row->convert_to_entry_id && $role_name != "Desk" )
										  title="تم التحويل الي مكتب استقبال"
										@else
											title="الغاء الحجز"
										@endif
										onclick="if(confirm('هل تريد ألغاء هذا الحجز ؟ ')){return true;}else{return false;}"
										class="btn
										@if($row->closed == true )
											disabled
										@elseif($row->convert_to_entry_id && $role_name != "Desk")
											disabled
										@elseif($row->created_at->format('Y-m-d') < date('Y-m-d',time()))
											disabled
										@endif btn-danger" ><i class="fa fa-close"></i></a></td>
									</td>
									@endif
								</tr>
								<?php $i++;?>
								@endforeach
								</tbody>
							</table>
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
