<ul class="list-inline">
  @foreach($activities as $activityid=>$activity)
  <li>{{ Html::image("img/activities/$activityid.jpg",$activityid,['width'=>80,'title'=>$activity,'class'=>'thumbnail']) }}</li>
  @endforeach
</ul>

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
<script>
//$(document).ready(function(){
  $('body').on('click','.activitysel li img',function(){
    $('.activitysel select[name=whatdidyoudoa]').val($(this).attr('alt'));
        var $this=$(this);
    $.ajax({
      type: 'get',
      url: 'activityhint/' + $this.attr('alt') ,
      success: function (hint){
        $this.parent().parent().next().next().find('input[name=whatdidyoudot]').val('').attr('placeholder',hint).focus();
      }
    });
  });
//});
</script>