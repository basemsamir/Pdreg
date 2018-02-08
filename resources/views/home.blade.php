@extends('layouts.app')
@section('title')
لوحة التحكم
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        لوحة التحكم
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">لوحة التحكم</li>
      </ol>
	 
    </section>

    <!-- Main content -->
    <section class="content">
	  <div id="overlay"></div>
	  @if (count($errors) > 0)
		<div class="alert alert-danger alert-dismissible" style="direction: rtl;">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>

		</div>
	@endif
      <!-- Small boxes (Stat box) -->
      <div class="row">

		    <div class="col-lg-3 col-sm-24">

          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$inpatient_visits}}</h3>

              <p><b>عدد مرضى القسم الداخلي</b></p>
            </div>
            <div class="icon">
             <i class="fa fa-bed" aria-hidden="true"></i>
            </div>
          <!--  <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-sm-24">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$patients_count}}</h3>

              <p><b>عدد المرضى الجدد اليوم</b></p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
           <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-sm-24">
          <div class="small-box bg-green">
              <div class="inner">
                <h3>{{$outpatient_desk_visits}}</h3>

                <p><b>عدد حالات الأستقبال اليوم</b></p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <div class="col-lg-3 col-sm-24">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $outpatient_clinic_visits }}</h3>
              <p><b>عدد حالات العيادات اليوم</b></p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>

        <!-- ./col -->
        
      </div>
      <!-- /.row -->
	  <div class="row" dir="rtl">

			<div class="col-lg-4 col-sm-24" style="text-align: right">
				<div class="panel panel-default">
				  <div class="panel-heading"><b>المستخدمين النشطاء</b></div>
				  <div class="panel-body">
					<table id="example" class="table table-bordered table-hover" >
						<thead>
						<tr>
						  <th style="text-align:center">أسم المستخدم</th>
						  <th style="text-align:center">البريد الألكتروني</th>
						</tr>
						</thead>
						<tbody>
							<?php $i=0; ?>
							@foreach($active_users as $row)
							 <tr>
							  <td>{{$row->user->name}}</td>
							  <td>{{$row->user->email}}</td>
							 </tr>
							@endforeach
						</tbody>
					</table>
				  </div>
				</div>
			</div>
			<div class="col-lg-8 col-sm-24"  style="text-align: right">
				<div class="panel panel-default">
				  <div class="panel-heading"><b>المرضى الجدد</b></div>
				  <div class="panel-body">
            <div class="form-inline">
              {!! Form::open(array('class'=>'form')) !!}
                {!! Form::label('رقم التذكرة',null) !!}
                {!! Form::text('ticket_number',null,['class'=>'form-control']) !!}
                <button type="submit" class="btn btn-primary" >بحث</button>
              {!! Form::close() !!}
            </div>
            <br>
						<table id="example4" class="table table-bordered table-hover">
							<thead>
							<tr>
							  <th style="text-align:center">رقم التذكرة</th>
                <th style="text-align:center">مصدر التذكرة</th>
							  <th style="text-align:center">الكود</th>
							  <th style="text-align:center">الأسم</th>
                <th style="text-align:center">العيادة</th>
                <th style="text-align:center">التفاصيل</th>
							  <th style="text-align:center">تعديل</th>
                <th style="text-align:center">تحويل الي الداخلي</th>
							</tr>
							</thead>
							<tbody>
								<?php $i=0; ?>
                @if(Session::has('visits'))
                  <?php $visits=Session::get('visits'); ?>
                @endif
								@foreach($visits as $row)
								<tr id="row{{$i}}">
								  <td>{{$row->ticket_number}}</td>
                  <td>@if($row->ticket_type==null)
                      {{'عيادات'}}
                      @elseif($row->ticket_type=="G")
                      {{'استقبال عام'}}
                      @else
                      {{'استقبال اصابات'}}
                      @endif
                  </td>
								  <td>{{$row->patient->id}} <?php $id=$row->patient->id;$vid=$row->id; ?></td>
								  <td>{{$row->patient->name}}</td>
                  <td>
                    @if(!$row->all_deps)
                      @foreach($row->medicalunits as $medicalunit)
                        @if($medicalunit->pivot->convert_to == null)
                          {{$medicalunit->name}}
                        @endif
                      @endforeach
                    @else
                      {{'إستكشاف طارىء'}}
                    @endif
                  </td>
                  <td align="center"><a href=' {{ url("admin/show/$vid") }} '
									  class="btn btn-primary" ><i class="fa fa-eye"></i></a></td>
								  <td align="center"><a href=' {{ url("admin/$id&$vid/edit") }} '
									  class="btn btn-success" ><i class="fa fa-edit"></i></a></td>

                  <td align="center">
                  <?php $department_conversion=false; ?>
                  <?php $clinic_id=0; ?>

                    @if($row->closed)
                      {{ "تم إنهاء زيارة المريض" }}
                    @else
                      @foreach($row->medicalunits as $medicalunit)
                          @if($medicalunit->pivot->department_conversion != null)
                          <?php $department_conversion=true; ?>
                          @endif
                          <?php $clinic_id=$medicalunit->id  ?>
                      @endforeach
                      @if($department_conversion)
                          {{ "تم التحويل المريض" }}
                      @else
                        <a href=' {{ url("admin/$id&$vid&$clinic_id/converttoentry") }} '
                          class="btn btn-warning" ><i class="fa fa-undo"></i></a>
                      @endif
                    @endif
                  </td>
								</tr>
								<?php $i++;?>
								@endforeach
							</tbody>
						</table>
				  </div>
				</div>
			</div>
	  </div>
	  <!-- row -->
    </section>
    <!-- /.content -->
  </div>

@endsection
@section('javascript')
<script>
$('#restore_file').change(function() {
  $("#overlay").show();
  $('#restore_form_id').submit();

});

</script>
@stop
