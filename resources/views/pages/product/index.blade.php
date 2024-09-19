@php
    $aksescreateProduct = validationAkses('create data product');
    $akseseditProduct = validationAkses('edit data product');
    $aksesdeleteProduct = validationAkses('delete data product');
    $aksesImportProduct = validationAkses('import data product');
    $aksesStockOpname = validationAkses('stock opname product');
    $roleuser = userRoleName();
@endphp
@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    @include('component.modal.import-product')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product</h1>
        <div>
            @if ($aksesImportProduct || $roleuser === 'Super Admin')
                <button type="button" id="btnImport" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                        class="fas fa-upload fa-sm text-white-50 mr-1"></i> Import Product</button>
            @endif
            @if ($aksesStockOpname || $roleuser === 'Super Admin')
                <a href="{{ route('product.stock_opname.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-file fa-sm text-white-50 mr-1"></i> Stock Opname</a>
            @endif
            @if ($aksescreateProduct || $roleuser === 'Super Admin')
                <a href="{{ route('product.input_product') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Data</a>
            @endif
        </div>

    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Product</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Ukuran</th>
                            <th>Seri</th>
                            <th>Merek</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Kategori</th>
                            <th>Supplier</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product as $index => $items)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $items->product->number }}</td>
                                <td>{{ $items->product->name }}</td>
                                <td>{{ $items->product->size }}</td>
                                <td>{{ $items->product->seri }}</td>
                                <td>{{ $items->product->brand->name ?? '-' }}</td>
                                <td>{{ rupiahFormat($items->purchase_price) }}</td>
                                <td>{{ rupiahFormat($items->selling_price) }}</td>
                                <td>{{ $items->stock }}</td>
                                <td>{{ $items->product->category->name ?? '-' }}</td>
                                <td>{{ $items->product->supplier->name ?? '-' }}</td>
                                <td>
                                    @if ($akseseditProduct || $roleuser === 'Super Admin')
                                        <a href="{{ route('product.edit_product', $items->id) }}"
                                            class="btn btn-sm btn-circle btn-primary" title="Edit Data">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    @endif
                                    @if ($aksesdeleteProduct || $roleuser === 'Super Admin')
                                        <button type="button" class="btn btn-sm btn-circle btn-danger btnDelete"
                                            data-item-id="{{ $items->id }}" title="Hapus Data">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.btnDelete').on('click', function() {
                var itemId = $(this).data('item-id');
                Swal.fire({
                    icon: 'question',
                    title: "Apakah anda akan menghapus data ini?",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('product.delete_product') }}",
                            type: 'GET',
                            data: {
                                idProduct: itemId
                            },
                            success: function() {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Anda berhasil menghapus data.",
                                    icon: "success"
                                });
                                window.location.reload();
                            },
                            error: function() {
                                Swal.fire({
                                    title: "Failed!",
                                    text: "Anda kesalahan saat menghapus data.",
                                    icon: "error"
                                });
                            }
                        })
                    }
                });
            });

            $("#btnImport").on('click', function() {
                $("#import-product").modal('show');
            })

            $("#modalClose").on('click', function() {
                $("#import-product").modal('hide');
            })
        })
    </script>
@endpush
