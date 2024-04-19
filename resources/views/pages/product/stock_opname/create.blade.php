@extends('component.layout.app')
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Stock Opname</h1>
        <a href="{{ route('product.stock_opname.index') }}"
            class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                class="fas fa-backward fa-sm text-white-50 mr-1"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Stock Opname</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.stock_opname.store_stock_opname') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product_id">Search Product</label>
                                    <select name="product_id" id="product_id" class="form-control" required>
                                        <option value="" disabled selected>Cari Product</option>
                                        @foreach ($product as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }} {{ $item->size }} {{ $item->brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input class="form-control" name="qty" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Jenis Stock Opname</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="1">Penambahan</option>
                                        <option value="2">Pengurangan</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">Keterangan</label>
                                    <input class="form-control" name="note" />
                                    <p class="text-xs text-gray">Note: Kosongkan jika tidak ada keterangan</p>
                                </div>
                            </div>
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
            $("#product_id").select2();
        });
    </script>
@endpush
