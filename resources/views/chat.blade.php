<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMT AI Chat</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-6 flex flex-col space-y-4">
        <h1 class="text-2xl font-bold text-center text-gray-800">💬 Chat with SM Technology AI</h1>

        <!-- Chat History -->
        <div id="chat-box" class="overflow-y-auto max-h-[500px] space-y-6 scroll-smooth">
            @foreach ($messages as $message)
                <!-- User message -->
                <div class="flex justify-end">
                    <div>
                        <p class="font-semibold text-blue-600 mb-1">You:</p>
                        <p class="bg-gray-200 p-3 rounded-2xl shadow-md text-gray-800 max-w-xs md:max-w-md">
                            {{ $message['query'] }}
                        </p>
                    </div>
                </div>

                <!-- AI message -->
                <div class="flex justify-start">
                    <div>
                        <p class="font-semibold text-green-600 text-right mb-1">AI:</p>

                        @if ($loop->last)
                            <!-- Streaming latest AI response -->
                            <div
                                x-data="{
                                    words: {{ Js::from($message['response']) }},
                                    index: 0,
                                    visibleWords: [],
                                    typing() {
                                        this.index = 0;
                                        this.visibleWords = [];
                                        const interval = setInterval(() => {
                                            if (this.index < this.words.length) {
                                                this.visibleWords.push(this.words[this.index]);
                                                this.index++;
                                            } else {
                                                clearInterval(interval);
                                            }
                                        }, 80);
                                    }
                                }"
                                x-init="typing()"
                                x-effect="if (visibleWords.length) { $nextTick(() => {
                                    const box = document.getElementById('chat-box');
                                    box.scrollTop = box.scrollHeight;
                                })}"
                                class="bg-blue-100 p-3 rounded-2xl shadow-md text-gray-800 max-w-xs md:max-w-md"
                            >
                                <template x-for="(word, i) in visibleWords" :key="i">
                                    <span x-text="word + ' '"></span>
                                </template>
                                <span class="animate-pulse text-gray-400">|</span>
                            </div>
                        @else
                            <!-- Fully rendered older AI responses -->
                            <div class="bg-blue-100 p-3 rounded-2xl shadow-md text-gray-800 max-w-xs md:max-w-md">
                                @foreach ($message['response'] as $word)
                                    <span>{{ $word }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Input Form -->
        <form method="POST" action="{{ route('ai.send') }}" class="space-y-3">
            @csrf
            <textarea
                name="query"
                rows="2"
                class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ask something..."
                required>{{ old('query') }}</textarea>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Send
            </button>
        </form>
    </div>
</body>
</html>
