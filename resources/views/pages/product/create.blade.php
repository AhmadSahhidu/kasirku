@extends('component.layout.app')
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Product</h1>
        <a href="{{ route('product.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                class="fas fa-backward fa-sm text-white-50 mr-1"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Input</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.create_product') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input class="form-control" name="name" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="size">Ukuran</label>
                                    <input class="form-control" name="size" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="seri">Seri</label>
                                    <input class="form-control" name="seri" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="satuan">Satuan</label>
                                    <input class="form-control" name="satuan" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control">
                                        <option value="" disabled selected>Pilih Supplier</option>
                                        @foreach ($supplier as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand_id">Merek</label>
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        <option value="" disabled selected>Pilih Merek</option>
                                        <option value="">Tidak Ada</option>
                                        @foreach ($brand as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('brand_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">Kategori Produk</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Kategori Produk</option>
                                        @foreach ($category as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="store_id">Cabang Store</label>
                                    <select name="store_id" id="store_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Cabang Store</option>
                                        @foreach ($store as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('brand_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock">Stok</label>
                                    <input class="form-control" name="stock" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_minimum">Stok Minimum</label>
                                    <input class="form-control" name="stock_minimum" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="purchase_price">Harga Beli</label>
                                    <input class="form-control" name="purchase_price" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selling_price">Harga Jual</label>
                                    <input class="form-control" name="selling_price" required />
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
            $("#store_id").select2();
            $("#supplier_id").select2();
            $("#brand_id").select2();
            $("#category_id").select2();
        });
    </script>
@endpush
