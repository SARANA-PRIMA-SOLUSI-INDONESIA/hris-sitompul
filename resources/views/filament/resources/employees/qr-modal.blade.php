<div style="text-align:center; padding: 16px 0;">
    <div style="background:#fff; display:inline-block; padding:16px; border:1px solid #e2e8f0; border-radius:12px;">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->generate($url) !!}
    </div>

    <p style="margin-top:16px; font-size:16px; font-weight:600;">{{ $employee->nama_lengkap }}</p>
    <p style="color:#64748b; font-size:14px; margin-top:4px;">{{ $employee->position?->nama }}</p>

    <p style="margin-top:16px; font-size:14px; word-break:break-all; color:#0f172a;">
        <a href="{{ $url }}" target="_blank" style="color:#059669;">{{ $url }}</a>
    </p>

    <div style="margin-top:12px;">
        <a href="{{ $url }}" target="_blank"
           style="display:inline-block; background:#059669; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-size:14px;">
            Buka Kartu Nama
        </a>
    </div>
</div>
