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
        <h1 class="h3 mb-0 text-gray-800">Detail Order Pembelian <b class="text-primary">#{{ $purchaseOrder->number }}</b>
        </h1>
    </div>
    <div class="row">

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Order Pembelian</h6>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <p>No. Pembelian : <b>{{ $purchaseOrder->number }}</b></p>
                        <p>Total Pembelian :
                            <b>{{ rupiahFormat($purchaseOrder->grand_total) }}</b>
                        </p>
                        <p>Jatuh Tempo : <b>{{ $purchaseOrder->due_date }}</b></p>
                        <p>Status : <b
                                class="{{ statusPurchaseOrder($purchaseOrder->status) === 'Lunas' ? 'text-success' : 'text-danger' }}">{{ statusPurchaseOrder($purchaseOrder->status) }}</b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <p>Nama : <b>{{ $supplier->name }}</b></p>
                        <p>No. Hp : <b>{{ $supplier->phone }}</b></p>
                        <p>Alamat : <b>{{ $supplier->address }}</b></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List Item Pembelian</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Harga Pembelian</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrderItems as $index => $items)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $items->product->name }}</td>
                                        <td>{{ $items->qty }}</td>
                                        <td>{{ rupiahFormat($items->price_buy) }}</td>
                                        <td>{{ rupiahFormat($items->grand_total) }}</td>
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
