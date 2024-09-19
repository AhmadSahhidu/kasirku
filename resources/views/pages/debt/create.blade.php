@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    @include('component.modal.import-product')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Hutang</h1>
        <div>
            {{-- @if ($aksescreateProduct || $roleuser === 'Super Admin') --}}
            <a href="{{ route('debt.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                    class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali</a>
            {{-- @endif --}}
        </div>

    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Input Hutang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('debt.proses_debt') }}" method="POST">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h6 class="text-primary font-weight-bold">Data Supplier</h6>
                                </div>
                                <hr class="divider" />
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama Supplier</label>
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name"
                                        autocomplete="off" />
                                    <input type="hidden" class="form-control" name="supplier_id" />
                                    <div class="col-md-12" style="position: relative; width: 100%;">
                                        <ul id="supplier-list" class="dropdown-menu"
                                            style="display:none; position: absolute; z-index: 1000;width: 100%;">
                                            <!-- Pilihan dari AJAX akan ditampilkan di sini -->
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No. Handphone</label>
                                    <input type="text" class="form-control" name="supplier_phone" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" class="form-control" name="supplier_address" />
                                </div>
                            </div>
                            <div class="col-md-12">
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
                            </div>
                        </div>
                    </form>

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
            $("#payment_method").on('change', function() {
                var payment = $(this).val();

                if (payment === '2') {
                    $("#tmp_due_date").show();
                } else {
                    $("#tmp_due_date").hide();

                }
            });

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
