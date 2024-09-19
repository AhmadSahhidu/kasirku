@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    @include('component.modal.import-product')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Data Hutang</h1>
        <div>
            {{-- @if ($aksescreateProduct || $roleuser === 'Super Admin') --}}
            <a href="{{ route('debt.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                    class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali</a>
            {{-- @endif --}}
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Hutang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('debt.proses_debt') }}" method="POST">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group flex-col">
                                    <p>No. Pembelian : <b class="text-info ml-2">{{ $debt->no_invoice }}</b></p>
                                    <p>Jenis Pembayaran : <b
                                            class="text-info ml-2">{{ $debt->payment_method === 1 ? 'Cash / Tunai' : 'Tempo' }}</b>
                                    </p>
                                    <p>Total Pembelian : <b class="text-info ml-2">{{ rupiahFormat($debt->amount) }}</b></p>
                                    <p>Jatuh Tempo : <b class="text-info ml-2">{{ $debt->due_date ?? '-' }}</b></p>
                                    <p>Status : <b class="text-info ml-2">{{ $debt->status }}</b></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Nama Supplier : <b class="text-info ml-2">{{ $debt->supplier->name }}</b></p>
                                    <p>No Handphone : <b class="text-info ml-2">{{ $debt->supplier->phone }}</b></p>
                                    <p>Alamat : <b class="text-info ml-2">{{ $debt->supplier->address }}</b></p>
                                    <p>Hutang Dibayar : <b class="text-info ml-2">{{ rupiahFormat($debt->paid_debt) }}</b>
                                    </p>
                                    <p>Sisa Hutang : <b
                                            class="text-info ml-2">{{ rupiahFormat($debt->remaining_debt) }}</b></p>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <hr class="divider" />
                                <div class="form-group">
                                    <h6 class="text-primary font-weight-bold">Data Pembelian</h6>
                                </div>
                                <hr class="divider" />
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No. Pembelian</label>
                                    <input type="text" class="form-control" name="no_invoice" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nominal Hutang</label>
                                    <input type="text" class="form-control" name="amount" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Pembayaran</label>
                                    <select class="form-control" id="payment_method" name="payment_method">
                                        <option value="1">Cash</option>
                                        <option value="2">Tempo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" style="display: none;" id="tmp_due_date">
                                <div class="form-group">
                                    <label>Jatuh Tempo</label>
                                    <input type="date" class="form-control" name="due_date" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr class="divider" />
                                <div class="form-group text-right">
                                    <button class="btn btn-success btn-sm"><i class="fa fa-save mr-2"></i>Simpan
                                        data</button>
                                </div>
                            </div> --}}
                        </div>
                    </form>

                </div>
            </div>
        </div>
        @if (count($payment) > 0)
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">History Pembayaran</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Hutang Dibayar</th>
                                        <th>Sisa Hutang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payment as $index => $items)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $items->tanggal }}</td>
                                            <td>{{ rupiahFormat($items->amount) }}</td>
                                            <td>{{ $items->payment_method === 1 ? 'Cash / Tunai' : 'Transfer / Kartu ATM' }}
                                            </td>
                                            <td>{{ rupiahFormat($items->paid_debt ?? '0') }}</td>
                                            <td>{{ rupiahFormat($items->remaining_debt ?? '0') }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($debt->status === 'Belum Lunas')
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Input Pembayaran Hutang</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('debt.proses_hutang', $debt->id) }}" method="POST">
                            @csrf
                            @method('post')
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nomer</label>
                                        <input type="text" class="form-control" name="number"
                                            value="{{ $debt->number }}" readonly />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Hutang Dibayar</label>
                                        <input type="text" class="form-control" name="paid"
                                            value="{{ $debt->paid_debt }}" readonly />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sisa Hutang</label>
                                        <input type="text" class="form-control" name="remaining"
                                            value="{{ $debt->remaining_debt }}" readonly />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jumlah Pembayaran</label>
                                        <input type="text" class="form-control" name="amount_paid" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Pembayaran</label>
                                        <select class="form-control" id="payment_method" name="payment_method">
                                            <option value="1">Cash / Tunai</option>
                                            <option value="2">Transfer / Kartu ATM</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr class="divider" />
                                    <div class="form-group text-right">
                                        <button class="btn btn-success btn-sm"><i class="fa fa-save mr-2"></i>Simpan
                                            data</button>
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
@push('script')
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function() {

            $("#supplier_name").on('keyup', function() {
                var supplier_name = $(this).val();

                if (supplier_name.length >= 2) { // Cari saat input lebih dari 2 karakter
                    $.ajax({
                        url: "{{ route('debt.search_supplier') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            supplier_name: supplier_name,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            var supplierList = $("#supplier-list");
                            supplierList.empty().hide(); // Kosongkan dan sembunyikan dropdown

                            if (res.data.length > 0) {
                                // Iterasi data dan buat item dropdown
                                res.data.forEach(function(supplier) {
                                    supplierList.append(
                                        '<a class="dropdown-item" href="#" data-id="' +
                                        supplier.id +
                                        '" data-phone="' + supplier.phone +
                                        '" data-address="' + supplier.address +
                                        '"><li class="fa fa-circle fa-xs mr-2"></li>' +
                                        supplier.name +
                                        '</a>');
                                });
                                supplierList.show(); // Tampilkan dropdown
                            }
                        },
                        error: function() {
                            console.log("Error fetching supplier data.");
                        }
                    });
                } else {
                    $("#supplier-list").empty()
                        .hide(); // Kosongkan dan sembunyikan dropdown jika input terlalu pendek
                    $('input[name="supplier_id"]').val(''); // Kosongkan supplier_id jika input dihapus
                    $('input[name="supplier_address"]').val(''); // Kosongkan supplier_id jika input dihapus
                    $('input[name="supplier_phone"]').val(''); // Kosongkan supplier_id jika input dihapus
                }
            });

            $(document).on('click', '#supplier-list .dropdown-item', function(e) {
                e.preventDefault();

                var selectedSupplierName = $(this).text();
                var selectedSupplierId = $(this).data('id');
                var selectedSupplierPhone = $(this).data('phone');
                var selectedSupplierAddress = $(this).data('address');

                $('#supplier_name').val(selectedSupplierName); // Isi nilai input dengan nama yang dipilih
                $('input[name="supplier_id"]').val(selectedSupplierId);
                $('input[name="supplier_phone"]').val(selectedSupplierPhone);
                $('input[name="supplier_address"]').val(selectedSupplierAddress);

                $('#supplier-list').empty().hide(); // Sembunyikan dropdown setelah memilih
            });


        })
    </script>
@endpush
