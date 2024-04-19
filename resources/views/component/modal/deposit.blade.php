<div class="modal fade" id="deposit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customer.balance_deposit', $balance->id) }}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h6 class="modal-title fs-5 font-weight-bold text-primary">Deposit</h6>
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
                    <button type="button" class="btn btn-sm btn-secondary" id="modalClose">Close</button>
                    <button type="submit" class="btn btn-sm btn-success"><i
                            class="fa fa-save mr-2"></i>Deposit</button>
                </div>
            </form>
        </div>
    </div>
</div>
