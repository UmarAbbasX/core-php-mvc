<!DOCTYPE html>
<html lang="en" class="antialiased">

<head>
    <meta charset="utf-8" />
    <title>403 Forbidden</title>
    <link href="<?= asset('build/css/app.css') ?>" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white font-sans min-h-screen flex items-center justify-center p-6">

    <div class="max-w-2xl w-full bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 shadow-lg p-10 text-center">

        <h1 class="text-8xl font-extrabold text-yellow-400 mb-6 select-none">403</h1>

        <p class="mb-8 text-white/80 text-lg leading-relaxed">
            <?php if (Core\Env::get('APP_DEBUG') === 'true' && !empty($errorMessage)): ?>
                <?= htmlspecialchars($errorMessage) ?>
            <?php else: ?>
                You don’t have permission to access this page.
            <?php endif; ?>
        </p>

        <?php if (Core\Env::get('APP_DEBUG') === 'true' && !empty($errorDetails)): ?>
            <pre class="bg-black/50 text-yellow-300 p-4 rounded max-w-full overflow-auto whitespace-pre-wrap text-left mb-8"><?= htmlspecialchars($errorDetails) ?></pre>
        <?php endif; ?>

        <a href="/" class="inline-block px-6 py-3 bg-yellow-500 hover:bg-yellow-600 rounded-full font-semibold transition select-none">
            Go Home
        </a>

    </div>

</body>

</html>