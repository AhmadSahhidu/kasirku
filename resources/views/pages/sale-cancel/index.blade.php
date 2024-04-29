@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Batalkan Transaksi</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi</h6>
                </div>
                <div class="card-body">
                    @if (!request('sale_id'))
                        <div class="row">
                            <div class="col-md-12">
                                <label for="sale_id">Pilih Nota</label>
                                <select name="sale_id" id="sale_id" class="form-control">
                                    <option value="" disabled selected>Search nota...</option>
                                    @foreach ($sales as $item)
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
                        <form action="{{ route('sale_cancel.cancel_transaction', request('sale_id')) }}"
                            id="sale-cancel-form" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">No Invoice</label>
                                        <input class="form-control" value="{{ $sales->number }}" name="name" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="store_id">Tanggal</label>
                                        <input class="form-control" value="{{ $sales->created_at }}" name="name"
                                            readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Customer</label>
                                        <input class="form-control" value="{{ $sales->customer->name ?? 'umum' }}"
                                            name="name" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="store_id">Jenis Pembayaran</label>
                                        <input class="form-control" value="{{ paymentMethod($sales->payment_method) }}"
                                            name="name" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="store_id">Total</label>
                                        <input class="form-control" value="{{ rupiahFormat($sales->grand_total) }}"
                                            name="name" readonly />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr class="divider" />
                                    <div class="text-right">
                                        <button type="button" data-id="{{ request('sale_id') }}" id="btn-delete"
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash mr-2"></i>
                                            Batalkan Transaksi</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    @endif
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
            window.location = `{{ route('sale_cancel.index') }}` + `?sale_id=` + $("#sale_id").val();
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
