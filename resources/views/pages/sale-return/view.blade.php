@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaksi Kembali</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Transaksi Pengembalian</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Customer</label>
                                            <p class="font-weight-bold">{{ $sale_return->sales->customer->name ?? 'Umum' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Phone</label>
                                            <p class="font-weight-bold">{{ $sale_return->sales->customer->phone ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Tanggal Penjualan</label>
                                            <p class="font-weight-bold">{{ $sale_return->sales->created_at ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">No Penjualan</label>
                                            <p class="font-weight-bold">{{ $sale_return->sales->number ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Method Pembayaran</label>
                                            <p class="font-weight-bold">
                                                {{ paymentMethod($sale_return->sales->payment_method ?? 1) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Total Belanja</label>
                                            <p class="font-weight-bold">
                                                {{ rupiahFormat($sale_return->sales->grand_total ?? '0') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Tanggal Pengembalian</label>
                                            <p class="font-weight-bold">{{ $sale_return->created_at }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Nomor Pengembalian</label>
                                            <p class="font-weight-bold">{{ $sale_return->number ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Status</label>
                                            <p class="font-weight-bold">{{ statusReturn($sale_return->status) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">User</label>
                                            <p class="font-weight-bold">{{ $sale_return->user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Total Retur</label>
                                            <p class="font-weight-bold">{{ rupiahFormat($sale_return->total) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Item Transaksi</h6>
                </div>
                <div class="card-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($sale_return->returnItems as $index => $item)
                                <input type="hidden" name="sale_item_id[]" value="{{ $item->id }}">
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $item->items->product->product->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $item->qty ?? 0 }}
                                    </td>
                                    <td>
                                        {{ amountFormat($item->items->product->selling_price) ?? 0 }}
                                    </td>
                                    <td>
                                        {{ amountFormat($item->total) ?? 0 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($sale_return->status === 1)
                        <form action="{{ route('sale_returns.verify_sale_return') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Konfimasi Pengembalian</label>
                                        <select class="form-control" name="status">
                                            <option value="2">Setuju</option>
                                            <option value="3">Tolak</option>
                                        </select>
                                        <input type="hidden" name="return_id" value="{{ $sale_return->id }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <textarea class="form-control" name="note"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr class="divider" />
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-save mr-2"></i>
                                            Konfirmasi</button>
                                    </div>
                                </div>
                            </div>
                    @endif
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
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#sale_id").select2();
        });

        $('#btn-show').on('click', function() {
            window.location = `{{ route('sale_returns.create_sale_return') }}` + `?sale_id=` + $("#sale_id").val();
        });
        $('#btn-delete').on('click', function() {
            var dataId = $(this).data('id');
            Swal.fire({
                icon: 'question',
                title: "Apakah anda yakin ingin membatalkan transaksi ini? Data akan menghilang secara permanent",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#sale-cancel-form").submit();
                }
            });
        })
    </script>
@endpush
