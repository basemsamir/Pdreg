<html>
<head>
    <meta charset="utf-8">
	  <link rel="stylesheet" href="{!! asset('bootstrap/fonts/Code39Azalea.css') !!}">

    <title>
      تذكرة المريض
    </title>
	<style>
		body{
      width: 12cm;
      height: 15.5cm;
      margin: 0 auto;
      
		}
    ul{
      list-style: none;
      margin-top: 150px;
      float: right;
      margin-right: 150px;
    }
    ul li{
      padding-bottom: 8px;
    }
    p{
      position: relative;
      top: 50;
	  left: 100;
      font-weight: bold;
    }
	</style>
<body onload="window.print()">
  <p>{{$medical_unit[0]->name}}</p>
<div>
    <ul style="">
		<li>{{ $patient_data[0]->name }}</li>
		<li>{{ $created_at }}</li>
    <li style="direction: rtl;">
      <?php
          $current_date = new DateTime();
          $birthdate = new DateTime($patient_data[0]->birthdate);
          $interval = $current_date->diff($birthdate);
      ?>
      @if($interval->y > 0)
        {{ $interval->y }}
        @if( $interval->y > 11 )
          {{ "سنة" }}
        @else
          {{ "سنوات" }}
        @endif
      @elseif($interval->m > 0)
        {{ $interval->m }}
        @if( $interval->m > 11 )
          {{ "شهر" }}
        @else
          {{ "شهور" }}
        @endif
      @else
        {{ $interval->d." يوم " }}
        @if( $interval->d > 11 )
          {{ "يوم" }}
        @else
          {{ "أيام" }}
        @endif
      @endif
      </li>
      <li>{{ $patient_data[0]->address }}</li>
	</ul>
</div>
</body>
</head>
</html>
