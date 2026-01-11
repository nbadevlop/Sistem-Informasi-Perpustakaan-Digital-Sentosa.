<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Perpustakaan Digital' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        
        /* Overwrite DataTables Style */
        .dataTables_wrapper .dataTables_length select { padding-right: 2rem; border-radius: 0.375rem; border-color: #d1d5db; }
        .dataTables_wrapper .dataTables_filter input { border-radius: 0.375rem; border-color: #d1d5db; padding: 0.5rem; }
        table.dataTable.no-footer { border-bottom: 1px solid #e5e7eb; }
        table.dataTable thead th, table.dataTable thead td { border-bottom: 1px solid #e5e7eb; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">
        
        <x-sidebar />

        <div class="flex-1 flex flex-col md:ml-64 transition-all duration-300">
            
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 sticky top-0 z-20">
                <div class="md:hidden font-bold text-lg text-gray-700">
                    Perpustakaan
                </div>
                
                <div class="hidden md:block"></div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Administrator' }}</div>
                        <div class="text-xs text-gray-500">Petugas Perpustakaan</div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200 shadow-sm">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="p-6 flex-1">
                {{ $slot }}
            </main>

            <footer class="bg-white border-t p-4 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </footer>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{ $script ?? '' }}

</body>
</html>