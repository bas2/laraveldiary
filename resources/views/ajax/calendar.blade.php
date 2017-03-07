{!! Form::open() !!}
<p>
{!! Form::button('&lt;',['id'=>"goLastMonth",'class'=>"lbtn",'title2'=>$vals['datesel'],'title'=>"Previous Month"]) !!}

{!! Form::selectMonth('months', $vals['seldate']->format('n'), ['id'=>'goToMonth','title2'=>$vals['datesel']]) !!}

{!! Form::button('&gt;',['id'=>"goNextMonth",'class'=>"rbtn",'title2'=>$vals['datesel'],'title'=>"Next Month"]) !!}

{!! Form::button('&lt;',['id'=>"goLastYear",'class'=>"lbtn",'title2'=>$vals['datesel'],'title'=>"Previous Year"]) !!}

{!! Form::selectYear('years','1973','2099',$vals['seldate']->format('Y'),['id'=>'goToYear','title2'=>$vals['datesel']]) !!}

{!! Form::button('&gt;',['id'=>"goNextYear",'class'=>"rbtn",'title2'=>$vals['datesel'],'title'=>"Next Year"]) !!}
</p>

<p>
{!! Form::button('This Month',['id'=>"goThisMonth",'class'=>"small",'title2'=>date('Y-m-d'),'title'=>"This Month"]) !!}
{!! Form::button('This Year',['id'=>"goThisYear",'class'=>"small",'title2'=>date('Y-m-d'),'title'=>"This Year"]) !!}
{!! Form::button('Today',['id'=>"goToday",'class'=>"today_col",'title2'=>date('Y-m-d').'|'.date('D'),'title'=>"Today"]) !!}
</p>
{!! Form::close() !!}

<table id="tblDate">
<caption>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</caption>
<tbody id="d">
<tr id="caldayrow">
  {{-- <td class="nohover">wk</td> --}}
  @foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
  <td width="10%">{{ $day }}</td>
  @endforeach
</tr>
</tbody>

<tr>
  {{-- <td>&nbsp;</td> --}}

@if($vals['blankcells'])
  <td colspan="{{ $vals['blankcells'] }}"><a>&nbsp;</a></td>
@endif

@for($i=1;$i<=$vals['daysinmonth'];$i++)
  @if(!empty($vals['monthentries'][$i]))
    @if($vals['seldate']->format('Y-m-'.sprintf('%02d', $i))==date('Y-m-d'))
    <td class="curDate">
    @else
      @if($vals['seldate']->format('Y-m-'.sprintf('%02d', $i))==$vals['datesel'])
      <td class="sel_col">
      @else
      <td class="three_col">
      @endif
    @endif
    <a title2="{{ $vals['seldate']->format('Y-m-'.$i) }}"
     tooltiptxt="<p class=date_s>{{ \Carbon\Carbon::parse($vals['seldate']->format('Y-m-'.$i))->format('l jS F') }} [{{ \App\Entry::daysdiff($vals['seldate']->format('Y-m-'.$i)) }}]</p>{{ $vals['monthentries'][$i] }}">{{ $i }}</a></td>
  @else
    @if($vals['seldate']->format('Y-m-'.sprintf('%02d', $i))==date('Y-m-d'))
    <td class="curDate">
    @else
      @if($vals['seldate']->format('Y-m-'.sprintf('%02d', $i))==$vals['datesel'])
      <td class="sel_col">
      @else
      <td class="four_col">
      @endif
    @endif
    <a title2="{{ $vals['seldate']->format('Y-m-'.$i) }}">{{ $i }}</a></td>
  @endif
  @foreach([7,14,21,28,35] as $rnum)
    @if($i == ($rnum-$vals['blankcells']))
</tr>
<tr>
  {{-- <td>-</td> --}}
    @endif
  @endforeach
@endfor
</tr>

</table>