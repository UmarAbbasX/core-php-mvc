<!DOCTYPE html>
<html lang="en" class="antialiased">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link href="<?= asset('build/css/app.css') ?>" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white font-sans min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white/10 backdrop-blur-md border-b border-white/20 fixed top-0 left-0 right-0 z-20">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold">CORE PHP MVC</div>
            <a href="/logout"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-full font-semibold transition">
                Logout
            </a>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow pt-24 mt-5 pb-12 px-6 max-w-4xl   w-full">
        <h1 class="text-xl font-bold">Welcome, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>!</h1>
        <p class="text-white/80 text-lg leading-relaxed">
            This is your dashboard. Customize it to fit your app's needs.
        </p>
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-4 text-white/50 text-sm select-none border-t border-white/10">
        Built with ❤️ by
        <a href="https://github.com/UmarAbbasX" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">
            Umar Abbas
        </a>
    </footer>


</body>

</html>