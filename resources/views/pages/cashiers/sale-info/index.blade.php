@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Informasi Transaksi</h1>
        <a href="{{ route('cashier.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                class="fas fa-backward fa-sm text-white-50 mr-1"></i> Reset Halamat</a>
    </div>
    <div class="row">
        <div class="col-md-8">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->items as $index => $items)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $items->product->product->name }}</td>
                                    <td>{{ $items->qty }}</td>
                                    <td>{{ rupiahFormat($items->product->selling_price) }}</td>
                                    <td>{{ rupiahFormat($items->product->selling_price * $items->qty) }}</td>
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
                            <label>Nama Customer</label>
                            <p class="font-weight-bold text-primary">{{ $sale->customer->name ?? 'Umum' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Methode Pembayaran</label>
                            @if ($sale->payment_method === 1)
                                <p class="font-weight-bold text-primary">Cash</p>
                            @elseif ($sale->payment_method === 2)
                                <p class="font-weight-bold text-primary">Transfer</p>
                            @elseif ($sale->payment_method === 3)
                                <p class="font-weight-bold text-primary">Tempo</p>
                            @else
                                <p class="font-weight-bold text-primary">Balance Customer</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Total yang harus dibayar</label>
                            <p class="font-weight-bold text-primary">{{ $total }}</p>

                        </div>
                        @if ($sale->payment_method !== 3)
                            <div class="form-group">
                                <label>Discount</label>
                                <p class="font-weight-bold text-primary">{{ $sale->discount ?? 0 }}</p>

                            </div>
                            <div class="form-group" id="inp-nominal">
                                <label>Nominal Pembayaran</label>
                                <p class="font-weight-bold text-primary">{{ $saleInfo->pay_amount }}</p>
                            </div>
                            <div class="form-group" id="inp-kembalian">
                                <label>Kembalian</label>
                                <p class="font-weight-bold text-primary">{{ $saleInfo->change }}</p>
                            </div>
                        @else
                            <div class="form-group" id="inp-nominal">
                                <label>Jatuh Tempo Pembayaran</label>
                                <p class="font-weight-bold text-primary">{{ $saleInfo->pay_amount }}</p>
                            </div>
                        @endif

                        <hr class="divider" />
                        <div class="text-right">
                            <a href="{{ route('cashier.sale_info_receipf', $sale->id) }}" target="_blank"
                                class="btn btn-sm btn-success"><i class="fa fa-print mr-2"></i>
                                Cetak Resi</a>

                            <a href="{{ route('cashier.sale_info_selling_note', $sale->id) }}" target="_blank"
                                class="btn btn-sm btn-success"><i class="fa fa-print mr-2"></i>
                                Cetak Nota</a>
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

                $("#payment_method").on('change', function() {
                    if ($(this).val() === '3' || $(this).val() === '4') {
                        $("#inp-nominal").hide();
                        $('#inp-kembalian').hide();
                    } else {
                        $("#inp-nominal").show();
                        $('#inp-kembalian').show();
                    }
                });

                $("#nominal").on('keyup', function() {
                    $("#kembalian").val($(this).val() - $('#grand_total').val());
                })
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
                            $("#proses-submit").submit();
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
