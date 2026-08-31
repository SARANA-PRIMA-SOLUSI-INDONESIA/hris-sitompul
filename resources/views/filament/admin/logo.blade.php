@php($setting = \App\Models\SiteSetting::current())
<div class="flex w-full items-center gap-3">
    <img
        src="{{ $setting->logo ? asset('storage/'.$setting->logo) : asset('images/logo.png') }}"
        alt="{{ $setting->nama_komunitas }}"
        class="h-16 w-auto object-contain"
    >
    <span class="truncate">{{ $setting->nama_komunitas ?: 'Marga Sitompul' }}</span>
</div>
