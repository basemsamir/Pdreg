<div class="col-lg-4">
    <div class="form-inline">
        {!! Form::label('التاريخ',null) !!} &nbsp;&nbsp;
        {!! Form::select('date_selection',[''=>'غير مستخدم','today'=>'اليوم','yestarday'=>'الأمس','last_week'=>'الأسبوع الماضي','date_selected'=>'تاريخ أختياري'],'',['id'=>'date_selection','class'=>'form-control']); !!}
        <br><br>
        {!! Form::label('الفترة من',null) !!}
        {!! Form::text('duration_from',null,array('class'=>'form-control','id'=>'datepicker','disabled'=>'disabled','placeholder'=>'1900-01-01')) !!}
        <br><br> &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
        {!! Form::label('ألي',null) !!}
        {!! Form::text('duration_to',null,array('class'=>'form-control','id'=>'datepicker2','disabled'=>'disabled','placeholder'=>'1900-01-01')) !!}

    </div>
</div>
<div class="col-lg-4">
    @if(!isset($department_flag))
        <div class="form-group">
        {!! Form::label('رقم التذكرة') !!} &nbsp;&nbsp;
        {!! Form::text('ticket_number',null,['id'=>'ticket_number','class' => 'form-control']); !!}
        </div>
        <br><br>
    @endif
    <div class="form-group">
    {!! Form::label('كود المريض') !!}
    {!! Form::text('id',null,['id'=>'id','class' => 'form-control']); !!}
    </div>
    <br><br>
    <div class="form-group">
    {!! Form::label('أسم المريض') !!}
    {!! Form::text('name',null,['id'=>'name','class' => 'form-control']); !!}
    </div>
</div>