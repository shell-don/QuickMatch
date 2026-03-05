<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-100 p-4 md:p-10 font-sans">

    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-3xl overflow-hidden flex flex-col h-[85vh]">
        
        <div class="bg-blue-600 p-6 text-white flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold italic">🚀 Assistant QuickMatch</h1>
                <p class="text-xs opacity-90">Connecté à notre base de données (Ollama + MySQL)</p>
            </div>
            <span class="text-[10px] bg-white/20 px-3 py-1 rounded-full uppercase">Llama3 Mode</span>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50" id="chat-box">
            @if(isset($question))
                <div class="flex justify-end">
                    <div class="bg-blue-600 text-white px-5 py-3 rounded-2xl rounded-tr-none shadow-md max-w-[80%] text-sm">
                        {{ $question }}
                    </div>
                </div>

                <div class="flex justify-start items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                        <i class="fas fa-robot text-sm"></i>
                    </div>
                    <div class="max-w-[85%] space-y-4">
                        <div class="bg-white border border-gray-200 p-5 rounded-2xl rounded-tl-none shadow-sm text-gray-700 text-sm leading-relaxed">
                            <span class="text-[10px] font-bold text-blue-600 uppercase block mb-2">Réponse Assistant</span>
                            {!! nl2br(e($reponse)) !!}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($entreprises as $ent)
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                    <h4 class="font-bold text-blue-800 text-xs">{{ $ent->nom_complet }}</h4>
                                    <p class="text-[10px] text-gray-400 italic mb-2">{{ $ent->secteur }}</p>
                                    <p class="text-[10px] font-mono bg-slate-50 p-1 rounded">SIREN: {{ $ent->siren }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                    <i class="fas fa-search text-4xl mb-3 opacity-20"></i>
                    <p class="text-sm">Recherchez un domaine, une ville ou un stage...</p>
                </div>
            @endif
        </div>

        <div class="p-6 bg-white border-t border-gray-100">
            <form action="{{ route('ollama.ask') }}" method="POST" id="ask-form" class="flex gap-2">
                @csrf
                <input type="text" name="question" required placeholder="Ex: Informatique à Toulouse..." 
                       class="flex-1 p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none text-sm shadow-inner">
                <button type="submit" id="btn-submit" class="bg-blue-600 text-white px-6 rounded-2xl hover:bg-blue-700 transition-all font-bold text-sm shadow-lg">
                    Envoyer
                </button>
            </form>
            <div id="loader" class="hidden mt-3 text-center text-[11px] text-blue-500 font-bold animate-pulse">
                <i class="fas fa-spinner animate-spin"></i> L'IA parcourt notre base de données pour vous répondre...
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('ask-form');
        form.addEventListener('submit', () => {
            document.getElementById('loader').classList.remove('hidden');
            document.getElementById('btn-submit').disabled = true;
        });
        const box = document.getElementById('chat-box');
        box.scrollTop = box.scrollHeight;
    </script>
</body>
</html>