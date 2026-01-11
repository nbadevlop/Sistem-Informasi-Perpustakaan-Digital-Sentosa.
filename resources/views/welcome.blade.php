<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital - Cari Buku</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="font-bold text-xl text-gray-800">{{ config('app.name', 'Perpustakaan') }}</span>
                </div>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800">Dashboard Admin</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Login Petugas</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-blue-600 py-20 px-4 text-center text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100"><circle cx="10" cy="10" r="20"></circle><circle cx="90" cy="80" r="15"></circle></svg>
        </div>

        <div class="relative z-10 max-w-3xl mx-auto">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Cari Referensi Bukumu Disini</h1>
            <p class="text-blue-100 mb-8 text-lg">Cek ketersediaan stok buku secara real-time tanpa perlu login.</p>
            
            <div class="relative">
                <input type="text" id="searchInput" 
                    class="w-full p-5 pl-12 rounded-full text-gray-800 shadow-lg text-lg focus:outline-none focus:ring-4 focus:ring-blue-300 transition" 
                    placeholder="Ketik Judul, Penulis, atau Penerbit..." autocomplete="off">
                
                <div class="absolute top-0 left-0 h-full flex items-center pl-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <div id="loading" class="absolute top-0 right-0 h-full flex items-center pr-5 hidden">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-screen">
        
        <div class="flex justify-between items-center mb-6">
            <h2 id="sectionTitle" class="text-xl font-bold text-gray-700 border-l-4 border-blue-600 pl-3">Buku Terbaru</h2>
            <span id="resultCount" class="text-sm text-gray-500 hidden">Menampilkan hasil pencarian...</span>
        </div>

        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($latestBooks as $buku)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden group">
                <div class="h-3 bg-blue-500 w-full"></div> <div class="p-5">
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</div>
                    <h3 class="font-bold text-lg text-gray-800 leading-tight mb-2 group-hover:text-blue-600 transition">{{ Str::limit($buku->judul, 40) }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $buku->penulis }}</p>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <div class="text-xs text-gray-400">
                            Rak: <span class="font-bold text-gray-600">{{ $buku->kategori->lokasi_rak ?? '-' }}</span>
                        </div>
                        
                        @if($buku->jumlah > 0)
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span> Tersedia ({{ $buku->jumlah }})
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span> Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div id="noData" class="hidden text-center py-20">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-xl font-bold text-gray-600">Yah, bukunya tidak ditemukan...</h3>
            <p class="text-gray-400">Coba cari dengan kata kunci lain.</p>
        </div>

    </div>

    <footer class="bg-white border-t py-6 mt-10">
        <div class="text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </footer>

    <script>
        const searchInput = document.getElementById('searchInput');
        const bookGrid = document.getElementById('bookGrid');
        const loading = document.getElementById('loading');
        const noData = document.getElementById('noData');
        const sectionTitle = document.getElementById('sectionTitle');
        const resultCount = document.getElementById('resultCount');

        let timeout = null;

        searchInput.addEventListener('keyup', function (e) {
            clearTimeout(timeout);
            const keyword = e.target.value;

            // Jika kosong, reload halaman agar kembali ke default (Opsional, atau biarkan kosong)
            if(keyword.length === 0) {
                // window.location.reload(); 
                // Atau biarkan user melihat hasil terakhir
                return;
            }

            // Delay pencarian 500ms agar tidak spam request
            timeout = setTimeout(() => {
                fetchBooks(keyword);
            }, 500);
        });

        function fetchBooks(keyword) {
            loading.classList.remove('hidden');
            sectionTitle.innerText = "Hasil Pencarian: '" + keyword + "'";
            resultCount.classList.remove('hidden');

            fetch("{{ route('books.search') }}?keyword=" + keyword)
                .then(response => response.json())
                .then(data => {
                    loading.classList.add('hidden');
                    bookGrid.innerHTML = ''; // Kosongkan grid

                    if (data.length > 0) {
                        noData.classList.add('hidden');
                        resultCount.innerText = "Ditemukan " + data.length + " buku.";
                        
                        data.forEach(buku => {
                            // Logic Stok
                            let stokHtml = '';
                            if(buku.jumlah > 0) {
                                stokHtml = `<span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span> Tersedia (${buku.jumlah})
                                            </span>`;
                            } else {
                                stokHtml = `<span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span> Habis
                                            </span>`;
                            }

                            // Logic Kategori & Rak (Handle null)
                            let kategori = buku.kategori ? buku.kategori.nama_kategori : 'Umum';
                            let rak = buku.kategori ? buku.kategori.lokasi_rak : '-';

                            // Append Card HTML
                            const card = `
                            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden group animate-fade-in">
                                <div class="h-3 bg-blue-500 w-full"></div>
                                <div class="p-5">
                                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">${kategori}</div>
                                    <h3 class="font-bold text-lg text-gray-800 leading-tight mb-2 group-hover:text-blue-600 transition">${buku.judul}</h3>
                                    <p class="text-sm text-gray-500 mb-4">${buku.penulis}</p>
                                    
                                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                        <div class="text-xs text-gray-400">
                                            Rak: <span class="font-bold text-gray-600">${rak}</span>
                                        </div>
                                        ${stokHtml}
                                    </div>
                                </div>
                            </div>
                            `;
                            bookGrid.innerHTML += card;
                        });
                    } else {
                        noData.classList.remove('hidden');
                        resultCount.innerText = "0 data ditemukan.";
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loading.classList.add('hidden');
                });
        }
    </script>

</body>
</html>