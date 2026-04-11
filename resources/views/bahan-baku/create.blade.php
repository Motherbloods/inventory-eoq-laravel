@extends('layouts.app')
@section('title', 'Tambah Bahan Baku')
@section('page-title', 'Tambah Bahan Baku')
@section('page-subtitle', 'Data Master → Bahan Baku')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Form Tambah Bahan Baku</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('bahan-baku.store') }}" method="POST">
                        @csrf
                        @include('bahan-baku._form')
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary text-white"><i
                                    class="bi bi-save me-1"></i>Simpan</button>
                            <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
