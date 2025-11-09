<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulir Peminjaman Barang</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            font-weight: bold;
            margin: 2cm;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .headerTable {
            width: 100%;
            font-size: 11pt;
        }

        .headerTable td {
            width: 50%;
            vertical-align: middle;
        }

        .headerSection h1 {
            font-size: 11pt;
            text-align: center;
            text-transform: uppercase;
            margin: 12px 0;
        }

        table.infoTable,
        table.infoTableReturn {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.infoTable td,
        table.infoTableReturn td {
            padding: 2px 0;
            vertical-align: top;
        }

        table.infoTable td.label {
            width: 33%;
        }

        table.infoTableReturn td.label {
            width: 40%;
        }

        table.signTable {
            width: 100%;
            margin-top: 10px;
            text-align: center;
        }

        table.signTable td {
            padding: 5px;
            width: 33%;
        }

        .cutLine {
            font-size: 11pt;
            font-weight: bold;
            margin: 20px 0 10px;
        }

        .cutLine hr {
            border: 1px dashed black;
            width: 100%;
            margin: 5px auto;
        }

        p {
            margin: 0;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @foreach ($loans as $employeeId => $employeeLoans)
    @php
    $employee = $employeeLoans->first()->employee;
    @endphp

    <!-- Header Bagian I -->
    <header class="header headerSection">
        <table class="headerTable">
            <tr>
                <td style="text-align:left;">BAGIAN I</td>
                <td style="text-align:right;">FM-BINUS-AA-FPT-25/RI</td>
            </tr>
        </table>
        <h1>FORMULIR PEMINJAMAN BARANG</h1>
    </header>

    <main>
        <!-- Informasi Peminjaman -->
        <section>
            <table class="infoTable">
                <tr>
                    <td class="label">Nama Peminjam</td>
                    <td>: {{ $employee->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td>: {{ $employee->position ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label" style="vertical-align: top;">Barang yang Dipinjam</td>
                    <td style="vertical-align: top;">
                        <div style="display: table; width: 100%;">
                            <div style="display: table-row;">
                                <div style="display: table-cell; width: 10px; vertical-align: top;">:</div>
                                <div style="display: table-cell; vertical-align: top;">
                                    <ul style="margin:0; padding-left:18px; line-height:1.4;">
                                        @foreach ($employeeLoans as $loan)
                                        <li style="margin:0 0 4px 0;">
                                            {{ $loan->item->name ?? '-' }}
                                            @if (!empty($loan->item->accessories))
                                            <ul style="margin:4px 0 0 14px; padding-left:0; list-style-type: circle;">
                                                @foreach (explode(',', $loan->item->accessories) as $acc)
                                                <li style="margin:0 0 3px 0;">{{ trim($acc) }}</li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="label" style="vertical-align: top;">Untuk Keperluan</td>
                    <td style="vertical-align: top;">
                        <div style="display: table; width: 100%;">
                            <div style="display: table-row;">
                                <div style="display: table-cell; width: 10px; vertical-align: top;">:</div>
                                <div style="display: table-cell; vertical-align: top;">
                                    <ul style="margin:0; padding-left:18px; line-height:1.4;">
                                        @foreach ($employeeLoans as $loan)
                                        <li style="margin:0 0 4px 0;">{{ $loan->notes ?? '-' }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="label">Tanggal Pinjam / Kembali</td>
                    <td>:
                        {{ \Carbon\Carbon::parse($employeeLoans->first()->loan_date)->format('d F Y') }}
                        /
                        {{ $employeeLoans->first()->expected_return_date
                            ? \Carbon\Carbon::parse($employeeLoans->first()->expected_return_date)->format('d F Y')
                            : '-' }}
                    </td>
                </tr>
                <tr>
                    <td>Bandung, {{ \Carbon\Carbon::parse($employeeLoans->first()->loan_date)->format('d F Y') }}</td>
                </tr>
            </table>
        </section>

        <!-- Tanda tangan -->
        <section class="signHeader">
            <table class="signTable">
                <tr>
                    <td>Peminjam,*</td>
                    <td>Dipinjamkan,*</td>
                    <td>Disetujui oleh,*</td>
                </tr>
                <tr>
                    <td colspan="3" style="height:50px;"></td>
                </tr>
                <tr>
                    <td>(................................)</td>
                    <td>(................................)</td>
                    <td>(................................)</td>
                </tr>
            </table>

            <p>
                Catatan: Pada saat pengembalian barang jangan lupa meminta Formulir
                Kontrol Pengembalian Barang yang telah diisi oleh staff
                &lt;&lt;Biro/Subbiro/UPT/Jurusan&gt;&gt;. Pengembalian dianggap sah
                bila Formulir Kontrol Pengembalian Barang telah diisi dan
                ditandatangani oleh &lt;&lt;Biro/Subbiro/UPT/Jurusan&gt;&gt;.
            </p>
        </section>

        <!-- Garis potong -->
        <section class="headerSection">
            <div class="cutLine">
                <span>Gunting di sini</span>
                <hr />
            </div>

            <p>BAGIAN II</p>
            <h1>FORMULIR KONTROL PENGEMBALIAN BARANG</h1>
            <p>&lt;&lt;Biro/Subbiro/UPT/Jurusan&gt;&gt;</p>

            <!-- Informasi Pengembalian -->
            <table class="infoTableReturn">
                <tr>
                    <td class="label">Tanggal Kembali</td>
                    <td>: ......../......../........</td>
                </tr>
                <tr>
                    <td class="label">Kondisi Pada Saat Kembali</td>
                    <td>: ..........................</td>
                </tr>
                <tr>
                    <td class="label">Yang Menerima Pengembalian</td>
                    <td>............................</td>
                </tr>
                <tr>
                    <td>Bandung, ............................</td>
                </tr>
            </table>

            <table class="signTable">
                <tr>
                    <td>Dikembalikan</td>
                    <td>Diterima</td>
                </tr>
                <tr>
                    <td colspan="2" style="height:50px;"></td>
                </tr>
                <tr>
                    <td>(................................)</td>
                    <td>(................................)</td>
                </tr>
            </table>

            <p>
                Catatan: Pengembalian barang dianggap sah bila Formulir Kontrol telah
                diisi dan ditandatangani oleh staff
                &lt;&lt;Biro/Subbiro/UPT/Jurusan&gt;&gt;.
            </p>
        </section>
    </main>

    @if (!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach
</body>

</html>