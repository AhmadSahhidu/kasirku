<div class="modal fade" id="transaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customer.balance_keluar', $balance->id) }}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h6 class="modal-title fs-5 font-weight-bold text-primary">Pilih Jenis Transaksi</h6>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select class="form-control" id="type">
                            <option value="1">Modal</option>
                            <option value="2">Deposit</option>
                            <option value="3">Pembayaran</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" id="modalClose">Close</button>
                    <button type="button" id="btnProses" class="btn btn-sm btn-success"><i
                            class="fa fa-save mr-2"></i>Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
