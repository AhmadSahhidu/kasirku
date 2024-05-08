@php
    use App\Enums\Sales\PaymentMethod;
    use App\Enums\Sales\SaleType;
    use Carbon\Carbon;

    if (request('date')) {
        $tanggalAwal = str_replace("'", '', request('date'));

        $tanggalObjek = Carbon::createFromFormat('Y-m-d', $tanggalAwal);

        $tanggalAkhir = $tanggalObjek->format('d-m-Y');
    }

@endphp
<html lang="id">

<head>
    <title>Nota Penjualan</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }


        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }

        .stamp {
            transform: rotate(12deg);
            color: #555;
            font-size: 2rem;
            font-weight: 700;
            border: 0.25rem solid #555;
            display: inline-block;
            padding: 0.25rem 1rem;
            text-transform: uppercase;
            border-radius: 1rem;
            font-family: 'Courier';
            -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
            -webkit-mask-size: 944px 604px;
            mix-blend-mode: multiply;
        }


        .is-nope {
            color: #D23;
            border: 0.5rem double #D23;
            transform: rotate(3deg);
            -webkit-mask-position: 2rem 3rem;
            font-size: 2rem;
        }

        .is-approved {
            color: #0A9928;
            border: 0.5rem solid #0A9928;
            -webkit-mask-position: 13rem 6rem;
            transform: rotate(-14deg);
            border-radius: 0;
        }


        .rubber-paid {
            font-size: 2.5vw;
            transform: rotateZ(-45deg);
            margin: auto;
            display: inline-block;
            box-shadow: 0 0 0 3px #0A9928, 0 0 0 2px #0A9928 inset;
            border: 2px solid transparent;
            border-radius: 4px;

            font-size: 150px;
            font-weight: 700;
            font-family: 'Segoe Ui', cursive;
            color: #0A9928;
            text-transform: uppercase;
            text-align: center;
            opacity: 0.1;
            width: auto;
            position: absolute;
            align-content: center;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
        }

        .rubber-unpaid {
            font-size: 2.5vw;
            transform: rotateZ(-45deg);
            margin: auto;
            display: inline-block;
            box-shadow: 0 0 0 3px #D23, 0 0 0 2px #D23 inset;
            border: 2px solid transparent;
            border-radius: 4px;

            font-size: 100px;
            font-weight: 700;
            font-family: 'Segoe Ui', cursive;
            color: #D23;
            text-transform: uppercase;
            text-align: center;
            opacity: 0.1;
            width: auto;
            position: absolute;
            align-content: center;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
        }

        .note-transfer {
            margin-top: 20px;
            width: 90%;
            padding: 5px 10px;
            float: right;
            display: flex;
            border: 1px solid #ddd;
            font-size: 12px;
        }
    </style>

</head>
@php
    $address = null;
    if ($sale->customer) {
        if ($sale->customer->mainAddress) {
            $address = $sale->customer->mainAddress;
        }
    }
@endphp

