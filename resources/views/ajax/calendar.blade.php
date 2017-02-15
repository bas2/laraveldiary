<table id="tblDate">
<tr id="caldayrow">
<tbody id="d">
<td class="nohover">wk</td>
@foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
<td width="10%">{{ $day }}</td>
@endforeach
</tbody>
</tr>
</table>