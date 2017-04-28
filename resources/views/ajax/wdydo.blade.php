<div class="form-group">
{{ Form::select('whatdidyoudoa',$activities,'',['class'=>'form-control']) }}
</div>
<div class="form-group">
{{ Form::text('whatdidyoudot',null,['class'=>'form-control']) }}
</div>
<div class="form-group">
{{ Form::button('Add >',['name'=>'add','class'=>'btn btn-primary btn-block']) }}
{{ Form::button('Cancel >',['name'=>'cancel','class'=>'btn btn-warning btn-block']) }}
</div>