<body style='font-family:tahoma; font-size:8pt;' class="container">
    <center style="margin-top: 20px;">
        <table style='width:700px; font-size:10pt; font-family:calibri; border-collapse: collapse;' border='0'>

            <td style='vertical-align:top' width='70%'>
                <b><span style='font-size:14pt'>NOTA PENJUALAN</span></b></br>
                <b> No Trans. : {{ $sale->number }}</b><br>
                <b> Nama Pelanggan : {{ $sale->customer ? $sale->customer->name : '-' }}</b><br>
                <b> Alamat : {{ $sale->customer ? $sale->customer->address : '' }} </b><br>
                <b> HP Pelanggan : {{ $sale?->customer?->phone }} </b><br>
            </td>
            <td width='30%' style='vertical-align:top; '>
                <b>Kasir : {{ $sale->user->name }}</b><br>
                <b>Tanggal :
                    {{ request('date') ? $tanggalAkhir : $sale->created_at->format('d-m-Y H:i') }}</b><br>
                <b>Jenis Pembayaran :
                    @if ($sale->payment_method === 1)
                        <span class="font-weight-bold text-primary">Cash</span>
                    @elseif ($sale->payment_method === 2)
                        <span class="font-weight-bold text-primary">Transfer</span>
                    @elseif ($sale->payment_method === 3)
                        <span class="font-weight-bold text-primary">Tempo</span>
                    @else
                        <span class="font-weight-bold text-primary">Balance Customer</span>
                    @endif
                </b>
            </td>
        </table>
        <table style='width:700px; font-size:10pt; font-family:calibri; border-collapse: collapse;' border='0'>

            {{-- <td width="30%">
            <span class="stamp is-approved">Lunas</span>
        </td> --}}

        </table>
        <table style='width:700px; font-family:Tahoma;  border-collapse: collapse; margin-top:20px; font-size: 14px'
            border='2'>

            <tr align='center' style="border-collapse: collapse;">
                <td style="border-collapse: collapse;" width='3%'>No</td>
                <td style="border-collapse: collapse;" width='25%'>Nama Barang</td>
                <td style="border-collapse: collapse;" width='10%'>Merk</td>
                <td style="border-collapse: collapse;" width='10%'>Ukuran</td>
                <td colspan="{{ request('ppn') ? 2 : 0 }}" style="border-collapse: collapse;" width='13%'>Harga</td>
                <td style="border-collapse: collapse;" width='4%'>Qty</td>
                @if (!request('ppn'))
                    <td style="border-collapse: collapse;" width='7%'>Discount</td>
                @endif
                <td style="border-collapse: collapse;" width='13%'>Total Harga</td>
            </tr>

            @php
                $subtotal = 0;
                $subTotaltambahan = 0;
            @endphp
            @foreach ($sale->items as $item)
                <tr style="font-weight: bold; border-collapse: collapse;">
                    <td style="border-collapse: collapse;">
                        <p>
                            <center>{{ $loop->iteration }}</center>
                        </p>
                    </td>
                    <td style="border-collapse: collapse;">
                        <p>{{ $item->product?->name }}
                        </p>
                    </td>
                    <td style="border-collapse: collapse;">
                        <p>{{ $item->product?->brand?->name }}</p>
                    </td>
                    <td style="border-collapse: collapse;">
                        <p>{{ $item->product?->size }}</p>
                    </td>
                    <td colspan="{{ request('ppn') ? 2 : 0 }}" style="border-collapse: collapse;">
                        <p>{{ amountFormat($item->price) }}</p>
                    </td>
                    <td style="border-collapse: collapse;">
                        <p>{{ $item->qty }}</p>
                    </td>
                    @if (!request('ppn'))
                        <td style="border-collapse: collapse;">
                            <p>{{ amountFormat(0) }}</p>
                        </td>
                    @endif

                    <td style='text-align:right; border-collapse: collapse;'>
                        <p>{{ amountFormat($item->price * $item->qty) }}</p>
                    </td>
                </tr>
                @php
                    $subtotal += $item->total;
                @endphp
            @endforeach
            @if ($notadarireturn != null)

                @foreach ($itemTambahan->items as $item)
                    @php
                        $subTotaltambahan += $item->total;
                        $saleItem = $item->saleItem ?? SaleItem::find($item->sale_item_id);
                        $product = $saleItem->product ?? getProduct($saleItem->product_id);
                    @endphp
                    <tr style="font-weight:bold">
                        <td style="border-collapse: collapse;">
                            <p>
                                <center>{{ $loop->iteration + count($saleItems) }}</center>
                            </p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $product?->name }} <br />Note:
                                <span>Barang diretur</span>
                            </p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $product?->brand?->name }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $product?->size }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ amountFormat($item->price) }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $item->quantity }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ amountFormat(0) }}</p>
                        </td>
                        <td style='text-align:right; border-collapse: collapse;'>
                            <p>{{ amountFormat($item->total) }}</p>
                        </td>
                    </tr>
                @endforeach
            @endif
            {{-- @if ($sale->reated_id != null)
                <tr>
                    <td colspan="8">Daftar Barang Return</td>
                </tr>
                @foreach ($saleReturnItems as $item)
                    @php
                        $saleItem = $item->saleItem ?? SaleItem::find($item->sale_item_id);
                        $product = $saleItem->product ?? getProduct($saleItem->product_id);
                    @endphp
                    <tr style="font-weight:bold">
                        <td style="border-collapse: collapse;">
                            <p>
                                <center>{{ $loop->iteration }}</center>
                            </p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $product?->name }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $product?->brand?->name }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $product?->size }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ amountFormat($item->price) }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ $item->quantity }}</p>
                        </td>
                        <td style="border-collapse: collapse;">
                            <p>{{ amountFormat(0) }}</p>
                        </td>
                        <td style='text-align:right; border-collapse: collapse;'>
                            <p>{{ amountFormat($item->total) }}</p>
                        </td>
                    </tr>
                @endforeach
            @endif --}}

            <tr>
                <td colspan='7'>
                    <div style='text-align:right'>Sub Total :</div>
                </td>
                <td style='text-align:right'>{{ amountFormat($sale->total) }}</td>
            </tr>
            {{-- @if (request('ppn'))
                <tr>
                    <td colspan='7'>
                        <div style='text-align:right'>PPN (11%) :</div>
                    </td>
                    <td style='text-align:right'>{{ amountFormat(round($sale->total * 0.11)) }}</td>
                </tr>
            @else --}}
            <tr>
                <td colspan='7'>
                    <div style='text-align:right'>Diskon :</div>
                </td>
                <td style='text-align:right'>{{ amountFormat($sale->discount) }}</td>
            </tr>
            {{-- @endif
            @if ($sale->reated_id != null)
                <tr>
                    <td colspan='7'>
                        <div style='text-align:right'>Total Return :</div>
                    </td>
                    <td style='text-align:right'>{{ amountFormat($totalReturn) }}</td>
                </tr>
                <tr>
                    <td colspan='7'>
                        <div style='text-align:right'><b>Grand Total :</b></div>
                    </td>
                    <td style='text-align:right'>
                        <b>{{ $sale->grand_total - $totalReturn > 0 ? amountFormat($sale->grand_total - $totalReturn) : 0 }}</b>
                    </td>
                </tr>
                @if ($sale->payment_method === PaymentMethod::Debt)
                    <tr>
                        <td colspan='7'>
                            <div style='text-align:right'>Kembali : </div>
                        </td>
                        <td style='text-align:right'>{{ amountFormat($totalReturn - $sale->grand_total) }}</td>
                    </tr>
                @endif
            @elseif (request('ppn'))
                @php
                    $ppn = round($sale->total * 0.11);
                @endphp
                <tr>
                    <td colspan='7'>
                        <div style='text-align:right'><b>Grand Total :</b></div>
                    </td>
                    <td style='text-align:right'>
                        <b>{{ amountFormat($sale->grand_total + $ppn) }}</b>
                    </td>
                </tr>
            @else --}}
            <tr>
                <td colspan='7'>
                    <div style='text-align:right'><b>Grand Total :</b></div>
                </td>
                <td style='text-align:right'><b>{{ amountFormat($sale->grand_total) }}</b></td>
            </tr>
            {{-- @endif
            @if ($sale->payment_method != PaymentMethod::Debt)
                <tr>
                    <td colspan='7'>
                        <div style='text-align:right'>Cash :</div>
                    </td>
                    <td style='text-align:right'>{{ amountFormat($sale->saleInfo->pay_amount) }}</td>
                </tr>
                <tr>
                    <td colspan='7'>
                        <div style='text-align:right'>Kembali : </div>
                    </td>
                    <td style='text-align:right'>{{ amountFormat($sale->saleInfo->change) }}</td>
                </tr>
            @endif --}}

        </table>
        {{-- @if ($payment !== null)
            <table style="width: 700px;">
                <tr>
                    <td align="center">
                        <div class="rubber-{{ $payment->paid != 0 ? 'paid' : 'unpaid' }}">
                            {{ $payment->paid != 0 ? 'Terbayar' : 'Belum Terbayar' }}
                        </div>

                    </td>
                </tr>
            </table>
        @endif --}}



        <table style=' font-size:10pt; margin-top:50px; width: 100%;'>
            <tr>
                <td style="width: 33%;" align="center">Pengecekan
                    Oleh<br><br><br><br><br><br><u><i style="font-weight: 100;">( ...........................)</i></u>
                </td>
                <td style="width: 33%;" align="center">Dikirim Oleh
                    <br><br><br><br><br><br><u><i style="font-weight: 100;">( ...........................)</i></u>
                </td>
                <td style="width: 33%;" align="center">TTD
                    Penerima<br><br><br><br><br><br><u><i style="font-weight: 100;">(
                            ...........................)</i></u></td>
            </tr>
        </table>
        <table style="margin-top: 25px;">
            <tr>
                <td style='font-size:7pt; text-align:center; width:100%'><b>*Barang yang sudah dibeli tidak dapat
                        dikembalikan</b></td>
            </tr>
        </table>
    </center>
    {{-- @if (request('note'))
        <div class="note-transfer">
            <div style="margin-top: 10px">
                <b>Pembayaran dapat ditransfer ke rekening : </b>
                <p><b>Atas nama Era Fitri Lailanita</b></p>
            </div>
            <ul>
                <li>BCA : 8030062877</li>
                <li>Mandiri : 1350089991234</li>
                <li>BNI : 7070198441</li>
            </ul>
        </div>
    @endif --}}

</body>

</html>
<script>
    window.print();

    window.addEventListener("afterprint", function(event) {
        window.close();
    });
</script>
