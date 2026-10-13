@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('images/brand/logo-mark-128.png') }}" class="logo" alt="" width="40" height="40" style="vertical-align: middle; margin-right: 10px;">
<span style="vertical-align: middle;">{!! $slot !!}</span>
</a>
</td>
</tr>
