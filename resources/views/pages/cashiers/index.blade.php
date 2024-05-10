@php
    $aksesKasir = validationAkses('kasir');
    $roleuser = userRoleName();
    if (!$aksesKasir || $roleuser !== 'Super Admin') {
        return redirect()->route('dashboard');
    }
@endphp
@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    @include('component.modal.list-product')
    @include('component.modal.edit-keranjang')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kasir</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Keranjang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('cashier.store_cart') }}" method="POST">
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
                                        Tambah Keranjang</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List Keranjang</h6>
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
                                    <td>{{ rupiahFormat($items->product->selling_price) }}</td>
                                    <td>{{ rupiahFormat($items->product->selling_price * $items->qty) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-primary btn-edit-cart"
                                            data-item-id="{{ $items->id }}" data-item-qty="{{ $items->qty }}"
                                            title="Edit Data">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-circle btn-danger btnDelete btn-delete-cart"
                                            data-item-id="{{ $items->id }}" title="Hapus Data">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
                    <h6 class="m-0 font-weight-bold text-primary">Pembayaran</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('cashier.transaction_cashier') }}" method="POST" id="proses-submit">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label>Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value="" disabled selected></option>
                                <option value="">Tidak Ada</option>
                                @foreach ($customer as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} - {{ $item->phone }} - {{ $item->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Methode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="1">Cash</option>
                                <option value="2">Transfer</option>
                                <option value="3">Tempo</option>
                                <option value="4">Balance Customer</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Total yang harus dibayar</label>
                            <input type="text" class="form-control" name="grand_total" id="grand_total"
                                value="{{ $total }}" readonly />
                        </div>
                        <div class="form-group" id="inp-nominal">
                            <label>Nominal</label>
                            <input type="text" class="form-control" id="nominal" name="nominal" required />
                        </div>
                        <div class="form-group" id="inp-due-date">
                            <label>Jatuh Tempo</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required />
                        </div>
                        <div class="form-group" id="inp-kembalian">
                            <label>Kembalian</label>
                            <input type="text" class="form-control" id="kembalian" name="kembalian" readonly />
                        </div>
                        <hr class="divider" />
                        <div class="text-right">
                            <button type="button" id="proses_transaksi" class="btn btn-sm btn-success"><i
                                    class="fa fa-save mr-2"></i>
                                Proses Transaksi</button>
                        </div>
                    </form>
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
                $("#btnSearch").on('click', function() {
                    $("#listProduct").modal('show');
                });
                $("#closeModal").on('click', function() {
                    $("#listProduct").modal('hide');
                });
                $(".btnSelect").on('click', function() {
                    var number = $(this).data('item-number');
                    $("#value-search").val(number);
                    $("#listProduct").modal('hide');
                });

                $("#customer_id").select2();
                $(".btn-edit-cart").on('click', function() {
                    var itemsId = $(this).data('item-id');
                    var itemsQty = $(this).data('item-qty');
                    $('#edit_qty').val(itemsQty);
                    $('#cart_id').val(itemsId);
                    $("#edit-qty").modal('show');
                });

                $('#inp-due-date').hide();
                $("#payment_method").on('change', function() {
                    if ($(this).val() === '3') {
                        $("#inp-nominal").hide();
                        $('#inp-kembalian').hide();
                        $('#inp-due-date').show();
                    } else if ($(this).val() === '4') {
                        $("#inp-nominal").hide();
                        $('#inp-kembalian').hide();
                        $('#inp-due-date').hide();
                    } else {
                        $("#inp-nominal").show();
                        $('#inp-kembalian').show();
                        $('#inp-due-date').hide();
                    }
                });

                $("#nominal").on('keyup', function() {
                    $("#kembalian").val($(this).val() - $('#grand_total').val());
                });

                $(".btnDelete").on('click', function() {
                    var idItems = $(this).data('item-id');

                    Swal.fire({
                        icon: 'question',
                        title: "Apakah anda akan menghapus data ini?",
                        showCancelButton: true,
                        confirmButtonText: "Ya",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('cashier.delete_cart') }}",
                                type: 'GET',
                                data: {
                                    cart_id: idItems
                                },
                                success: function(res) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: res.message,
                                        icon: "success"
                                    });
                                    window.location.reload();
                                },
                                error: function(err) {
                                    Swal.fire({
                                        title: "Failed!",
                                        text: err.message,
                                        icon: "error"
                                    });
                                }
                            })
                        }
                    });
                });
            })
            $(document).ready(function() {
                $("#proses_transaksi").on('click', function() {
                    var payment_mehthod = $("#payment_method").val();
                    var customer = $("#customer_id").val();
                    if (payment_mehthod === '3' || payment_mehthod === '4') {
                        if (customer === null || customer === '') {
                            Swal.fire({
                                title: "Gagal!",
                                text: "Customer tidak boleh kosong.",
                                icon: "error"
                            });
                        } else {
                            if (payment_mehthod === '3' && $("#due_date").val() === '') {
                                Swal.fire({
                                    title: "Informasi!",
                                    text: "Silahkan masukkan tanggal jatuh tempo.",
                                    icon: "info"
                                });
                            } else {
                                $("#proses-submit").submit();
                            }
                        }
                    } else {
                        if ($("#nominal").val() < $("#grand_total").val()) {
                            Swal.fire({
                                title: "Informasi!",
                                text: "Pembayaran transaksi anda kurang.",
                                icon: "info"
                            });
                        } else {
                            $("#proses-submit").submit();
                        }

                    }
                });
            })
        </script>
    @endpush
