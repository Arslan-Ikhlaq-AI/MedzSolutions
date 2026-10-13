<x-mail::message>
# Hi {{ $firstName }},

{{ $body }}

@if ($details)
<x-mail::panel>
**Your request**<br>
@foreach ($details as $label => $value)
{{ $label }}: {{ $value }}<br>
@endforeach
</x-mail::panel>
@endif

If you need anything in the meantime, just reply to this email or call us on {{ $company['phone'] }}.

<x-mail::button :url="$websiteUrl">
Visit our website
</x-mail::button>

Kind regards,<br>
The {{ $company['name'] }} team
</x-mail::message>
