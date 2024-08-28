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
    @include('component.modal.list-product')
    {{-- @include('component.modal.edit-keranjang') --}}
    {{-- @include('component.modal.request-diskon') --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Pembelian</h1>
    </div>
    <div class="row">
        @if (!request('supplier'))
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
                                    @foreach ($supplier as $items)
                                        <option value="{{ $items->id }}">{{ $items->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <hr class="divider" />
                                <div class="text-right">
                                    <button type="button" id="btnSupplierSelect" class="btn btn-sm btn-success"><i
                                            class="fa fa-file mr-2"></i>
                                        Order Pembelian</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (request('supplier'))
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Keranjang {{ $supplier->name }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchase.proses_add_cart_order') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md-7">
                                    <label for="product_id">Produk</label>
                                    <div class="input-group">
                                        <input type="text" id="value-search" name="number"
                                            class="form-control bg-gray-200 border-0 small" aria-label="Search"
                                            aria-describedby="basic-addon2" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" id="btnSearch" type="button">
                                                <i class="fas fa-search fa-sm"></i> Pilih Produk
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="qty">Qty</label>
                                        <input type="number" class="form-control" name="qty" required />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr class="divider" />
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-cash-register mr-2"></i>
                                            Tambah Order</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Order</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $index => $items)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $items->product->name }}</td>
                                        <td>{{ $items->qty }}</td>
                                        <td>{{ rupiahFormat($items->product->purchase_price) }}</td>
                                        <td>{{ rupiahFormat($items->product->purchase_price * $items->qty) }}</td>
                                        <td>

                                            <a href="{{ route('purchase.delete_list_cart_order', $items->id) }}"
                                                class="btn btn-sm btn-circle btn-danger" data-item-id="{{ $items->id }}"
                                                title="Hapus Data"
                                                onclick="return confirmDeletion(event, '{{ $items->id }}');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Total</h6>
                    </div>
                    <div class="card-body">
                        <h3 class="font-weight-bold text-primary text-center">{{ rupiahFormat($total) }}</h3>
                        <p class="text-xs text-center text-gray-600">Total keseluruhan belanja</p>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchase.proses_order') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label>Nama : <b>{{ $supplier->name }}</b></label>
                                <label>No. Hp : <b>{{ $supplier->phone }}</b></label>
                                <label>Alamat : <b>{{ $supplier->address }}</b></label>
                            </div>
                            <hr class="divider" />
                            <h6 class="text-primary font-weight-bold">Pembayaran</h6>
                            <hr class="divider" />
                            <div class="form-group">
                                <label>Jatuh Tempo</label>
                                <input type="date" class="form-control" name="due_date" required />
                                <input type="hidden" name="grand_total" value="{{ $total }}" />
                                <input type="hidden" name="supplier_id" value="{{ request('supplier') }}" />
                            </div>
                            <hr class="divider" />
                            <div class="text-right">
                                <button type="submit" id="proses_transaksi" class="btn btn-sm btn-success"><i
                                        class="fa fa-save mr-2"></i>
                                    Proses Order</button>
                            </div>
                        </form>
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
                document.location.href = '{{ route('purchase.index') }}?supplier=' + supplierId
            });
            $("#btnSearch").on('click', function() {
                $("#listProduct").modal('show');
            });
            $("#btnRequestDiskon").on('click', function() {
                $('#requestDiskon').modal('show');
            })
            $("#closeModal").on('click', function() {
                $("#listProduct").modal('hide');
            });
            $(".btnSelect").on('click', function() {
                var number = $(this).data('item-number');
                $("#value-search").val(number);
                $("#listProduct").modal('hide');
            });

        });

        function confirmDeletion(event, itemId) {
            const confirmed = confirm("Apakah Anda yakin ingin menghapus item ini?");
            if (!confirmed) {
                event.preventDefault();
            }
            return confirmed;
        }
    </script>
@endpush
