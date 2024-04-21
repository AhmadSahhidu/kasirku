<div class="modal fade" id="edit-qty" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('cashier.edit_qty_cart') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title fs-5 font-weight-bold text-primary">Edit Qty</h6>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nominal">
                            Qty
                        </label>
                        <input type="number" name="qty" id="edit_qty" class="form-control" required />
                        <input type="hidden" name="cart_id" id="cart_id" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save mr-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
