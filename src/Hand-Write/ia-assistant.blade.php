<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant TrouveTonStage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">

    <div class="max-w-3xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden">
        <div class="bg-blue-600 p-6">
            <h1 class="text-white text-2xl font-bold flex items-center">
                <span class="mr-2">🚀</span> Assistant TrouveTonStage
            </h1>
            <p class="text-blue-100 text-sm">Pose tes questions à notre IA locale (Ollama).</p>
        </div>

        <div class="p-8">
            <form action="{{ route('ollama.ask') }}" method="POST" id="chatForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Comment puis-je t'aider aujourd'hui ?</label>
                    <textarea 
                        name="question" 
                        id="question"
                        rows="3" 
                        class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none border-gray-300"
                        placeholder="Ex: Aide-moi à corriger mon mail de candidature spontanée..."
                        required
                    >{{ old('question', $question ?? '') }}</textarea>
                </div>
                <button type="submit" id="submitBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-300 flex justify-center items-center">
                    <span id="btnText">Générer une réponse</span>
                    <svg id="spinner" class="hidden animate-spin ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            {{-- Zone de réponse --}}
            @if(isset($reponse))
                <div class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-100">
                    <h3 class="font-bold text-gray-800 mb-2">💡 Réponse de l'IA :</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $reponse }}</p>
                </div>
            @endif

            {{-- Zone d'erreur --}}
            @if(isset($error))
                <div class="mt-6 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200">
                    <strong>Erreur :</strong> {{ $error }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('chatForm').onsubmit = function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('btnText').innerText = "L'IA réfléchit...";
            document.getElementById('spinner').classList.remove('hidden');
        };
    </script>
</body>
</html>