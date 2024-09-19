@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Asset / Kekayaan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
        </div>
        <div class="card-body">
            <div class="row">

                @if (userRoleName() === 'Super Admin')
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cabang Store</label>
                            <select class="form-control" id="store_id" name="store_id">
                                @foreach ($store as $items)
                                    <option value="{{ $items->id }}">{{ $items->name }}</option>
                                @endforeach
                            </select>
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Assets Barang</div>
                            <div class="h5 mb-0 font-weight-bold text-success">{{ rupiahFormat($totalAssets ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Balance Toko</div>
                            <div class="h5 mb-0 font-weight-bold text-success">
                                {{ rupiahFormat(request('store') ? $totalBalance?->amount_in_hand + $totalBalance?->amount_in_cashier : 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Hutang</div>
                            <div class="h5 mb-0 font-weight-bold text-danger">{{ rupiahFormat($cushutang ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Kekayaan</div>
                            <div
                                class="h5 mb-0 font-weight-bold {{ $totalAssets + (request('store') ? $totalBalance?->amount_in_hand + $totalBalance?->amount_in_cashier : 0) - $cushutang > 0 ? 'text-success' : 'text-danger' }}">
                                {{ rupiahFormat($totalAssets + (request('store') ? $totalBalance?->amount_in_hand + $totalBalance?->amount_in_cashier : 0) - $cushutang ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
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
            $("#dataTable-cashOut").DataTable();
            $("#btnFilter").on('click', function() {
                const store_id = $("#store_id").val();

                window.location.href = '{{ route('report.report_assets') }}' + '?store=' +
                    store_id;
            })
        })
    </script>
@endpush
