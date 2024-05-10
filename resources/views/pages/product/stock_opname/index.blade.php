@php
    $aksesStockOpname = validationAkses('stock opname product');
    $roleuser = userRoleName();
@endphp
@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Stok Opname</h1>
        <div>
            @if ($aksesStockOpname || $roleuser === 'Super Admin')
                <a href="{{ route('product.stock_opname.create_stock_opname') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50 mr-1"></i> Lakukan Stok Opname</a>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Stok Opname</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Number</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Stok Sebelum</th>
                            <th>Quantity</th>
                            <th>Stok Sesudah</th>
                            <th>Alasan</th>
                            <th>User</th>
                            <th>Cabang Store</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stock as $index => $items)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $items->number }}</td>
                                <td>{{ $items->created_at }}</td>
                                <td>{{ $items->product->name ?? '-' }}</td>
                                <td>
                                    @if ($items->type === 1)
                                        <span class="text-primary">Penambahan</span>
                                    @else
                                        <span class="text-danger">Pengurangan</span>
                                    @endif
                                </td>
                                <td>{{ $items->stock_before }}</td>
                                <td>{{ $items->qty ?? '-' }}</td>
                                <td>{{ $items->stock_after }}</td>
                                <td>{{ $items->note ?? '-' }}</td>
                                <td>{{ $items->user->name ?? '-' }}</td>
                                <td>{{ $items->store->name ?? '-' }}</td>
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
            })
        })
    </script>
@endpush
