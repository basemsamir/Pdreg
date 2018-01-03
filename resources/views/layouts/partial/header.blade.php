 <header class="main-header">
    <!-- Logo -->
    <a href="{{url('/')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Pd</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Pd</b>REG</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#"  data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
		  <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="{{url('auth/logout')}}" >
              <i class="fa fa-user" ></i>
              تسجيل خروج
            </a>

          </li>

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">

        <div class="pull-right image" style="color: white;" >
           <p><b>{{Auth::user()->name}}&nbsp &nbsp<i class="fa fa-user" style="color:white; font-size: 20px;"></i>
		   </b></p>
        </div>

      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">القائمة الرئيسية</li>
		@if($role_name =='Doctor')
			@if($medicalunits)
				@foreach($medicalunits as $row)
					@if(Request::segment(2) == $row->id)
						<li class="active">
					@else
						<li>
					@endif
						  <a href='{{url("visits/$row->id")}}'>
							<span><b>{{ $row->type=='c'?' عيادة '.$row->name:' قسم '.$row->name }}</b></span> <i class="fa fa-medkit"></i>
						  </a>
						</li>
				@endforeach
				<li class=" {{ isset($s_active)? $s_active:'' }} ">
					  <a href="{{url('visits/patient/show')}}" >
  						<span><b>دليل المرضى</b></span> <i class="fa fa-book"></i>
  					</a>
				</li>
			@endif
		@else
		@if($role_name =='Admin' || $role_name =='SubAdmin')
        <li class=" {{ isset($d_active)? $d_active:'' }} treeview">
          <a href="{{url('admin')}}">
            <span><b>لوحة التحكم</b></span> <i class="fa fa-dashboard"></i>
          </a>

        </li>
			@if($role_name =='Admin')
			<li class="">
			  <a href="{{url('logs')}}" target="_blank">
				<span><b>صفحة أخطاء و مشاكل النظام</b></span> <i class="fa fa-exclamation-triangle"></i>
			  </a>
			</li>

			<li class=" {{ isset($ld_active)? $ld_active:'' }}">
			  <a href="{{url('admin/datalog')}}">
				<span><b>سجل النظام</b></span> <i class="fa fa-file-archive-o"></i>
			  </a>
			</li>
			@endif
		<li class=" {{ isset($iu_active)? $iu_active:'' }} ">
          <a href="{{url('admin/users')}}">
            <span><b>أدارة المستخدمين</b></span> <i class="fa fa-cogs"></i>
          </a>
        </li>
		<li class="treeview {{ isset($mu_active)?'active':'' }}">
			<a href="#">
				<span><b>الوحدات الطبية</b></span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
				<i class="fa fa-h-square"></i>
			</a>
			<ul class="treeview-menu">
				<li class=" {{ isset($o)? ($o=='1'?'active':''):'' }} ">
				  <a href="{{url('admin/medicalunits')}}">
					<span><b>أدارة الوحدات الطبية</b></span><i class="fa fa-circle-o"></i></i>
				  </a>
				</li>
				<li class=" {{ isset($o)? ($o=='2'?'active':''):'' }} ">
				  <a href="{{url('admin/assignclinic')}}">
					<span><b>أضافة عيادات الأقسام</b></span><i class="fa fa-circle-o"></i></i>
				  </a>
				</li>
				<li class=" {{ isset($o)? ($o=='3'?'active':''):'' }} ">
				  <a href="{{url('admin/assigndoctor')}}">
					<span><b>أضافة أطباء الأقسام</b></span><i class="fa fa-circle-o"></i></i>
				  </a>
				</li>
			</ul>
		</li>
		<li class="treeview {{ isset($e_active)?'active':'' }}">
			<a href="#">
				<span><b>إدارة مكاتب مستخدمي النظام</b></span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
				<i class="fa fa-ticket"></i>
			</a>
			<ul class="treeview-menu">
				<li class=" {{ isset($o)? ($o=='1'?'active':''):'' }} ">
				  <a href="{{url('admin/entrypoints')}}">
					<span><b>أضافة المكاتب</b></span> <i class="fa fa-circle-o"></i>
				  </a>
				</li>
				<li class=" {{ isset($o)? ($o=='2'?'active':''):'' }} ">
				  <a href="{{url('admin/assignentrypoint')}}">
					<span><b>أضافة مستخدم الي مكتب</b></span> <i class="fa fa-circle-o"></i>
				  </a>
				</li>
			</ul>
		</li>
		<li class=" {{ isset($s_active)? $s_active:'' }} ">
			<a href="{{url('admin/show')}}">
				<span><b>دليل المرضى</b></span> <i class="fa fa-book"></i>
			</a>
		</li>
		<li class="treeview {{ isset($r1_active) || isset($r2_active) || isset($r3_active) || isset($r4_active) || isset($r5_active) || isset($r6_active) || isset($r7_active) || isset($r8_active) || isset($r9_active) || isset($r10_active) || isset($r11_active)? 'active':'' }}">
			<a href="#">
				<span><b>تقارير</b></span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
				<i class="fa fa-pie-chart"></i>
			</a>
			<ul class="treeview-menu">

			  <li class="treeview">
				<a href="#">
					<span><b>تقارير حجز التذاكر</b></span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
					<i class="fa fa-pie-chart"></i>
				</a>
				<ul class="treeview-menu">
					@if($entrypoints_header)
						@foreach($entrypoints_header as $row)
							@if($row->type == 1)
							<li>
								<a target="_blank" href='{{url("admin/visitstoday/$row->id")}}'><b>تقرير {{ $row->name }}  اليوم</b><i class="fa fa-print"></i></a>
							</li>
							@endif
						@endforeach
					@endif
					<li class=" {{ isset($r2_active)? 'active':'' }} " >
					  <a href='{{url("admin/visitsperiod")}}'><b>تقرير مكاتب حجز التذاكر خلال فترة</b><i class="fa fa-circle-o"></i></a>
				    </li>


				</ul>

			  </li>


			  <li class="treeview {{ isset($r8_active) || isset($r9_active)? 'active':'' }}">
				<a href="#">
					<span><b>تقارير الأستقبال</b></span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
					<i class="fa fa-pie-chart"></i>
				</a>
				<ul class="treeview-menu">
					@if($entrypoints_header)
						@foreach($entrypoints_header as $row)
							@if($row->type == 3)
							<li>
								<a target="_blank" href='{{url("admin/visitstoday/$row->id")}}'><b>تقرير {{ $row->name }}  اليوم</b><i class="fa fa-print"></i></a>
							</li>
							@endif
						@endforeach
					@endif
					<li class=" {{ isset($r8_active)? 'active':'' }} " >
					  <a href='{{url("admin/desk_visits_period")}}'><b>تقرير مكاتب الأستقبال خلال فترة</b><i class="fa fa-circle-o"></i></a>
				    </li>
					<li class=" {{ isset($r9_active)? 'active':'' }} " >
					  <a href='{{url("admin/rec_desk_visits_period")}}'><b>المحولون من حجز العيادة الي الأستقبال</b><i class="fa fa-circle-o"></i></a>
				    </li>
				</ul>

			  </li>
			   <li class="treeview {{ isset($r4_active) || isset($r3_active)? 'active':'' }}">
				<a href="#">
					<span><b>تقارير العيادات</b></span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
					<i class="fa fa-pie-chart"></i>
				</a>
				<ul class="treeview-menu">
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a  target="_blank" href='{{url("admin/print_total_patients_today")}}'><b>تقرير إجمالي عدد الحالات اليوم</b><i class="fa fa-print"></i></a>
				    </li>
				    <li class=" {{ isset($r3_active)? 'active':'' }} " >
					  <a href='{{url("admin/total_patients_period")}}'><b>تقرير إجمالي عدد الحالات خلال فترة</b><i class="fa fa-circle-o"></i></a>
				    </li>
				</ul>

			  </li>

			  <li class="treeview">
				<a href="#">
					<span><b>تقارير مرضي الدخول</b></span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
					<i class="fa fa-pie-chart"></i>
				</a>
				<ul class="treeview-menu">
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a target="_blank" href="{{url('admin/inpatienttoday')}}">
						<span><b>تقرير مرضي الدخول اليوم</b></span> <i class="fa fa-print"></i>
					  </a>
				    </li>
					<li class=" {{ isset($r5_active)? 'active':'' }} " >
					  <a href='{{url("admin/inpatientsvisitsperiod")}}'><b>تقرير مرضى الدخول خلال فترة</b><i class="fa fa-circle-o"></i></a>
					</li>
				    <li class=" {{ isset($r6_active)? 'active':'' }} " >
					  <a href='{{url("admin/print_total_inpatients")}}'><b>تقرير حصر دخول مرضى الداخلي</b><i class="fa fa-circle-o"></i></a>
					</li>
				</ul>
			  </li>
				<li class="treeview {{ isset($r11_active)? 'active':'' }}">
				<a href="#">
					<span><b>تقارير طبية</b></span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
					<i class="fa fa-pie-chart"></i>
				</a>
				<ul class="treeview-menu">
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a href="{{url('admin/medicalreports/clinics')}}">
						<span><b>عيادات خارجية</b></span> <i class="fa fa-circle-o"></i>
					  </a>
				  </li>
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a href="{{url('admin/medicalreports/entry_clinics')}}">
						<span><b>اقسام داخلية عيادات خارجية</b></span> <i class="fa fa-circle-o"></i>
					  </a>
				  </li>
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a href="{{url('admin/medicalreports/gdesk')}}">
						<span><b>استقبال عام</b></span> <i class="fa fa-circle-o"></i>
					  </a>
				  </li>
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a href="{{url('admin/medicalreports/entry_gdesk')}}">
						<span><b>اقسام داخلية استقبال عام</b></span> <i class="fa fa-circle-o"></i>
					  </a>
				  </li>
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a href="{{url('admin/medicalreports/tdesk')}}">
						<span><b>استقبال اصابات</b></span> <i class="fa fa-circle-o"></i>
					  </a>
				  </li>
					<li class=" {{ isset($r4_active)? 'active':'' }} " >
					  <a href="{{url('admin/medicalreports/entry_tdesk')}}">
						<span><b>اقسام داخلية استقبال اصابات</b></span> <i class="fa fa-circle-o"></i>
					  </a>
				  </li>
				</ul>
			  </li>
        <li class="treeview {{ isset($r10_active)? 'active':'' }}">
  				<a href='{{url("admin/print_medicines")}}'>
  					<span><b>تقرير الأدوية و المستلزمات</b></span>
  					<i class="fa fa-pie-chart"></i>
  				</a>
        </li>
			</ul>
		</li>
		<li class="">
			<a href="{{ url('admin/backup') }}" onclick="show_loading_screen();">
				<span><b>عمل نسخة الأحتياطية</b></span> <i class="fa fa-database"></i>
			</a>
		</li>
		{!! Form::open(array('id'=>'restore_form_id','action'=>'AdminController@restore_file','enctype'=>'multipart/form-data')) !!}
			{!! Form::file('restore_file',array('id'=>'restore_file','style'=>'display:none;'));!!}
		{!! Form::close() !!}
		<li class="">
			<a href="#" onclick="$('#restore_file').click();">
				<span><b>أسترجاع النسخة الأحتياطية</b></span> <i class="fa fa-database"></i>
			</a>
		</li>
		@else
			@if($role_name == 'Entrypoint')
				@if(!is_null($entrypoint_sub_type) && $entrypoint_sub_type != "exit_only")
				<li class=" {{ isset($p_active)? $p_active:'' }} ">
				  <a href="{{url('patients')}}">
					<span><b>تسجيل بيانات المرضى</b></span> <i class="fa fa-th"></i>
				  </a>
				</li>
				@endif
			@elseif($role_name == 'Receiption')
				<li class=" {{ isset($r_active)? $r_active:'' }} ">
				  <a href="{{url('patients/reserve/-1')}}">
					<span><b>حجز تذكرة كشف عيادة</b></span> <i class="fa fa-ticket"></i>
				  </a>
				</li>
				@if($entrypoints_header)
					@foreach($entrypoints_header as $row)
						<li class=" {{ isset($sv_active)? $sv_active:'' }} ">
							<a href='{{url("patients/showvisits/$row->id")}}'>
							<span><b>{{ $row->name }}</b></span> <i class="fa fa-book"></i>
						  </a>
						</li>

					@endforeach
				@endif
			@elseif($role_name == 'Desk')
				<li class=" {{ isset($desk_active)? $desk_active:'' }} ">
				  <a href="{{url('patients/desk/-1')}}">
					<span><b>حجز تذكرة استقبال</b></span> <i class="fa fa-ticket"></i>
				  </a>
				</li>
				@if($entrypoints_header)
					@foreach($entrypoints_header as $row)
						<li class=" {{ isset($sv_active)? $sv_active:'' }} ">
							<a href='{{url("patients/showvisits/$row->id")}}'>
							<span><b>{{ $row->name }}</b></span> <i class="fa fa-book"></i>
						  </a>
						</li>

					@endforeach
				@endif
			@endif



			<li class=" {{ isset($ip_active)? $ip_active:'' }} ">
			  <a href="{{url('patients/showinpatient')}}">
				<span><b>مرضي الدخول</b></span> <i class="fa fa-book"></i>
			  </a>
			</li>
			@if(is_null($entrypoint_sub_type) || $entrypoint_sub_type != "exit_only")
			<li class=" {{ isset($s_active)? $s_active:'' }} ">
			  <a href="{{url('patients/show')}}">
				<span><b>دليل المرضى</b></span> <i class="fa fa-book"></i>
			  </a>
			</li>
			@endif
			@if($role_name == 'Receiption' || $role_name == 'Desk')
			  @if($entrypoints_header)
				@foreach($entrypoints_header as $row)
				<li>
				  <a target="_blank" href='{{url("admin/visitstoday/$row->id")}}'><b>تقرير {{ $row->name }}  اليوم</b><i class="fa fa-print" aria-hidden="true"></i></a>
				</li>
				@endforeach
			  @endif
			@endif
				@if(is_null($entrypoint_sub_type) || $entrypoint_sub_type != "exit_only")
				<li class=" {{ isset($r4_active)? 'active':'' }} ">
				  <a target="_blank" href="{{url('admin/inpatienttoday')}}">
					<span><b>تقرير مرضي الدخول اليوم</b></span> <i class="fa fa-print"></i>
				  </a>
				</li>
				@endif
			@endif <!-- End if $v_active -->
		@endif <!-- End if $role_name == Admin -->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
