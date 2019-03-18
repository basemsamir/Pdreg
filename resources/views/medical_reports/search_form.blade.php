<div class="col-lg-4">
    @include('./layouts/components/date_interval')
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