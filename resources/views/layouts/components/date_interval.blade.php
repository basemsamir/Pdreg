<div class="form-inline">
    {!! Form::label('التاريخ',null) !!} &nbsp;&nbsp;
    {!! Form::select('date_selection',['today'=>'اليوم','yestarday'=>'الأمس','last_week'=>'الأسبوع الماضي','date_selected'=>'تاريخ أختياري'],'',['id'=>'date_selection','class'=>'form-control']); !!}
    <br><br>
    {!! Form::label('الفترة من',null) !!}
    {!! Form::text('duration_from',null,array('class'=>'form-control','id'=>'datepicker','disabled'=>'disabled','placeholder'=>'1900-01-01','autocomplete'=>'off')) !!}
    <br><br> &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
    {!! Form::label('ألي',null) !!}
    {!! Form::text('duration_to',null,array('class'=>'form-control','id'=>'datepicker2','disabled'=>'disabled','placeholder'=>'1900-01-01','autocomplete'=>'off')) !!}
</div>
