@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">
			سجل النظام
		</li>
      </ol>
	  <h1>
			سجل النظام
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
				  
				  <div class="row" >
					{!! Form::open(array('class'=>'form','name'=>'patient_form')) !!}
					 <div class="col-lg-12">
						<div class="form-group">
						  {!! Form::label('Date') !!}
						  {!! Form::text('date',null,array('id'=>'datepicker2','class'=>'form-control')) !!}
						</div>
						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
					 </div>
					{!! Form::close() !!}
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12" style="overflow-x: scroll">
							<table id="example3" class="table table-bordered table-hover">
								<thead>
								<tr>
								  <th style="text-align:center">Number</th>
								  <th style="text-align:center">User ID</th>
								  <th style="text-align:center">Loggable ID</th>
								  <th style="text-align:center">Loggable Type</th>
								  <th style="text-align:center">Action</th>
								  <th style="text-align:center">Before</th>
								  <th style="text-align:center">After</th>
								  <th style="text-align:center">Created At</th>
									
								</tr>
								</thead>
								<tbody>
									@foreach($ldata as $row)
									<tr> <td> {{$row->id}} </td> <td> {{$row->user_id}} </td> <td> {{$row->loggable_id}} </td> 
										 <td> {{$row->loggable_type}} </td> <td> {{$row->action}} </td> <td> {{json_encode($row->before, JSON_UNESCAPED_UNICODE)}} </td> 
										 <td> {{json_encode($row->after, JSON_UNESCAPED_UNICODE)}} </td> <td> {{$row->created_at}} </td>  
									</tr>
									@endforeach
								</tbody>
							</table>
						</div> <!-- ./col-lg-12 -->
					</div> <!-- ./row -->
			  </div>			  
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
