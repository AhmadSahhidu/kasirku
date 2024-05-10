@php
    $aksescreateCustomer = validationAkses('create data customer');
    $akseseditCustomer = validationAkses('edit data customer');
    $aksesdeleteCustomer = validationAkses('delete data customer');
    $aksesBalanceCustomer = validationAkses('view balance customer');
    $roleuser = userRoleName();
@endphp
@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Form Pembayaran</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pembayaran</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <table style="width: 100%;">
                        <tr style="height: 45px;border-bottom: 1px solid #ddd">
                            <td class="font-weight-bold">Nomor Penjualan</td>
                            <td>:</td>
                            <td>{{ $salesDebt->sale->number ?? '-' }}</td>
                        </tr>
                        <tr style="height: 45px;border-bottom: 1px solid #ddd">
                            <td class="font-weight-bold">Tanggal Penjualan</td>
                            <td>:</td>
                            <td>{{ $salesDebt->sale->created_at ?? '-' }}</td>
                        </tr>
                        <tr style="height: 45px;border-bottom: 1px solid #ddd">
                            <td class="font-weight-bold">Total Penjualan</td>
                            <td>:</td>
                            <td>{{ $salesDebt->sale->grand_total ?? '-' }}</td>
                        </tr>
                        <tr style="height: 45px;border-bottom: 1px solid #ddd">
                            <td class="font-weight-bold">Nama Pelanggan</td>
                            <td>:</td>
                            <td>{{ $salesDebt->sale->customer->name ?? '-' }}</td>
                        </tr>
                        <tr style="height: 45px;">
                            <td class="font-weight-bold">No. Handphone</td>
                            <td>:</td>
                            <td>{{ $salesDebt->sale->customer->phone ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-xs-12 col-md-6">
                    <table style="width: 100%;">
                        <tr style="height: 45px;border-bottom: 1px solid #ddd">
                            <td class="font-weight-bold">Jatuh Tempo</td>
                            <td>:</td>
                            <td>{{ $salesDebt->due_date ?? '-' }}</td>
                        </tr>
                        <tr style="height: 45px;border-bottom: 1px solid #ddd">
                            <td class="font-weight-bold">Terbayar</td>
                            <td>:</td>
                            <td>{{ rupiahFormat($salesDebt->paid ?? '0') }}</td>
                        </tr>
                        <tr style="height: 45px;border-bottom: 1px solid #ddd">
                            <td class="font-weight-bold">Sisa</td>
                            <td>:</td>
                            <td>{{ rupiahFormat($salesDebt->remaining ?? '0') }}</td>
                        </tr>
                        <tr style="height: 45px;">
                            <td class="font-weight-bold">Status</td>
                            <td>:</td>
                            <td>
                                @if ($salesDebt->status === 1)
                                    <button class="btn btn-warning btn-sm">Belum Lunas</button>
                                @else
                                    <button class="btn btn-success btn-sm">Lunas</button>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pembayaran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('debt_payment.process_payment', $salesDebt->id) }}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Total Yang Harus Dibayar</label>
                            <input class="form-control" readonly name="pay_requied" value="{{ $salesDebt->remaining }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Jenis Pembayaran</label>
                            <select class="form-control" id="payment_method" name="payment_method">
                                <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                <option value="1">Cash</option>
                                <option value="2">Transfer</option>
                                <option value="3">Balance Customer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="tmpPaid" style="display: none">
                        <div class="form-group">
                            <label for="name">Nominal</label>
                            <input class="form-control" name="paid" id="paid" />
                        </div>
                    </div>
                </div>
                <hr />
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-money-bill mr-2"></i>Proses
                    Pembayaran</button>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#payment_method").on('change', function() {
                if ($(this).val() === '3' || $(this).val() === '') {
                    $("#tmpPaid").hide();
                } else {
                    $("#tmpPaid").show();
                }
            })
        })
    </script>
@endpush
