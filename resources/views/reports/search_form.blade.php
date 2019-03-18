<div class="col-lg-4">
    @include('./layouts/components/date_interval')
</div>
<div class="col-lg-4">
    <div class="form-group">
        {!! Form::label('العيادة') !!}
        {!! Form::select('clinic',$clinics,null,['id'=>'clinic','class' => 'form-control']); !!}
    </div>
</div>
@if(isset($doctors))
<br><br>
<div class="col-lg-4">
    <div class="form-group">
        {!! Form::label('الطبيب') !!}
        {!! Form::select('doctor',[],null,['id'=>'doctor_select','class' => 'form-control']); !!}
    </div>
</div>
@endif