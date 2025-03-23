<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Response</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white shadow-xl rounded-2xl p-6 max-w-2xl w-full">
        <h1 class="text-2xl font-bold mb-4 text-center">💬 AI Response</h1>

        <div
            x-data="{ words: {{ Js::from($words) }}, index: 0, interval: null }"
            x-init="interval = setInterval(() => {
                if (index < words.length) index++;
                else clearInterval(interval);
            }, 150)"
            class="text-gray-800 text-lg leading-relaxed"
        >
            <template x-for="(word, i) in words.slice(0, index)" :key="i">
                <span x-text="word + ' '"></span>
            </template>
        </div>
    </div>
</body>
</html>
