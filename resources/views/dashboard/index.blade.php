<x-layout>
    <x-slot:title>Dashboard Statistik</x-slot:title>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Mahasiswa</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-indigo-500 flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Judul Buku</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalJudulBuku }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500 flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Total Stok</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalStokBuku }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500 flex items-center">
             <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Sedang Dipinjam</p>
                <p class="text-2xl font-bold text-gray-800">{{ $statusPeminjaman['Dipinjam'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <h3 class="text-xl font-bold text-gray-700 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        Analisis Peminjaman
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-lg shadow-lg p-5 lg:col-span-2">
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-4">Tren Peminjaman Tahun Ini</h4>
            <div class="relative h-72">
                <canvas id="chartTren"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-5">
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-4">Rasio Peminjaman</h4>
            <div class="relative h-64 flex justify-center">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-5">
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-4">Top 5 Buku Terlaris</h4>
            <div class="relative h-64">
                <canvas id="chartTopBuku"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-5">
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-4">Top 5 Mahasiswa Terrajin</h4>
            <div class="relative h-64">
                <canvas id="chartTopMhs"></canvas>
            </div>
        </div>
    </div>

    <h3 class="text-xl font-bold text-gray-700 mb-4 mt-8 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        Analisis Demografi Mahasiswa
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-5">
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-4">Angkatan</h4>
            <canvas id="chartAngkatan"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-5">
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-4">Sebaran Fakultas</h4>
            <div class="relative h-64 flex justify-center"><canvas id="chartFakultas"></canvas></div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg p-5 mb-8">
        <h4 class="text-sm font-bold text-gray-500 uppercase mb-4">Jurusan Terpadat</h4>
        <canvas id="chartJurusan" height="80"></canvas>
    </div>

    <x-slot:script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // === 1. CHART TREN PEMINJAMAN (LINE) ===
            new Chart(document.getElementById('chartTren'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($trenPeminjaman->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: {!! json_encode($trenPeminjaman->values()) !!},
                        borderColor: '#3B82F6', // Blue
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4, // Garis melengkung
                        fill: true,
                        pointRadius: 5
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: {stepSize: 1} } } }
            });

            // === 2. CHART STATUS PEMINJAMAN (DOUGHNUT) ===
            new Chart(document.getElementById('chartStatus'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($statusPeminjaman->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($statusPeminjaman->values()) !!},
                        backgroundColor: ['#F59E0B', '#10B981'], // Yellow (Dipinjam), Green (Dikembalikan)
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });

            // === 3. CHART TOP BUKU (BAR HORIZONTAL) ===
            new Chart(document.getElementById('chartTopBuku'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topBuku->keys()) !!},
                    datasets: [{
                        label: 'Dipinjam (kali)',
                        data: {!! json_encode($topBuku->values()) !!},
                        backgroundColor: '#6366F1', // Indigo
                        borderRadius: 5
                    }]
                },
                options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
            });

            // === 4. CHART TOP MAHASISWA (BAR HORIZONTAL) ===
            new Chart(document.getElementById('chartTopMhs'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topMahasiswa->keys()) !!},
                    datasets: [{
                        label: 'Meminjam (kali)',
                        data: {!! json_encode($topMahasiswa->values()) !!},
                        backgroundColor: '#EC4899', // Pink
                        borderRadius: 5
                    }]
                },
                options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
            });

            // === 5. CHART DEMOGRAFI (YANG LAMA) ===
            const dataAngkatan = {!! json_encode($angkatanData) !!}; // Helper variable
            new Chart(document.getElementById('chartAngkatan'), {
                type: 'bar',
                data: {
                    labels: Object.keys(dataAngkatan),
                    datasets: [{ label: 'Jml Mhs', data: Object.values(dataAngkatan), backgroundColor: '#3B82F6' }]
                }
            });

            const dataFakultas = {!! json_encode($fakultasData) !!};
            new Chart(document.getElementById('chartFakultas'), {
                type: 'pie',
                data: {
                    labels: Object.keys(dataFakultas),
                    datasets: [{ data: Object.values(dataFakultas), backgroundColor: ['#EF4444', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6'] }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
            });

            const dataJurusan = {!! json_encode($jurusanData) !!};
            new Chart(document.getElementById('chartJurusan'), {
                type: 'bar',
                data: {
                    labels: Object.keys(dataJurusan),
                    datasets: [{ label: 'Jml Mhs', data: Object.values(dataJurusan), backgroundColor: '#10B981' }]
                }
            });
        </script>
    </x-slot:script>
</x-layout>