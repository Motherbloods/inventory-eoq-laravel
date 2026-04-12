@extends('layouts.app')
@section('title', 'Edit Pemasok')
@section('page-title', 'Edit Pemasok')
@section('page-subtitle', 'Data Master → Pemasok')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><span class="card-title">Edit: {{ $pemasok->nama_pemasok }}</span></div>
                <div class="card-body">
                    <form action="{{ route('pemasok.update', $pemasok) }}" method="POST">
                        @csrf @method('PUT')
                        @include('pemasok._form')
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary text-white"><i
                                    class="bi bi-save me-1"></i>Perbarui</button>
                            <a href="{{ route('pemasok.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
