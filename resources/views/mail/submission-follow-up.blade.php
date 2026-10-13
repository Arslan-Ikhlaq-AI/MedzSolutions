<x-mail::message>
# Hi {{ $firstName }},

Thank you again for getting in touch with {{ $company['name'] }}. {{ $body }}

<x-mail::button :url="$bookingUrl">
Talk to a billing specialist
</x-mail::button>

You can also reach us on {{ $company['phone'] }} or at {{ $company['email'] }}.

Kind regards,<br>
The {{ $company['name'] }} team
</x-mail::message>
