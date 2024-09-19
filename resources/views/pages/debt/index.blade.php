@php
    // $aksescreateProduct = validationAkses('create data product');
    // $akseseditProduct = validationAkses('edit data product');
    // $aksesdeleteProduct = validationAkses('delete data product');
    // $aksesImportProduct = validationAkses('import data product');
    // $aksesStockOpname = validationAkses('stock opname product');
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
        <h1 class="h3 mb-0 text-gray-800">List Hutang</h1>
        <div>
            {{-- @if ($aksescreateProduct || $roleuser === 'Super Admin') --}}
            <a href="{{ route('debt.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50 mr-1"></i> Input Hutang</a>
            {{-- @endif --}}
        </div>

    </div>
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Keseluruhan Hutang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ rupiahFormat($total) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Hutang Dibayar</div>
                            <div class="h5 mb-0 font-weight-bold text-success">{{ rupiahFormat($paidDebt) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Sisa Hutang</div>
                            <div class="h5 mb-0 font-weight-bold text-danger">{{ rupiahFormat($remainingDebt) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Hutang</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Number</th>
                                    <th>Name Supplier</th>
                                    <th>Total</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($flowDebt as $index => $items)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $items->number }}</td>
                                        <td>{{ $items->supplier->name }}</td>
                                        <td>{{ rupiahFormat($items->amount) }}</td>
                                        <td>{{ $items->payment_method === 1 ? 'Cash' : 'Tempo' }}</td>
                                        <td>{{ $items->due_date ?? '-' }}</td>
                                        <td>{{ $items->status }}</td>
                                        <td>
                                            {{-- @if ($akseseditProduct || $roleuser === 'Super Admin') --}}
                                            <a href="{{ route('debt.detail_debt', $items->id) }}"
                                                class="btn btn-sm  btn-success">
                                                <i class="fa fa-file mr-2"></i> Detail
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
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
