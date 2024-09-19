<div class="modal fade" id="listProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title fs-5 font-weight-bold text-primary">Daftar Produk</h6>
            </div>
            <div class="modal-body">
                <div class="table-responsive text-sm">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr style="font-size: 12px;">
                                <th>#</th>
                                <th>Name</th>
                                <th>Ukuran</th>
                                <th>Seri</th>
                                <th>Merek</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Kategori</th>
                                <th>Supplier</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product as $index => $items)
                                <tr style="font-size: 12px;">
                                    <td class="fs-6">{{ $index + 1 }}</td>
                                    {{-- <td>{{ $items->number }}</td> --}}
                                    <td class="text-sm">{{ $items->product->name }}</td>
                                    <td class="text-sm">{{ $items->product->size }}</td>
                                    <td class="text-sm">{{ $items->product->seri }}</td>
                                    <td class="text-sm">{{ $items->product->brand->name ?? '-' }}</td>
                                    <td class="text-sm">{{ rupiahFormat($items->selling_price) }}</td>
                                    <td class="text-sm">{{ $items->stock }}</td>
                                    <td class="text-sm">{{ $items->product->category->name ?? '-' }}</td>
                                    <td class="text-sm">{{ $items->product->supplier->name ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success btnSelect"
                                            data-item-number="{{ $items->product->number }}"
                                            data-sub-product-id="{{ $items->id }}">
                                            Pilih
                                        </button>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
