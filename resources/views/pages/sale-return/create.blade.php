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
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
                </div>
                <div class="card-body">
                    @if (!request('sale_id'))
                        <div class="row">
                            <div class="col-md-12">
                                <label for="sale_id">Pilih Nota</label>
                                <select name="sale_id" id="sale_id" class="form-control">
                                    <option value="" disabled selected>Search nota...</option>
                                    @foreach ($sale as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->number }} - {{ $item->customer->name ?? 'Umum' }} -
                                            {{ $item->created_at }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <hr class="divider" />
                                <div class="text-right">
                                    <button type="button" id="btn-show" class="btn btn-sm btn-success"><i
                                            class="fa fa-search mr-2"></i>
                                        Tampilkan</button>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (request('sale_id'))
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">No Invoice</label>
                                    <p class="font-weight-bold">{{ $sale->number }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_id">Tanggal</label>
                                    <p class="font-weight-bold">{{ $sale->created_at }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_id">Jenis Pembayaran</label>
                                    <p class="font-weight-bold">{{ paymentMethod($sale->payment_method) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_id">Total Belanja</label>
                                    <p class="font-weight-bold">{{ rupiahFormat($sale->grand_total) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        @if (request('sale_id'))
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Customer</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Customer</label>
                                    <p class="font-weight-bold">{{ $sale->customer->name ?? 'Umum' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">No. Handphone Customer</label>
                                    <p class="font-weight-bold">{{ $sale->customer->phone ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Alamat Customer</label>
                                    <p class="font-weight-bold">{{ $sale->customer->address ?? '-' }}</p>
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
                        <form action="{{ route('sale_returns.proses_sale_return') }}" method="POST">
                            @csrf
                            @method('POST')
                            <input hidden name="sale_id" value="{{ $sale->id }}" />
                            <table class="table table-bordered">
                                <thead class="table-info">
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Jumlah Beli</th>
                                        <th>Jumlah Barang Kembali</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->items as $item)
                                        <input type="hidden" name="sale_item_id[]" value="{{ $item->id }}">
                                        <tr>
                                            <td>
                                                <h5>{{ $item->product->name ?? '-' }}</h5>
                                            </td>
                                            <td>
                                                <h5>{{ $item->qty ?? 0 }}</h5>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    data-item-id="{{ $item->id }}"
                                                    name="quantity[{{ $item->id }}]" value="0" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <hr class="divider" />
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-save mr-2"></i>
                                            Proses Transaksi</button>
                                    </div>
                                </div>
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
