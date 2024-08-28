@php
    $aksesKasir = validationAkses('Kasir');
    $roleuser = userRoleName();
@endphp
@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Pembelian</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="product_id">Pilih Supplier</label>
                            <select class="form-control" id="supplier_id">
                                <option selected disabled> -- </option>
                                @foreach ($suppliers as $items)
                                    <option @if ($items->id === request('supplier')) selected @endif value="{{ $items->id }}">
                                        {{ $items->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <hr class="divider" />
                            <div class="text-right">
                                <button type="button" id="btnSupplierSelect" class="btn btn-sm btn-success"><i
                                        class="fa fa-search mr-2"></i>
                                    Pencarian</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (request('supplier'))
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Order Pembelian {{ $supplier->name }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Number</th>
                                        <th>Supplier</th>
                                        <th>Total</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrder as $index => $items)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $items->number }}</td>
                                            <td>{{ $items->supplier->name }}</td>
                                            <td>{{ rupiahFormat($items->grand_total) }}</td>
                                            <td>{{ $items->due_date }}</td>
                                            <td><b
                                                    class="{{ statusPurchaseOrder($items->status) === 'Lunas' ? 'text-success' : 'text-danger' }}">{{ statusPurchaseOrder($items->status) }}</b>
                                            </td>
                                            <td>
                                                <a href="{{ route('purchase.detail_order_pembelian', $items->id) }}"
                                                    class="btn btn-sm btn-primary"><i class="fa fa-eye mr-2"></i>Detail</a>
                                                @if (statusPurchaseOrder($items->status) === 'Belum lunas')
                                                    <a href="{{ route('purchase.konfirmasi_pembayaran_order_pembelian', $items->id) }}"
                                                        class="btn btn-sm btn-success"><i
                                                            class="fa fa-money-bill mr-2"></i>Pembayaran</a>
                                                @endif

                                                <a href="" class="btn btn-sm btn-warning"><i
                                                        class="fa fa-print mr-2"></i>Cetak</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('script')
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#supplier_id").select2();
            $('#btnSupplierSelect').on('click', function() {
                var supplierId = $("#supplier_id").val();
                document.location.href = '{{ route('purchase.list_order_pembelian') }}?supplier=' +
                    supplierId
            });
        });
    </script>
@endpush
