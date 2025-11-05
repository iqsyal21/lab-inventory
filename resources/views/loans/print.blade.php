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

        /* Bagian I: default proporsi label 1fr : 2fr (≈33% : 67%) */
        table.infoTable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.infoTable td {
            padding: 2px 0;
            vertical-align: top;
        }

        table.infoTable td.label {
            width: 33%;
        }

        /* Bagian II: proporsi 2fr : 3fr (≈40% : 60%) */
        table.infoTableReturn {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.infoTableReturn td {
            padding: 2px 0;
            vertical-align: top;
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
    </style>
</head>

<body>
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
                    <td>: {{ $loan->employee->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td>: {{ $loan->employee->position ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Barang yang Dipinjam</td>
                    <td>
                        :
                        {{ $loan->item->name ?? '-' }}
                        @if(!empty($loan->item->accessories))
                        <br />
                        @foreach(explode(',', $loan->item->accessories) as $accessory)
                        {{ trim($accessory) }}<br />
                        @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Untuk Keperluan</td>
                    <td>: {{ $loan->notes ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Pinjam / Kembali</td>
                    <td>
                        :
                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d F Y') }}
                        /
                        {{ $loan->expected_return_date ? \Carbon\Carbon::parse($loan->expected_return_date)->format('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td>Bandung, {{ \Carbon\Carbon::parse($loan->loan_date)->format('d F Y') }}</td>
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

            <!-- Informasi Pengembalian (2fr : 3fr) -->
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
</body>

</html>