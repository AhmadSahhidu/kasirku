@php
    use App\Models\Sale;
    use App\Models\SaleItem;
    use App\Enums\Sales\{PaymentMethod, SaleType};
@endphp
<style>
    .strikethrough {
        text-decoration: line-through;
        font-size: 10px;
        color: #888;
    }

    .discount-label {
        /*color: #d9534f;*/
        font-weight: bold;
    }
</style>
<div style="width:240px;height:auto;font-family:Arial, Helvetica, sans-serif; padding-bottom:30px;">
    <div style="width:100%;text-align:center; padding-top:10px; padding-left:4px;padding-right:4px; padding-bottom:5px;">
        <div class="form-group">

        </div>
    </div>
    <hr>
    <div style="width:100%;padding:6px 4px;margin-top:2px;">
        <table style="width:100%;font-size: 12px">

            <tr>
                <td style="width: 35%;">Nomor</td>
                <td>:</td>
                <td>{{ $sale->number }}
                </td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $sale->created_at }}
                </td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>:</td>
                <td>{{ $sale->user->name }}
                </td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>:</td>
                <td>{{ $sale->customer ? $sale->customer->name : '-' }}
                </td>
            </tr>
            {{-- <tr>
                <td>Jenis Penjualan</td>
                <td>:</td>
                <td>{{ SaleType::getDescription($sale->sale_type) }}
                </td>
            </tr> --}}

            @php
                $paymentMethod = $sale->payment_method ?? 1;
            @endphp
            <tr>
                <td>Metode Pembayaran</td>
                <td>:</td>
                <td>
                    @if ($sale->payment_method === 1)
                        <p class="font-weight-bold text-primary">Cash</p>
                    @elseif ($sale->payment_method === 2)
                        <p class="font-weight-bold text-primary">Transfer</p>
                    @elseif ($sale->payment_method === 3)
                        <p class="font-weight-bold text-primary">Tempo</p>
                    @else
                        <p class="font-weight-bold text-primary">Balance Customer</p>
                    @endif
                </td>
            </tr>
            {{-- @if ($paymentMethod === PaymentMethod::Split)
                @foreach ($sale->splitPayments as $splitPayment)
                    <tr>
                        <td>Pembayaran {{ $loop->iteration }}</td>
                        <td>:</td>
                        <td>{{ amountFormat($splitPayment->amount) }}</td>
                    </tr>
                    @if ($splitPayment->payment_method === PaymentMethod::EDC)
                        <tr>
                            <td>Nomor Kartu</td>
                            <td>:</td>
                            <td>
                                {{ hideCardNumber($splitPayment->edc_card_number) ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor Transaksi</td>
                            <td>:</td>
                            <td>
                                {{ $splitPayment->edc_transaction_number ?? '-' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif

            @if ($paymentMethod === PaymentMethod::EDC)
                <tr>
                    <td>Nomor Kartu</td>
                    <td>:</td>
                    <td>
                        {{ hideCardNumber($saleInfo?->edc_card_number) ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td>Nomor Transaksi</td>
                    <td>:</td>
                    <td>
                        {{ $saleInfo?->edc_transaction_number ?? '-' }}
                    </td>
                </tr>
            @endif

            @if ($sale->payment_type === PaymentMethod::Debt)
                <tr>
                    <td>Jatuh Tempo Pembayaran</td>
                    <td>:</td>
                    <td>
                        {{ $sale->accountReceivable->due_date->format('d-m-Y H:i') }}
                    </td>
                </tr>
            @endif --}}


        </table>
    </div>
    <hr>
    <div style="width:100%;padding:6px 4px;border-bottom:dashed 1px #333;">
        <table style="width:100%;font-size:13px;">
            @php
                $subtotal = 0;
                /** @var Sale $sale */
            @endphp
            @foreach ($sale->items as $item)
                <tr>
                    <td colspan="3"><b>{{ $item?->product?->name }} ({{ $item?->product?->size }})</b></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <b> {{ $item->product?->brand?->name ?? '' }}</b>
                    </td>
                </tr>
                <tr>
                    <td>

                        {{ amountFormat($item->price) }}

                    </td>
                    <td>{{ $item->qty }}</td>
                    <td align="right">{{ amountFormat($item->price * $item->qty) }}</td>
                </tr>
                @php
                    $subtotal += $item->price * $item->qty;
                @endphp
            @endforeach
            {{-- @if ($sale->reated_id != null)
                <tr>
                    <td style="border-top: 1px solid #ddd;border-bottom: 1px solid #ddd; text-align: center;"
                        colspan="3">Daftar Product Return
                    </td>
                </tr>
                @foreach ($saleReturnItems as $item)
                    @php
                        $saleItem = $item->saleItem ?? SaleItem::find($item->sale_item_id);
                        $product = $saleItem->product ?? getProduct($saleItem->product_id);
                    @endphp
                    <tr>
                        <td colspan="3"><b>{{ $product?->name }} ({{ $product?->size }})</b></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b> {{ $product?->brand?->name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ amountFormat($item->price) }}
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td align="right">{{ amountFormat($item->total) }}</td>
                    </tr>
                @endforeach
            @endif --}}
        </table>
    </div>
    <div style="width:100%;padding:6px 4px;margin-top:2px;">
        <table style="width:100%;font-size: 13px">
            <tr>
                <td>Subtotal</td>
                <td>:</td>
                <td>{{ amountFormat($subtotal) }}</td>
            </tr>
            <tr>
                <td>Diskon</td>
                <td>:</td>
                <td style="border-bottom:dashed 1px #333">{{ amountFormat($sale->discount) }}
                </td>
            </tr>

            {{-- @if ($sale->payment_type === PaymentMethod::Debt)
                <tr>
                    <td>Biaya Tempo ({{ config('app.sale_debt.percent') }}%)</td>
                    <td>:</td>
                    <td style="border-bottom:dashed 1px #333">{{ amountFormat($sale->charge_amount) }}
                    </td>
                </tr>
            @endif
            @if ($sale->reated_id != null)
                <tr>
                    <td>Total Return (-)</td>
                    <td>:</td>
                    <td style="border-bottom:dashed 1px #333"> {{ amountFormat($totalReturn) }}
                    </td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>:</td>
                    <td style="border-bottom:dashed 1px #333">{{ amountFormat($sale->grand_total - $totalReturn) }}
                    </td>
                </tr>
            @else
                <tr>
                    <td>Total</td>
                    <td>:</td>
                    <td style="border-bottom:dashed 1px #333">{{ amountFormat($sale->grand_total) }}
                    </td>
                </tr>
            @endif --}}

            @if ((int) $sale->payment_method !== 3)
                <tr>
                    <td>Bayar</td>
                    <td>:</td>
                    <td style="border-bottom:dashed 1px #333"> {{ amountFormat($saleInfo->pay_amount) }}
                    </td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td>:</td>
                    <td style="border-bottom:dashed 1px #333">{{ amountFormat($saleInfo->change) }}
                    </td>
                </tr>
            @endif

        </table>
    </div>
    <div
        style="width:100%;text-align:center;margin-top:20px 0;border-top:dashed 1px #333; margin-bottom:40px;padding-top: 10px;">
        <span style="font-size: 10px;">TERIMA KASIH ATAS KUNJUNGAN ANDA</span><br>
    </div>


</div>
<style media="screen">
    table tr th {
        margin: 0 0 10px 0;
    }
</style>
<script type="text/javascript">
    window.print();
    window.addEventListener("afterprint", function(event) {
        window.close();
    });
</script>
