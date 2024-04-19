<div class="modal fade" id="pengeluaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customer.balance_keluar', $balance->id) }}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h6 class="modal-title fs-5 font-weight-bold text-primary">Pengeluaran</h6>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nominal">
                            Nominal
                        </label>
                        <input type="number" name="nominal" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="note">
                            Keterangan
                        </label>
                        <input type="text" name="note" class="form-control" />
                        <p class="text-xs text-danger mt-1">Note: Kosongkan jika tidak ada</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" id="modalClosePengeluaran">Close</button>
                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save mr-2"></i>Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
