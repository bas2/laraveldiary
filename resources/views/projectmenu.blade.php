<ul id="projectsmenu">
@foreach($projlist as $project)
    @if (\Config::get('constants.appname') == $project)
    <li class="sel">{{ $project }}</li>
    @else
    <li>{{ link_to('../../../laravel/' . strtolower($project) . '/public', $project) }}</li>
    @endif
@endforeach
</ul>