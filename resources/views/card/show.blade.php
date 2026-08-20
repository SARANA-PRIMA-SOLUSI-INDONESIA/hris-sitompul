<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $employee->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 420px;
            width: 100%;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 28px 24px;
            text-align: center;
        }
        .avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 4px solid #fff;
            object-fit: cover;
            display: block;
            margin: 0 auto 12px;
            background: #fff;
        }
        .avatar-placeholder {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 4px solid #fff;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: #fff;
            font-size: 32px;
            font-weight: 700;
        }
        .name {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
        }
        .position {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            margin-top: 4px;
        }
        .company {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            margin-top: 2px;
        }
        .card-body { padding: 24px; }
        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon { font-size: 18px; width: 24px; text-align: center; }
        .info-label { font-size: 12px; color: #64748b; }
        .info-value { font-size: 14px; color: #0f172a; margin-top: 2px; word-break: break-word; }
        .card-footer {
            background: #f8fafc;
            padding: 14px 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        a { color: inherit; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            @if ($employee->foto)
                <img class="avatar" src="{{ asset('storage/'.$employee->foto) }}" alt="{{ $employee->nama_lengkap }}">
            @else
                <div class="avatar-placeholder">{{ strtoupper(substr($employee->nama_lengkap, 0, 1)) }}</div>
            @endif
            <div class="name">{{ $employee->nama_lengkap }}</div>
            <div class="position">{{ $employee->position?->nama }}</div>
            <div class="company">{{ config('app.name', 'Perusahaan') }}</div>
        </div>
        <div class="card-body">
            <div class="info-row">
                <div class="info-icon">&#128197;</div>
                <div>
                    <div class="info-label">No. Pegawai</div>
                    <div class="info-value">{{ $employee->no_pegawai }}</div>
                </div>
            </div>
            @if ($employee->department)
            <div class="info-row">
                <div class="info-icon">&#127970;</div>
                <div>
                    <div class="info-label">Departemen</div>
                    <div class="info-value">{{ $employee->department->nama }}</div>
                </div>
            </div>
            @endif
            @if ($employee->no_telp)
            <div class="info-row">
                <div class="info-icon">&#128222;</div>
                <div>
                    <div class="info-label">Telepon</div>
                    <div class="info-value"><a href="tel:{{ $employee->no_telp }}">{{ $employee->no_telp }}</a></div>
                </div>
            </div>
            @endif
            @if ($employee->email_pribadi)
            <div class="info-row">
                <div class="info-icon">&#9993;</div>
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value"><a href="mailto:{{ $employee->email_pribadi }}">{{ $employee->email_pribadi }}</a></div>
                </div>
            </div>
            @endif
            @if ($employee->alamat)
            <div class="info-row">
                <div class="info-icon">&#128205;</div>
                <div>
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $employee->alamat }}</div>
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ config('app.name', 'SITOMPUL') }} &middot; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
