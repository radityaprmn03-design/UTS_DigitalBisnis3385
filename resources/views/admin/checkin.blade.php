<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Scanner Hari-H - Penjaga Pintu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen font-sans flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-slate-800 border-b border-slate-700 py-4 px-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <span class="text-3xl">🛡️</span>
            <div>
                <h1 class="font-extrabold text-xl tracking-tight">Scanner Penjaga Pintu</h1>
                <p class="text-xs text-slate-400">AmikomEventHub • Gate Verification System</p>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-xl text-xs font-bold transition">
            ← Kembali ke Dashboard
        </a>
    </header>

    <!-- Main Content -->
    <main class="max-w-md mx-auto w-full px-4 py-8 flex-1 flex flex-col justify-center">

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 p-5 bg-emerald-500/20 border-2 border-emerald-500 text-emerald-300 rounded-3xl text-center shadow-lg shadow-emerald-950 animate-bounce">
            <span class="text-4xl block mb-2">🎉</span>
            <div class="font-black text-lg">{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-5 bg-rose-500/20 border-2 border-rose-500 text-rose-300 rounded-3xl text-center shadow-lg shadow-rose-950">
            <span class="text-4xl block mb-2">⚠️</span>
            <div class="font-black text-lg">{{ session('error') }}</div>
        </div>
        @endif

        <!-- Scanner Card -->
        <div class="bg-slate-800 border border-slate-700 rounded-3xl p-6 shadow-2xl space-y-6">
            <div class="text-center">
                <h2 class="font-bold text-lg text-slate-200">Arahkan Kamera ke QR Code E-Ticket</h2>
                <p class="text-xs text-slate-400 mt-1">Sistem akan otomatis mengecek validitas tiket</p>
            </div>

            <!-- HTML5 QR Reader Container -->
            <div id="reader" class="overflow-hidden rounded-2xl border-2 border-indigo-500/50 bg-black min-h-[260px]"></div>

            <!-- Form Validation -->
            <form id="checkinForm" action="{{ route('admin.checkin.process') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Atau Input Kode Tiket (Order ID) Manual</label>
                    <div class="flex gap-2">
                        <input type="text" id="order_id_input" name="order_id" placeholder="TRX-1700000000-XXXXX" required
                            class="flex-1 px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono text-sm focus:outline-none focus:border-indigo-500 transition">
                        <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 font-bold text-sm rounded-xl transition shadow-lg shadow-indigo-900">
                            Check-in
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-500">
        Anti-Fraud Double Entry Protection • AmikomEventHub
    </footer>

    <!-- Script HTML5 QR Code -->
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);
            document.getElementById('order_id_input').value = decodedText;
            document.getElementById('checkinForm').submit();
        }

        function onScanFailure(error) {
            // silent scan fail handling
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 220, height: 220} },
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>
</html>
