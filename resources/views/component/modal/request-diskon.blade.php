<div class="modal fade" id="requestDiskon" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title fs-5 font-weight-bold text-primary">Form Input Request Diskon</h6>
            </div>
            <div class="modal-body">
                <form action="{{ route('cashier.request_discount') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Belanja</label>
                                <input type="text" value="{{ $total }}" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nominal Diskon</label>
                                <input type="text" name="amount_discount" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right">
                                <hr />
                                <button type="submit" class="btn btn-success btn-sm">Kirim Permintaan Diskon</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
