@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset($general_settings['full_logo'])}}" width="300px" alt="" />
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
