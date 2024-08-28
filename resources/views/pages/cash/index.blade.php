@php
    $aksescreateArusKas = validationAkses('create data arus kas');
    $akseseditArusKas = validationAkses('edit data arus kas');
    $aksesdeleteArusKas = validationAkses('delete data arus kas');
    $roleuser = userRoleName();
@endphp
@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Arus Kas</h1>
        @if ($aksescreateArusKas || $roleuser === 'Super Admin')
            <a href="{{ route('cash.create_cash') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Data</a>
        @endif
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
                            <select class="form-control" id="select-store">
                                <option disabled selected>Pilih Cabang Store</option>
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
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-2">
                                Total Pemasukan Kas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ rupiahFormat($countIn ?? 0) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ rupiahFormat($countOperasional ?? 0) }}
                            </div>
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
                                Total Modal</div>
                            <div class="h5 mb-0 font-weight-bold ">
                                {{ rupiahFormat($countModal ?? 0) }}</div>
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
            <h6 class="m-0 font-weight-bold text-primary">Data Pemasukan Kas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashIn as $index => $items)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $items->tgl }}</td>
                                <td>{{ amountFormat($items->amount) }}</td>
                                <td>{{ $items->note ?? '-' }}</td>
                                <td>
                                    @if ($akseseditArusKas || $roleuser === 'Super Admin')
                                        <a href="{{ route('cash.edit_cash', $items->id) }}"
                                            class="btn btn-sm btn-circle btn-primary" title="Edit Data">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    @endif
                                    @if ($aksesdeleteArusKas || $roleuser === 'Super Admin')
                                        <button type="button" class="btn btn-sm btn-circle btn-danger btnDelete"
                                            data-item-id="{{ $items->id }}" title="Hapus Data">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran Modal</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-cash-modal" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashModal as $index => $items)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $items->tgl }}</td>
                                <td>{{ amountFormat($items->amount) }}</td>
                                <td>{{ $items->note ?? '-' }}</td>
                                <td>
                                    @if ($akseseditArusKas || $roleuser === 'Super Admin')
                                        <a href="{{ route('cash.edit_cash', $items->id) }}"
                                            class="btn btn-sm btn-circle btn-primary" title="Edit Data">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    @endif
                                    @if ($aksesdeleteArusKas || $roleuser === 'Super Admin')
                                        <button type="button" class="btn btn-sm btn-circle btn-danger btnDelete"
                                            data-item-id="{{ $items->id }}" title="Hapus Data">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran Operasional</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-cash-operasional" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashOperasional as $index => $items)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $items->tgl }}</td>
                                <td>{{ amountFormat($items->amount) }}</td>
                                <td>{{ $items->note ?? '-' }}</td>
                                <td>
                                    @if ($akseseditArusKas || $roleuser === 'Super Admin')
                                        <a href="{{ route('cash.edit_cash', $items->id) }}"
                                            class="btn btn-sm btn-circle btn-primary" title="Edit Data">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    @endif
                                    @if ($aksesdeleteArusKas || $roleuser === 'Super Admin')
                                        <button type="button" class="btn btn-sm btn-circle btn-danger btnDelete"
                                            data-item-id="{{ $items->id }}" title="Hapus Data">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
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
            $("#select-store").select2();
            $("#dataTable-cash-modal").DataTable();
            $("#dataTable-cash-operasional").DataTable();
            $('.btnDelete').on('click', function() {
                var itemId = $(this).data('item-id');
                Swal.fire({
                    icon: 'question',
                    title: "Apakah anda akan menghapus data ini?",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('cash.delete_cash') }}",
                            type: 'GET',
                            data: {
                                idCash: itemId
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Failed!",
                                    text: xhr.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });

            $("#btnFilter").on('click', function() {
                const start_date = $("#start_date").val();
                const end_date = $("#end_date").val();
                const store = $("#select-store").val();
                if (start_date === '' && end_date === '') {
                    Swal.fire({
                        title: 'Pemberitahuan',
                        text: 'Masukkan tanggal mulai dan tanggal akhir',
                        type: 'warning'
                    })
                } else if (store != '') {
                    window.location.href = '{{ route('cash.index') }}' + '?start_date=' +
                        start_date + "&end_date=" + end_date + '&store=' + store;
                } else {
                    window.location.href = '{{ route('cash.index') }}' + '?start_date=' +
                        start_date + "&end_date=" + end_date;
                }
            })
        });
    </script>
@endpush
