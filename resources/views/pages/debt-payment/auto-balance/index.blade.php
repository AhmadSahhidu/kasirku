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
        <h1 class="h3 mb-0 text-gray-800">Auto Balance Customer</h1>
    </div>

    @if (!request('customer_id'))
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pelanggan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="customer_id">Data Pelanggan</label>
                                    <select name="customer_id" id="customer_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Pelanggan</option>
                                        @foreach ($customer as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" id="btnShow" class="btn btn-success btn-sm"><i
                                        class="fa fa-search mr-2"></i>Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (request('customer_id'))
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
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Transaksi Jatuh Tempo</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Nomor Penjualan</th>
                                        <th>Tanggal</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesDebt as $index => $items)
                                        <tr>
                                            <td>{{ $items->sale->customer->name ?? 'Umum' }}</td>
                                            <td>{{ $items->sale->number }}</td>
                                            <td>{{ $items->sale->created_at }}</td>
                                            <td>{{ $items->due_date }}</td>
                                            <td>{{ amountFormat($items->sale->grand_total) }}</td>
                                            <td>{{ statusDebt($items->status) }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary mb-3">Data Auto Balance</h6>
                        <span class="mt-5 text-sm">Dibawah ini adalah data tagihan yang bisa dibayar dengan balance yang
                            dipunyai customer</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Nomor Penjualan</th>
                                        <th>Tanggal</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataAutoBalance as $index => $items)
                                        <tr>
                                            <td>{{ $items->sale->customer->name ?? 'Umum' }}</td>
                                            <td>{{ $items->sale->number }}</td>
                                            <td>{{ $items->sale->created_at }}</td>
                                            <td>{{ $items->due_date }}</td>
                                            <td>{{ amountFormat($items->sale->grand_total) }}</td>
                                            <td>{{ statusDebt($items->status) }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <hr />
                            @if ($dataAutoBalance)
                                <div class="text-right">
                                    <button type="button" id="btnAutoBalance" data-customer="{{ request('customer_id') }}"
                                        class="btn btn-sm btn-success"><i class="fa fa-money-bill mr-2"></i>
                                        Auto Balance Tagihan</button>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
            $("#customer_id").select2();
            $("#btnShow").on('click', function() {
                const customer = $("#customer_id").val();

                window.location.href = '{{ route('debt_payment.auto_balance_customer') }}' +
                    '?customer_id=' + customer
            });

            $("#btnAutoBalance").on('click', function() {
                const customerId = $(this).data('customer');
                Swal.fire({
                    icon: 'question',
                    title: "Apakah anda ingin membayar tagihan ini dengan balance customer?",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('debt_payment.proses_auto_balance_customer') }}",
                            type: 'GET',
                            data: {
                                idCustomer: customerId
                            },
                            success: function(res) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Anda berhasil melakukan auto balance tagihan.",
                                    icon: "success"
                                });
                                // window.location.reload();
                                console.log(res);
                            },
                            error: function(res) {
                                Swal.fire({
                                    title: "Failed!",
                                    text: "Anda kesalahan saat menghapus data.",
                                    icon: "error"
                                });
                            }
                        })
                    }
                });
            })
        })
    </script>
@endpush
