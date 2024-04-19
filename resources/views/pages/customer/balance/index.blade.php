@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    @include('component.modal.deposit')
    @include('component.modal.pengeluaran')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Balance Customer</h1>
        <div>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" id="btnPengeluaran"><i
                    class="fas fa-money-bill fa-sm text-white-50 mr-1"></i> Pengeluaran</button>
            <button type="button" id="btnDeposit" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-money-bill fa-sm text-white-50 mr-1"></i> Deposit</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Customer</h6>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <table>
                            <tr>
                                <td style="width: 30%">Nama Customer</td>
                                <td style="width: 15px">:</td>
                                <td>{{ $balance->customer->name }}</td>
                            </tr>
                            <tr>
                                <td>No. Handphone</td>
                                <td>:</td>
                                <td>{{ $balance->customer->phone }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $balance->customer->address }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Balance</h6>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <h2 class="font-weight-bold text-primary text-center">{{ rupiahFormat($balance->nominal) }}</h2>
                        <p class="text-xs text-center text-gray-600">Nominal yang tersedia</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Balance</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Number</th>
                                <th>Nominal</th>
                                <th>Balance Awal</th>
                                <th>Balance Akhir</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($balance->historyBalance as $index => $items)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $items->number }}</td>
                                    <td>{{ rupiahFormat($items->nominal) }}</td>
                                    <td>{{ rupiahFormat($items->start_balance) }}</td>
                                    <td>{{ rupiahFormat($items->end_balance) }}</td>
                                    <td>
                                        @if ($items->status === '1')
                                            <span class="text-success text-sm text-center font-weight-bold">Deposit</span>
                                        @else
                                            <span class="text-danger font-weight-bold">Pengurangan</span>
                                        @endif
                                    </td>
                                    <td>{{ $items->note }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
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
            $('#btnDeposit').on('click', function() {
                $("#deposit").modal('show');
            });
            $('#btnPengeluaran').on('click', function() {
                $("#pengeluaran").modal('show');
            });
            $('#modalClose').on('click', function() {
                $("#deposit").modal('hide');
            })
            $('#modalClosePengeluaran').on('click', function() {
                $("#pengeluaran").modal('hide');
            })
        })
    </script>
@endpush
