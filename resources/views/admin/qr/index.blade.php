@extends('layouts.admin')

@section('title', 'QR Pengunjung')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">QR Pengunjung</h3>
        <small class="text-muted">Generate & tampilkan QR kunjungan harian</small>
    </div>

    {{-- BUTTON GENERATE --}}
    @if(!$qr)
        <form method="POST" action="{{ route('admin.qr.generate') }}">
            @csrf
            <button class="btn btn-primary shadow-sm">
                <i class="bi bi-qr-code"></i> Generate QR
            </button>
        </form>
    @else
        <button class="btn btn-secondary shadow-sm" disabled>
            <i class="bi bi-check-circle"></i> QR Aktif
        </button>
    @endif
</div>

{{-- ALERT --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-warning">{{ session('error') }}</div>
@endif

@if($qr)
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <div class="row align-items-center">

            {{-- SEGMENT QR --}}
            <div class="col-md-5 text-center border-end">
                <span class="badge bg-success mb-3">QR Aktif</span>

                <p class="text-muted mb-1">
                    Berlaku:
                    <strong>{{ $tanggal->translatedFormat('d F Y') }}</strong>
                </p>

                <div class="my-3">
                    {!! QrCode::size(220)->generate(route('qr.public', $qr->token)) !!}
                </div>

                <small class="text-muted">
                    Tampilkan QR ini di monitor pengunjung
                </small>
            </div>

            {{-- SEGMENT LINK & ACTION --}}
            <div class="col-md-7 ps-md-4">

                <label class="form-label fw-semibold">Link QR</label>

                <div class="input-group mb-4">
                    <input id="qrLink"
                           type="text"
                           class="form-control"
                           value="{{ route('qr.public', $qr->token) }}"
                           readonly>
                </div>

                <div class="d-flex gap-3">

                    {{-- COPY --}}
                    <button id="copyBtn"
                            class="btn btn-outline-primary btn-lg rounded-circle action-btn"
                            style="width:56px;height:56px"
                            onclick="copyLink()">
                        <i class="bi bi-clipboard"></i>
                    </button>

                    {{-- DISPLAY --}}
                    <button id="displayBtn"
                            class="btn btn-outline-primary btn-lg rounded-circle action-btn"
                            style="width:56px;height:56px"
                            onclick="openDisplay()">
                        <i class="bi bi-display"></i>
                    </button>

                </div>

                <small class="text-muted d-block mt-3">
                    Salin link atau tampilkan halaman QR ke layar monitor
                </small>

            </div>

        </div>

    </div>
</div>
@endif

{{-- SCRIPT --}}
<script>
    function setActive(buttonId) {
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
        });

        const activeBtn = document.getElementById(buttonId);
        activeBtn.classList.remove('btn-outline-primary');
        activeBtn.classList.add('btn-primary');
    }

    function copyLink() {
        const link = document.getElementById('qrLink').value;
        navigator.clipboard.writeText(link);

        setActive('copyBtn');
        alert('Link QR berhasil disalin');
    }

    function openDisplay() {
        const link = document.getElementById('qrLink').value;
        window.open(link, '_blank');

        setActive('displayBtn');
        alert('QR ditampilkan ke monitor');
    }
</script>

@endsection
