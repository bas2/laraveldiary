<p class="newentry">New</p>

<fieldset class="mode"><legend>Mode</legend>
<span class="add{{ $classes[0] }}">Add</span> | 
<span class="upd">Update</span> | 
<span class="del">Delete</span> | 
<span class="up">Up</span>
</fieldset>

<ul>
@foreach($data as $id=>$qentry)
<li class="li_{{ $qentry->id }}">{{ $qentry->text }}</li>
@endforeach
</ul>