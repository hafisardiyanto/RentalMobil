<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - RentalMobil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-600">RentalMobil</h1>
            <p class="text-slate-500 mt-2">Buat password baru untuk akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="Minimal 6 karakter">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required 
                    class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    placeholder="Ulangi password baru">
            </div>

            

            <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-colors shadow-lg shadow-indigo-200">
                Simpan 
            </button>
        </form>
    </div>
</body>
</html>
