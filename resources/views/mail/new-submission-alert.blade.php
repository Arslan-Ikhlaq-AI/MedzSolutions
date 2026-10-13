<x-mail::message>
# New {{ strtolower($label) }}

**{{ $name }}** sent a {{ strtolower($label) }} through the website. Reply to this email to answer them directly.

<x-mail::table>
| | |
|:--|:--|
| Name | {{ $name }} |
| Email | {{ $email }} |
@if ($phone)
| Phone | {{ $phone }} |
@endif
@foreach ($details as $detailLabel => $value)
| {{ $detailLabel }} | {{ $value }} |
@endforeach
</x-mail::table>

@if ($message)
<x-mail::panel>
{{ \Illuminate\Support\Str::limit($message, 1200) }}
</x-mail::panel>
@endif

## What they looked at first

@if ($journey)
First visit **{{ $journey['first_seen']->format('M j, Y') }}** from **{{ $journey['source'] }}**. They viewed **{{ $journey['page_count'] }} {{ \Illuminate\Support\Str::plural('page', $journey['page_count']) }}** over {{ $journey['visit_days'] }} {{ \Illuminate\Support\Str::plural('day', $journey['visit_days']) }}:

@foreach ($journey['pages']->take(-12) as $page)
- {{ $page->created_at->format('M j, g:i A') }} · {{ $page->title ?: $page->path }}
@endforeach
@else
No visit history. This visitor did not allow analytics cookies, so we only know what they wrote in the form.
@endif

<x-mail::button :url="$adminUrl">
Open in admin panel
</x-mail::button>
</x-mail::message>
