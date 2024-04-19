<div class="modal fade" id="import-product" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('product.import_product') }}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h6 class="modal-title fs-5 font-weight-bold text-primary">File Import</h6>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" name="file" class="form-control" required />
                        <p class="text-xs text-info mt-2">Note: Masukkan file type : csv,xls,xlsx</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" id="modalClose">Close</button>
                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save mr-2"></i>Import
                        Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
