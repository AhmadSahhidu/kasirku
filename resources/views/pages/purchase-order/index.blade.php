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
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchase.validation_supplier') }}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Supplier</label>
                                        <input type="text" class="form-control" id="supplier_name" name="supplier_name"
                                            autocomplete="off" />
                                        <input type="hidden" class="form-control" name="supplier_id" />
                                        <div class="col-md-12" style="position: relative; width: 100%;">
                                            <ul id="supplier-list" class="dropdown-menu"
                                                style="display:none; position: absolute; z-index: 1000;width: 100%;">
                                                <!-- Pilihan dari AJAX akan ditampilkan di sini -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>No. Handphone</label>
                                        <input type="text" class="form-control" name="supplier_phone" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <input type="text" class="form-control" name="supplier_address" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr class="divider" />
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-file mr-2"></i>
                                            Order Pembelian</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Produk</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name"
                                            autocomplete="off" />
                                        <input type="hidden" class="form-control" name="product_id" />
                                        <div class="col-md-12" style="position: relative; width: 100%;">
                                            <ul id="product-list" class="dropdown-menu"
                                                style="display:none; position: absolute; z-index: 1000;width: 100%;">
                                                <!-- Pilihan dari AJAX akan ditampilkan di sini -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Ukuran</label>
                                        <input type="text" class="form-control" id="size" name="size" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Kategori Produk</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name"
                                            autocomplete="off" />
                                        <input type="hidden" class="form-control" name="category_id" />
                                        <div class="col-md-12" style="position: relative; width: 100%;">
                                            <ul id="category-list" class="dropdown-menu"
                                                style="display:none; position: absolute; z-index: 1000;width: 100%;">
                                                <!-- Pilihan dari AJAX akan ditampilkan di sini -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Harga Pembelian</label>
                                        <input type="text" class="form-control" id="purchase_price"
                                            name="purchase_price" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Harga Jual</label>
                                        <input type="text" class="form-control" id="selling_price"
                                            name="selling_price" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Qty</label>
                                        <input type="text" class="form-control" id="qty" name="qty" />
                                        <input type="hidden" value="{{ request('supplier') }}" class="form-control"
                                            id="supplierId" name="supplierId" />
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
                                        <td>{{ $items->name_product }}</td>
                                        <td>{{ $items->qty }}</td>
                                        <td>{{ rupiahFormat($items->purchase_price) }}</td>
                                        <td>{{ rupiahFormat($items->purchase_price * $items->qty) }}</td>
                                        <td>

                                            <a href="{{ route('purchase.delete_list_cart_order', $items->id) }}"
                                                class="btn btn-sm btn-circle btn-danger"
                                                data-item-id="{{ $items->id }}" title="Hapus Data"
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
                                <label>Jenis Pembayaran</label>
                                <select class="form-control" id="payment_method" name="payment_method">
                                    <option value="cash">Cash</option>
                                    <option value="tempo">Tempo</option>
                                </select>
                                <input type="hidden" name="total" value="{{ $total }}" />
                                <div id="tmp-balance" class="mt-2">
                                    <label>Sumber Dana</label>
                                    <select class="form-control" id="sumber_dana" name="sumber_dana">
                                        <option value="cashier">Saldo kasir -
                                            {{ rupiahFormat($balance->amount_in_cashier) }}</option>
                                        <option value="hand">Saldo ATM / Sendiri -
                                            {{ rupiahFormat($balance->amount_in_hand) }}</option>
                                    </select>
                                </div>
                                <div id="tmp-due-date" style="display: none;" class="mt-2">
                                    <label>Jatuh Tempo</label>
                                    <input type="date" class="form-control" name="due_date" />
                                </div>
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
            $("#supplier_name").on('keyup', function() {
                var supplier_name = $(this).val();

                if (supplier_name.length >= 2) { // Cari saat input lebih dari 2 karakter
                    $.ajax({
                        url: "{{ route('debt.search_supplier') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            supplier_name: supplier_name,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            var supplierList = $("#supplier-list");
                            supplierList.empty().hide(); // Kosongkan dan sembunyikan dropdown

                            if (res.data.length > 0) {
                                // Iterasi data dan buat item dropdown
                                res.data.forEach(function(supplier) {
                                    supplierList.append(
                                        '<a class="dropdown-item" href="#" data-id="' +
                                        supplier.id +
                                        '" data-phone="' + supplier.phone +
                                        '" data-address="' + supplier.address +
                                        '"><li class="fa fa-circle fa-xs mr-2"></li>' +
                                        supplier.name +
                                        '</a>');
                                });
                                supplierList.show(); // Tampilkan dropdown
                            }
                        },
                        error: function() {
                            console.log("Error fetching supplier data.");
                        }
                    });
                } else {
                    $("#supplier-list").empty()
                        .hide(); // Kosongkan dan sembunyikan dropdown jika input terlalu pendek
                    $('input[name="supplier_id"]').val(''); // Kosongkan supplier_id jika input dihapus
                    $('input[name="supplier_address"]').val(''); // Kosongkan supplier_id jika input dihapus
                    $('input[name="supplier_phone"]').val(''); // Kosongkan supplier_id jika input dihapus
                }
            });

            $(document).on('click', '#supplier-list .dropdown-item', function(e) {
                e.preventDefault();

                var selectedSupplierName = $(this).text();
                var selectedSupplierId = $(this).data('id');
                var selectedSupplierPhone = $(this).data('phone');
                var selectedSupplierAddress = $(this).data('address');

                $('#supplier_name').val(selectedSupplierName); // Isi nilai input dengan nama yang dipilih
                $('input[name="supplier_id"]').val(selectedSupplierId);
                $('input[name="supplier_phone"]').val(selectedSupplierPhone);
                $('input[name="supplier_address"]').val(selectedSupplierAddress);

                $('#supplier-list').empty().hide(); // Sembunyikan dropdown setelah memilih
            });

            $("#product_name").on('keyup', function() {
                var product_name = $(this).val();

                if (product_name.length > 2) {
                    $.ajax({
                        url: "{{ route('product.search_product') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            product_name: product_name,
                            supplier: "{{ request('supplier') }}",
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            var productList = $("#product-list");
                            productList.empty().hide(); // Kosongkan dan sembunyikan dropdown

                            if (res.data.length > 0) {
                                // Iterasi data dan buat item dropdown
                                res.data.forEach(function(product) {
                                    productList.append(
                                        '<a class="dropdown-item" href="#" data-id="' +
                                        product.id +
                                        '" data-size="' +
                                        product.size +
                                        '" data-categoryname="' +
                                        product.category.name +
                                        '" data-categoryid="' +
                                        product.category_id +
                                        '"><li class="fa fa-circle fa-xs mr-2"></li>' +
                                        product.name + '</a>');
                                });
                                productList.show(); // Tampilkan dropdown
                            }
                        },
                        error: function() {
                            console.log("Error fetching supplier data.");
                        }
                    });
                } else {
                    $("#product-list").empty().hide();
                    $('input[name="product_id"]').val('');
                    $('input[name="category_name"]').val('');
                    $('input[name="category_id"]').val('');
                    $('input[name="size"]').val('');
                }
            });
            $(document).on('click', '#product-list .dropdown-item', function(e) {
                e.preventDefault();

                var selectedSupplierName = $(this).text();
                var selectedSupplierId = $(this).data('id');
                var selectedSize = $(this).data('size');
                var categoryName = $(this).data('categoryname');
                var categoryId = $(this).data('categoryid');

                $('#product_name').val(selectedSupplierName); // Isi nilai input dengan nama yang dipilih
                $('input[name="product_id"]').val(selectedSupplierId);
                $('input[name="category_name"]').val(categoryName);
                $('input[name="category_id"]').val(categoryId);
                $('input[name="size"]').val(selectedSize);

                $('#product-list').empty().hide(); // Sembunyikan dropdown setelah memilih
            });

            $("#category_name").on('keyup', function() {
                var category_name = $(this).val();

                if (category_name.length > 2) {
                    $.ajax({
                        url: "{{ route('product.search_category') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            category_name: category_name,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            var categoryList = $("#category-list");
                            categoryList.empty().hide(); // Kosongkan dan sembunyikan dropdown

                            if (res.data.length > 0) {
                                // Iterasi data dan buat item dropdown
                                res.data.forEach(function(category) {
                                    categoryList.append(
                                        '<a class="dropdown-item" href="#" data-id="' +
                                        category.id +
                                        '"><li class="fa fa-circle fa-xs mr-2"></li>' +
                                        category.name +
                                        '</a>');
                                });
                                categoryList.show(); // Tampilkan dropdown
                            }
                        },
                        error: function() {
                            console.log("Error fetching supplier data.");
                        }
                    });
                } else {
                    $("#category-list").empty().hide();
                }
            });
            $(document).on('click', '#category-list .dropdown-item', function(e) {
                e.preventDefault();

                var selectedName = $(this).text();
                var selectedId = $(this).data('id');

                $('#category_name').val(selectedName); // Isi nilai input dengan nama yang dipilih
                $('input[name="category_id"]').val(selectedId);

                $('#category-list').empty().hide(); // Sembunyikan dropdown setelah memilih
            });

            $("#payment_method").on('change', function() {
                var payment = $(this).val();
                if (payment === 'cash') {
                    $("#tmp-due-date").hide();
                    $("#tmp-balance").show();
                } else {
                    $("#tmp-balance").hide();
                    $("#tmp-due-date").show();
                }
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
