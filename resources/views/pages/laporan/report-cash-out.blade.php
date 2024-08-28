@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Laba Rugi</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" id="start_date" value="{{ request('start_date') }}" name="start_date"
                            class="form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" id="end_date" value="{{ request('end_date') }}" name="start_date"
                            class="form-control" />
                    </div>
                </div>
                @if (userRoleName() === 'Super Admin')
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cabang Store</label>
                            <input type="date" name="store" class="form-control" />
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <hr>
                    <button class="btn btn-success btn-sm" id="btnFilter"><i class="fa fa-search mr-1"></i>
                        Tampilkan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Modal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ rupiahFormat($totalCashIn) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Operasional</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ rupiahFormat($totalCashOut) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Laba Bersih</div>
                            <div
                                class="h5 mb-0 font-weight-bold {{ $totalCashIn - $totalCashOut > 0 ? 'text-success' : 'text-danger' }}">
                                {{ rupiahFormat($totalCashIn - $totalCashOut) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kas Modal</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Number</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashIn as $index => $items)
                            <tr>
                                <td>{{ $index + 1 }}.</td>
                                <td>{{ $items->number }}</td>
                                <td>{{ $items->created_at }}</td>
                                <td>{{ amountFormat($items->amount) }}</td>
                                <td>{{ $items->note ?? '-' }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kas Operasional</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-cashOut" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Number</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Jenis Pengeluaran</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashOut as $index => $items)
                            <tr>
                                <td>{{ $index + 1 }}.</td>
                                <td>{{ $items->number }}</td>
                                <td>{{ $items->created_at }}</td>
                                <td>{{ amountFormat($items->amount) }}</td>
                                <td>
                                    @if ($items->type_cash_out === 1)
                                        <span>Pengeluaran Modal</span>
                                    @elseif ($items->type_cash_out === 2)
                                        <span>Pengeluaran Operasional</span>
                                    @else
                                        <span>Transaksi Lainnya</span>
                                    @endif
                                </td>
                                <td>{{ $items->note ?? '-' }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
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
            $("#dataTable-cashOut").DataTable();
            $("#btnFilter").on('click', function() {
                const start_date = $("#start_date").val();
                const end_date = $("#end_date").val();

                window.location.href = '{{ route('report.report_cash_flow') }}' + '?start_date=' +
                    start_date + "&end_date=" + end_date;
            })
        })
    </script>
@endpush
