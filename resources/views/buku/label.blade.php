<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Buku - {{ $buku->judul }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e2e8f0; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .book-card {
            width: 80mm;  
            height: 50mm;
            background: white;
            border: 2px solid #333;
            border-radius: 5px;
            position: relative;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .left-info {
            flex: 1;
            padding-right: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px dashed #ccc;
        }

        .right-qr {
            width: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-left: 10px;
        }

        .header {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 5px;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            line-height: 1.2;
            margin-bottom: 4px;
            max-height: 2.4em;
            overflow: hidden;
        }

        .author {
            font-size: 10px;
            color: #444;
            margin-bottom: 8px;
            font-style: italic;
        }

        .meta-box {
            background: #f3f4f6;
            padding: 4px;
            border-radius: 3px;
            font-size: 9px;
            display: inline-block;
        }

        .meta-label { font-weight: bold; color: #555; }
        .meta-value { color: #000; font-weight: bold; }

        @media print {
            body { 
                background: none; 
                -webkit-print-color-adjust: exact; 
                display: block;
            }
            .no-print { display: none; }
            .book-card {
                box-shadow: none;
                page-break-inside: avoid;
                margin: 0;
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>

    <button class="no-print" onclick="window.print()" style="position: absolute; top: 20px; background: #333; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;">
        🖨️ Cetak Label
    </button>

    <div class="book-card">
        <div class="left-info">
            <div class="header">{{ config('app.name') }}</div>
            <div class="title">{{ Str::limit($buku->judul, 50) }}</div>
            <div class="author">Oleh: {{ $buku->penulis }} ({{ $buku->tahun }})</div>
            
            <div class="meta-box">
                <span class="meta-label">Lokasi:</span> <br>
                <span class="meta-value">
                    {{ $buku->kategori->nama_kategori ?? '-' }} 
                    ({{ $buku->kategori->lokasi_rak ?? 'Belum set' }})
                </span>
            </div>
        </div>

        <div class="right-qr">
            <div id="qrcode"></div>
            <div style="font-size: 8px; margin-top: 5px; font-weight: bold;">ID: {{ $buku->id }}</div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code berisi ID Buku (Supaya nanti bisa discan sistem)
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $buku->id }}", 
            width: 70,
            height: 70,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>

</body>
</html>