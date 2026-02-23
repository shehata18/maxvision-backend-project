@props(['url'])
<tr>
<td class="header" style="padding: 30px 0; text-align: center; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
@php
    $appName = config('app.name');
    $slotContent = trim($slot);
    $shouldShowLogo = in_array($slotContent, ['Laravel', 'MaxVision Display', $appName]);
    
    $logoUrl = null;
    if ($shouldShowLogo) {
        try {
            $logoPath = \App\Models\Setting::get('site_logo');
            if ($logoPath) {
                $logoUrl = config('app.url') . '/storage/' . $logoPath;
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to load logo in email: ' . $e->getMessage());
        }
    }
@endphp

@if($shouldShowLogo && $logoUrl)
    <img src="{{ $logoUrl }}" 
         alt="{{ $appName }}" 
         width="200" 
         height="auto"
         style="height: 50px; max-height: 50px; width: auto; display: block; margin: 0 auto; border: 0;"
         border="0">
@elseif($shouldShowLogo)
    <span style="color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">{{ $appName }}</span>
@else
    {{ $slot }}
@endif
</a>
</td>
</tr>
