<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota - {{ $mahasiswa->nama }}</title>
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

        .card-container {
            width: 85.6mm;  /* Standar ID Card */
            height: 53.98mm;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); 
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            color: white;
            display: flex;
            padding: 15px;
            box-sizing: border-box;
        }

        .circle-bg {
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .content-left {
            flex: 2;
            z-index: 10;
        }

        .header {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            padding-bottom: 5px;
            display: inline-block;
        }

        .nama {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            text-transform: uppercase;
            line-height: 1.2;
            max-width: 180px; /* Batasi lebar agar tidak nabrak QR */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nim {
            font-size: 10px;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .prodi {
            font-size: 9px;
            background-color: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }

        .footer {
            font-size: 7px;
            position: absolute;
            bottom: 10px;
            left: 15px;
            opacity: 0.7;
        }

        .content-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        .qr-box {
            background: white;
            padding: 5px;
            border-radius: 8px;
        }

        @media print {
            body { 
                background: none; 
                -webkit-print-color-adjust: exact; 
                display: block;
            }
            .no-print { display: none; }
            .card-container {
                box-shadow: none;
                page-break-inside: avoid;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <button class="no-print" onclick="window.print()" style="position: absolute; top: 20px; background: #333; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;">
        🖨️ Cetak Kartu
    </button>

    <div class="card-container">
        <div class="circle-bg"></div>
        
        <div class="content-left">
            <div class="header">PERPUSTAKAAN DIGITAL</div>
            
            <div class="nama">{{ $mahasiswa->nama }}</div>
            <div class="nim">NIM: {{ $mahasiswa->nim }}</div>
            
            <div class="prodi">{{ $mahasiswa->jurusan }}</div>
            
            <div class="footer">
                Berlaku Selamanya<br>
            </div>
        </div>

        <div class="content-right">
            <div class="qr-box">
                <div id="qrcode"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code saat halaman dibuka
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $mahasiswa->nim }}", // Isi QR Code = NIM Mahasiswa
            width: 70,
            height: 70,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>

</body>
</html>