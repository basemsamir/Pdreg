@extends('layouts.app')
@section('title'){{$title}}@endsection
@section('css')
<style>
	label{
		font-size:16px;
	}
</style>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        {{$title}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">{{$title}}</li>
      </ol>
	    
    </section>

    <!-- Main content -->
    <section class="content" style="direction: rtl">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-24">
						<!-- small box -->
					<div class="box box-primary" >
						<!-- /.box-header -->
						<!-- box body start -->
						<div class="box-body">
							<div class="row">
								
								<div class="col-lg-6" >
									<div class="form-inline">
										@if($visit[0]->ticket_type != null)
											{!! Form::label('أسم القسم :',null) !!}
										@else
											{!! Form::label('أسم العيادة :',null) !!}
										@endif
										{!! Form::label($visit[0]->all_deps?'إستكشاف طارىء':$visit[0]->medicalunits[0]->name) !!}
									</div>
									@if($visit[0]->ticket_type != null)
									<div class="form-inline">
										{!! Form::label('مرسل بمعرفة :',null) !!}
										{!! Form::label($visit[0]->sent_by_person) !!}
									</div>
									<div class="form-inline">
										{!! Form::label(' أسم مرافق الحجز :',null) !!}
										{!! Form::label($visit[0]->ticket_companion_name) !!}
									</div>
									<div class="form-inline">
										{!! Form::label(' رقم بطاقة مرافق الحجز :',null) !!}
										{!! Form::label($visit[0]->ticket_companion_sin) !!}
									</div>
									@endif
									<div class="form-inline">
										{!! Form::label('مكتب الحجز :',null) !!}
										{!! Form::label($visit[0]->entrypoint->name) !!}
									</div>
									<div class="form-inline">
										{!! Form::label('تاريخ و وقت تسجيل الحجز :',null) !!}
										@if($visit[0]->ticket_type != null)
											{!! Form::label($visit[0]->registration_datetime) !!}
										@else
											{!! Form::label($visit[0]->created_at) !!}
										@endif
									</div>
									<div class="form-inline">
										{!! Form::label('أسم مدخل البيان:',null) !!}
										{!! Form::label($visit[0]->user->name) !!}
									</div>
								</div>
								<div class="col-lg-6"  >
									@if($visit[0]->ticket_type != null)
									<div class="form-inline">
										{!! Form::label('رقم التسلسل :',null) !!}
										{!! Form::label($visit[0]->serial_number) !!}
									</div>
									@endif
									<div class="form-inline">
										{!! Form::label('رقم التذكرة :',null) !!}
										{!! Form::label($visit[0]->ticket_number) !!}
									</div>
								  @if($visit[0]->ticket_type != null)
									<div class="form-inline">
										{!! Form::label('نوع التذكرة',null) !!}
										{!! Form::label($visit[0]->ticket_type=="T"?"إستقبال إصابات":"إستقبال عام") !!}
									</div>
									<div class="form-inline">
										{!! Form::label('المشاهدة :',null) !!}
										{!! Form::label($visit[0]->watching_status) !!}
									</div>
									@endif
									<div class="form-inline">
										{!! Form::label('كود المريض :',null) !!}
										{!! Form::label($visit[0]->patient->id) !!}
									</div>
									
									<div class="form-inline">
										{!! Form::label('أسم المريض :',null) !!}
										{!! Form::label($visit[0]->patient->name) !!}
									</div>
									<div class="form-inline">
										{!! Form::label('النوع :' ,null) !!}
										{!! Form::label($visit[0]->patient->gender=='M'?'ذكر':'أنثى') !!}
									</div>
									<div class="form-inline">
										{!! Form::label('السن :',null) !!}
										{!! Form::label( calculateAge($visit[0]->patient->birthdate)) !!}
									</div>
									<div class="form-inline">
										{!! Form::label('العنوان :',null) !!}
										{!! Form::label($visit[0]->patient->address) !!}
									</div>
									@if($visit[0]->ticket_type != null)
									<div class="form-inline">
										{!! Form::label('المهنة :',null) !!}
										{!! Form::label($visit[0]->patient->job) !!}
									</div>
									@endif
									<div class="form-inline">
										{!! Form::label('رقم البطافة :',null) !!}
										{!! Form::label($visit[0]->patient->sid) !!}
									</div>
								</div>
								<!-- row -->
							</div>
						</div>
						<!-- /.box-body -->
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
