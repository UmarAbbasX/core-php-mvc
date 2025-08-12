<!DOCTYPE html>
<html lang="en" class="antialiased">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="<?= asset('build/css/app.css') ?>" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white font-sans min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 shadow-lg px-8 pt-6 pb-8">

        <h2 class="text-3xl font-bold mb-6 text-center select-none">Login</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="bg-red-700/20 text-red-300 px-4 py-2 rounded mb-4 font-mono text-sm select-text">
                <?= htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="bg-green-700/20 text-green-300 px-4 py-2 rounded mb-4 font-mono text-sm select-text">
                <?= htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/login" class="space-y-6">
            <?= csrf_field() ?>

            <div>
                <label class="block text-white/80 text-sm font-semibold mb-2" for="email">Email</label>
                <input id="email" type="email" name="email" required
                    class="w-full rounded-md bg-white/10 border border-white/30 text-white placeholder-white/50 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label class="block text-white/80 text-sm font-semibold mb-2" for="password">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full rounded-md bg-white/10 border border-white/30 text-white placeholder-white/50 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 rounded-full font-semibold py-3 text-white transition select-none">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-white/70 select-none">
            Don't have an account?
            <a href="/register" class="text-blue-400 hover:underline">Register</a>
        </p>
    </div>

</body>

</html>