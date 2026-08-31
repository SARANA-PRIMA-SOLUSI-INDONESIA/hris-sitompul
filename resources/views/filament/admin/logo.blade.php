<div class="flex w-full flex-col items-center gap-2 text-center">
    <img
        src="{{ \App\Models\SiteSetting::current()->logo ? asset('storage/'.\App\Models\SiteSetting::current()->logo) : asset('images/logo.png') }}"
        alt="{{ \App\Models\SiteSetting::current()->nama_komunitas }}"
        class="h-16 w-auto object-contain"
    >

</div>
