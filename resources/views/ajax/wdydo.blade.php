<div class="form-group">
{{ Form::select('whatdidyoudoa',$activities,'',['class'=>'form-control']) }}
</div>
<div class="form-group">
{{ Form::text('whatdidyoudot',null,['class'=>'form-control']) }}
</div>
<div class="form-group">
{{ Form::button('Add >',['class'=>'btn btn-primary btn-block']) }}
</div>