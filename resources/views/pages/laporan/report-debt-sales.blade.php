@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Jatuh Tempo</h1>
        <a href="{{ route('debt_payment.auto_balance_customer') }}"
            class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-money-bill fa-sm text-white-50 mr-1"></i> Auto Balance Customer</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Laporan Jatuh Tempo</h6>
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
                            <th>Action</th>
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
                                <td>
                                    @if ($items->status === 1)
                                        <a href="{{ route('debt_payment.index', $items->id) }}"
                                            class="btn btn-sm btn-success"><i class="fa fa-money-bill"></i>Bayar</a>
                                    @else
                                        <a href="{{ route('debt_payment.index', $items->id) }}"
                                            class="btn btn-sm btn-success"><i class="fa fa-eye mr-2"></i>Detail</a>
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
@push('script')
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function() {
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
                            url: "{{ route('customer.delete_customer') }}",
                            type: 'GET',
                            data: {
                                idCustomer: itemId
                            },
                            success: function() {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Anda berhasil menghapus data.",
                                    icon: "success"
                                });
                                window.location.reload();
                            },
                            error: function() {
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
