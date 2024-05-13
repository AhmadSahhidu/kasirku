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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Konfirmasi Permintaan Diskon</h1>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Konfirmasi Diskon</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">Nomer Diskon</label>
                                <p class="font-weight-bold">{{ $diskon->number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">Tanggal Permintaan</label>
                                <p class="font-weight-bold">{{ $diskon->created_at }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">Kasir Pembuat</label>
                                <p class="font-weight-bold">{{ $diskon->user->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">Nominal</label>
                                <p class="font-weight-bold">{{ rupiahFormat($diskon->amount_discount) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="width: 100%" for="number">Status Konfirmasi</label>
                                @if ($diskon->status === 1)
                                    <div class="btn btn-info btn-sm">Menunggu Konfirmasi</div>
                                @elseif ($diskon->status === 2)
                                    <div class="btn btn-primary btn-sm">Disetujui</div>
                                @else
                                    <div class="btn btn-danger btn-sm">Ditolak</div>
                                @endif

                            </div>
                        </div>
                        @if ($diskon->status === 1)
                            <div class="col-md-12">
                                <hr />
                                <form action="{{ route('diskon.store_diskon', $diskon->id) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label style="width: 100%" for="status">Status Konfirmasi</label>
                                                <select class="form-control" name="status">
                                                    <option value="">Pilih konfirmasi diskon</option>
                                                    <option value="2">Disetujui</option>
                                                    <option value="3">Ditolak</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label style="width: 100%" for="number">Catatan</label>
                                                <textarea name="note" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <hr>
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-success btn-sm"><i
                                                        class="fa fa-save mr-2"></i>Konfirmasi</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

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

        })
    </script>
@endpush
