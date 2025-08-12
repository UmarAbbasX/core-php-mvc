<!DOCTYPE html>
<html lang="en" class="antialiased">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CORE PHP MVC</title>
    <link href="<?= asset('build/css/app.css') ?>" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white font-sans min-h-screen">

    <!-- Auth Buttons for Desktop: Top Right -->
    <div class="hidden md:flex absolute top-6 right-6 space-x-3 z-10">
        <a href="/login"
            class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-full border border-white/10 font-semibold transition">
            Login
        </a>
        <a href="/register"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-full font-semibold transition">
            Register
        </a>
    </div>

    <!-- Main Card -->
    <div class="min-h-screen flex items-center justify-center p-6">

        <div
            class="max-w-4xl w-full rounded-2xl border border-white/10 bg-white/5 backdrop-blur-lg overflow-hidden shadow-xl flex flex-col md:flex-row">

            <!-- Auth Buttons for Mobile: Inside card, top center -->
            <div class="flex md:hidden justify-center space-x-3 pt-6 pb-4 border-b border-white/10">
                <a href="/login"
                    class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-full border border-white/10 font-semibold transition">
                    Login
                </a>
                <a href="/register"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-full font-semibold transition">
                    Register
                </a>
            </div>

            <!-- Left Column -->
            <div class="flex-1 p-8 flex flex-col justify-center space-y-6 text-center md:text-left">
                <h1 class="text-3xl font-bold">Let's get started</h1>
                <p class="text-white/80 max-w-xl leading-relaxed mt-2">
                    I built <strong>core-php-mvc</strong>, a simple and lightweight core PHP setup<br />
                    designed for fast and easy development without unnecessary complexity.
                </p>

                <div class="flex flex-col sm:flex-row sm:justify-center md:justify-start gap-3 mt-4">
                    <a href="https://github.com/UmarAbbasX/core-php-mvc" target="_blank"
                        class="px-4 py-2 rounded-full border border-white/10 bg-white/10 hover:bg-white/20 transition">
                        Project Repository
                    </a>
                    <a href="https://github.com/UmarAbbasX/core-php-mvc/issues" target="_blank"
                        class="px-4 py-2 rounded-full border border-red-400/30 text-red-400 hover:bg-red-400/10 hover:border-red-400/50 transition">
                        Report Issues
                    </a>
                </div>

                <p class="mt-6 text-sm text-white/60 max-w-xl">
                    Explore, Contribute, or Reach out if you want to collaborate or suggest improvements.
                </p>
            </div>


            <!-- Right Column -->
            <div
                class="w-full md:w-72 border-t md:border-t-0 md:border-l border-white/10 flex flex-col items-center justify-center p-8 space-y-8 bg-white/10 rounded-lg shadow-lg backdrop-blur-sm">
                <a href="https://github.com/umarabbas" target="_blank" class="inline-block">
                    <img src="https://avatars.githubusercontent.com/u/225075108" alt="Avatar"
                        class=" rounded-full border-2 border-white hover:border-blue-500 transition" />
                </a>

                <a href="https://github.com/umarabbas" target="_blank"
                    class="text-lg font-semibold hover:text-blue-400 transition">
                    Umar Abbas
                </a>

                <!-- Social Icons with backdrop blur -->
                <div
                    class="flex space-x-8 bg-white rounded-full px-6 py-3 shadow-md backdrop-blur-sm items-center justify-center">
                    <a href="https://github.com/UmarAbbasX" target="_blank" aria-label="GitHub"
                        class="transform transition hover:scale-110 hover:text-blue-400">
                        <img src="https://i.ibb.co/8njHvg0c/github.png" alt="GitHub" class="w-7 h-7" />
                    </a>
                    <a href="https://discord.com/users/1246577121359433828" target="_blank" aria-label="Discord"
                        class="transform transition hover:scale-110 hover:text-indigo-400">
                        <img src="https://i.ibb.co/twHPmDRT/discord.png" alt="Discord" class="w-7 h-7" />
                    </a>
                    <a href="https://linkedin.com/in/UmarAbbasX" target="_blank" aria-label="LinkedIn"
                        class="transform transition hover:scale-110 hover:text-blue-600">
                        <img src="https://i.ibb.co/NdQvsxbT/linkedin.png" alt="LinkedIn" class="w-7 h-7" />
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script src="<?= asset('build/js/app.js') ?>"></script>
</body>

</html>