<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk POS - {{ substr($transaction->id, 0, 8) }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0;
            padding: 5mm;
            font-size: 13px;
            color: #000;
            line-height: 1.2;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .divider-thick {
            margin: 10px 0;
            border-top: 2px solid #000;
            height: 2px;
            border-bottom: 2px solid #000;
        }

        .divider-double {
            margin: 10px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
        }

        .divider-dash {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .divider-equal {
            margin: 10px 0;
            overflow: hidden;
            height: 1.2em;
        }

        .divider-dash-line {
            margin: 10px 0;
            border-top: 1px dashed #000;
        }

        .info-table {
            width: 100%;
            margin-bottom: 5px;
        }

        .info-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        .label {
            width: 45%;
        }

        .colon {
            width: 5%;
        }

        .value {
            font-weight: bold;
        }

        .token-box {
            text-align: center;
            margin: 10px 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 15px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #4F46E5;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-family: sans-serif;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <div class="logo font-bold" style="font-size: 20px;">WARUNG DIGI</div>
        <div style="font-size: 12px;">Layanan Pembayaran PPOB Online</div>
        <div style="font-size: 12px;">NPWP: 028.800.265.2-444.000</div>
    </div>

    <div style="border-top: 1px solid #000; border-bottom: 1px solid #000; height: 4px; margin: 10px 0;"></div>

    <div class="text-center font-bold" style="margin-bottom: 5px;">BUKTI PEMBAYARAN PLN</div>

    <table class="info-table">
        <tr>
            <td style="width: 30%">Invoice</td>
            <td style="width: 5%">:</td>
            <td style="font-weight: bold;">IVR/{{ strtoupper(substr($transaction->id, 0, 16)) }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td style="font-weight: bold;">{{ $transaction->created_at->format('d F Y H:i') }} WIB</td>
        </tr>
    </table>

    <div class="divider-dash-line"></div>

    <div class="font-bold" style="margin-bottom: 5px;">Token Listrik</div>

    <table class="info-table">
        <tr>
            <td class="label">Nominal</td>
            <td class="colon">:</td>
            <td class="value">Rp {{ number_format($transaction->amount - 3500, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td class="label">No. Meter</td>
            <td class="colon">:</td>
            <td class="value">{{ $customer->meter_no ?? $transaction->customer_number }}</td>
        </tr>
        <tr>
            <td class="label">Nama Pelanggan</td>
            <td class="colon">:</td>
            <td class="value">{{ $customer->name ?? 'PELANGGAN PLN' }}</td>
        </tr>
        <tr>
            <td class="label">Tarif / Daya</td>
            <td class="colon">:</td>
            <td class="value">{{ $customer->segment_power ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Total KWh</td>
            <td class="colon">:</td>
            <td class="value">{{ $transaction->kwh ?? '0' }} kWh</td>
        </tr>
    </table>

    <div class="divider-dash-line"></div>
    <div class="text-center font-bold">TOKEN</div>
    <div class="token-box">
        @if($transaction->sn_token)
        @php
        $token = str_replace(['-', ' '], '', $transaction->sn_token);
        $formattedToken = implode(' ', str_split($token, 4));
        @endphp
        {{ $formattedToken }}
        @else
        -
        @endif
    </div>
    <div class="divider-dash-line"></div>

    <div style="margin-top: 10px;">
        <div class="font-bold">TOTAL BAYAR : Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
        <div style="font-size: 11px;">(Termasuk pajak dan biaya administrasi)</div>
    </div>

    <div style="border-top: 1px solid #000; border-bottom: 1px solid #000; height: 4px; margin: 15px 0;"></div>

    <div class="footer">
        <div class="font-bold">TERIMA KASIH TELAH MEMILIH KAMI</div>
        <div>Simpan resi ini sebagai bukti pembayaran sah.</div>
        <div>www.warungdigi.co.id</div>
    </div>

    <div style="border-top: 1px solid #000; border-bottom: 1px solid #000; height: 4px; margin: 15px 0;"></div>

    <div class="no-print">
        <a href="javascript:window.print()" class="btn-print">CETAK STRUK</a>
        <a href="{{ route('dashboard') }}" style="display: block; text-align: center; margin-top: 15px; font-family: sans-serif; font-size: 12px; color: #666;">Kembali ke Dashboard</a>
    </div>
</body>

</html>