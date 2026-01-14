@extends('layouts.app')

@section('title', 'Form Buku Tamu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h4 class="text-center mb-4 text-primary">Form Buku Tamu</h4>

            <form method="POST" action="#">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama">
                </div>

                <div class="mb-3">
                    <label class="form-label">Instansi</label>
                    <input type="text" class="form-control" name="instansi">
                </div>

                <div class="mb-3">
                    <label class="form-label">Keperluan</label>
                    <textarea class="form-control" name="keperluan"></textarea>
                </div>

                <button class="btn btn-primary w-100">Kirim</button>
            </form>
        </div>
    </div>
</div>
@endsection
