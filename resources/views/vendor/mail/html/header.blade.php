@props(['url'])
<tr>
<td class="header">
<div class="logo-container">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'UiTM Credit System')
<!-- UiTM Logo Placeholder - Add actual logo later -->
<div style="margin-bottom: 16px;">
    <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block;">
        <circle cx="32" cy="32" r="30" fill="#e2a817" opacity="0.2"/>
        <path d="M32 12L20 24V44L32 52L44 44V24L32 12Z" fill="#ffffff"/>
        <path d="M28 28H36V36H28V28Z" fill="#e2a817"/>
    </svg>
</div>
<div class="university-name">UNIVERSITI TEKNOLOGI MARA</div>
<div class="system-name">Credit Exemption System</div>
@else
{!! $slot !!}
@endif
</a>
</div>
</td>
</tr>
