@extends('component.layout.app')
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaksi Balance</h1>
        <a href="{{ route('cash.balance_store') }}?store={{ $balance->store_id }}"
            class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                class="fas fa-backward fa-sm text-white-50 mr-1"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('cash.proses_balance_transaction') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="name">Nominal</label>
                            <input class="form-control" name="amount" required />
                            <input type="hidden" class="form-control" name="type" value="{{ request('type') }}"
                                required />
                            <input type="hidden" class="form-control" name="balanceId" value="{{ request('balance') }}"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Jenis Transaksi</label>
                            <input class="form-control" readonly
                                value="{{ (request('type') === '1' ? 'Modal' : request('type') === '2') ? 'Customer Deposit' : 'Pembayaran' }}"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Keterangan</label>
                            <input class="form-control" name="note" required />
                        </div>
                        @if (request('type') === '3')
                            <div class="form-group">
                                <label for="name">Sumber Dana</label>
                                <select class="form-control" name="sumber">
                                    <option value="1">Balance ATM / Dipegang sendiri</option>
                                    <option value="2">Balance dikasir</option>
                                    <option value="3">Balance Deposit Customer</option>
                                </select>
                            </div>
                        @endif


                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save mr-2"></i>Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
