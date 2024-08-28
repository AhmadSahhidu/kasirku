@extends('component.layout.app')
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Arus Kas</h1>
        <a href="{{ route('cash.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                class="fas fa-backward fa-sm text-white-50 mr-1"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('cash.update_cash', $cash->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tanggal</label>
                                    <input type="date" class="form-control" value="{{ $cash->tgl }}" name="tgl"
                                        required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nominal</label>
                                    <input class="form-control" value="{{ $cash->amount }}" name="amount" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Jenis Arus Kas</label>
                                    <select name="type_cash" required id="type_cash" class="form-control">
                                        <option value="1" {{ $cash->type_cash === 1 ? 'selected' : '' }}>Pemasukan
                                        </option>
                                        <option value="2" {{ $cash->type_cash === 2 ? 'selected' : '' }}>Pengeluaran
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="tmpCashOut"
                                style="{{ $cash->type_cash === 1 ? 'display:none' : '' }}">
                                <div class="form-group">
                                    <label for="name">Jenis Arus Kas Keluar</label>
                                    <select name="type_cash" id="type_cash_out" class="form-control">
                                        <option value="1" {{ $cash->type_cash_out === 1 ? 'selected' : '' }}>Modal
                                        </option>
                                        <option value="2" {{ $cash->type_cash_out === 1 ? 'selected' : '' }}>
                                            Operasional</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Keterangan</label>
                                    <input class="form-control" name="note" value="{{ $cash->note }}" required />
                                </div>
                            </div>
                            @if (userRoleName() === 'Super Admin')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Cabang Store</label>
                                        <select class="form-control" name="store_id" id="store_id">
                                            <option value="" selected disabled>Pilih Cabang Store</option>
                                            @foreach ($stores as $items)
                                                <option value="{{ $items->id }}"
                                                    {{ $cash->store_id === $items->id ? 'selected' : '' }}>
                                                    {{ $items->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save mr-2"></i>Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#type_cash").on('click', function() {
                if ($(this).val() === '2') {
                    $("#tmpCashOut").show()
                } else {
                    $("#tmpCashOut").hide()
                }
            });

            $("#store_id").select2();
        });
    </script>
@endpush
