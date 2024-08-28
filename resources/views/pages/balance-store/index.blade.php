@php
    $aksesdeposit = validationAkses('input deposit balance customer');
    $aksespengeluaran = validationAkses('input pengeluaran balance customer');
    $roleuser = userRoleName();
@endphp
@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="row">
        @if (request('store') === null || request('store') === '')
            <div class="col-md-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Cabang Store</h1>
                </div>
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Data Store</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($store as $index => $items)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $items->name }}</td>
                                                <td>
                                                    <a href="{{ route('cash.balance_store') }}?store={{ $items->id }}"
                                                        class="btn btn-sm btn-success">Balance</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include('component.modal.option-transaction-balance')
            <div class="col-md-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Balance Store</h1>
                    <div>
                        @if ($aksesdeposit || $roleuser === 'Super Admin')
                            <button type="button" id="btnTransaksi"
                                class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                                    class="fas fa-money-bill fa-sm text-white-50 mr-1"></i> Transaksi</button>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Data Store</h6>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <table>
                                        <tr>
                                            <td style="width: 30%">Nama Store</td>
                                            <td style="width: 15px">:</td>
                                            <td>{{ $store->name }}</td>
                                        </tr>
                                        <tr style="height: 50px">
                                            <td style="width: 30%">Balance Dipegang</td>
                                            <td style="width: 15px">:</td>
                                            <td>{{ rupiahFormat($balance->amount_in_hand) }}</td>
                                        </tr>
                                        <tr style="height: 50px">
                                            <td style="width: 30%">Balance Pada Kasir</td>
                                            <td style="width: 15px">:</td>
                                            <td>{{ rupiahFormat($balance->amount_in_cashier) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%">Balance Deposit Kustomer</td>
                                            <td style="width: 15px">:</td>
                                            <td>{{ rupiahFormat($balance->amount_customer_debt) }}</td>
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
                                    <h2 class="font-weight-bold text-primary text-center">
                                        {{ rupiahFormat($balance?->grand_total) }}
                                    </h2>
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
                                            <th>Tanggal</th>
                                            <th>Nominal</th>
                                            <th>Balance Awal</th>
                                            <th>Balance Akhir</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history as $index => $items)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $items->tgl }}</td>
                                                <td>{{ rupiahFormat($items->amount) }}</td>
                                                <td>{{ rupiahFormat($items->balance_start) }}</td>
                                                <td>{{ rupiahFormat($items->balance_end) }}</td>
                                                <td>
                                                    @if ($items->type === 1)
                                                        <span
                                                            class="text-success text-sm text-center font-weight-bold">Modal</span>
                                                    @elseif ($items->type === 2)
                                                        <span class="text-warning font-weight-bold">Deposit Customer</span>
                                                    @elseif ($items->type === 3)
                                                        <span class="text-danger font-weight-bold">Pengeluaran</span>
                                                    @else
                                                        <span
                                                            class="text-success text-sm text-center font-weight-bold">Penjualan</span>
                                                    @endif
                                                </td>
                                                <td>{{ $items->description }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('script')
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#btnTransaksi').on('click', function() {
                $("#transaksi").modal('show');
            });
            $('#modalClose').on('click', function() {
                $("#transaksi").modal('hide');
            });

            $('#btnProses').on('click', function() {
                var type = $("#type").val(); // Mendapatkan nilai dari elemen dengan id "type"
                var balance = '{{ $balance?->id }}'; // Mengambil nilai store dari variabel Laravel
                window.location.href = 'balance-transaction?balance=' + balance + '&type=' + type;
            })
        })
    </script>
@endpush
