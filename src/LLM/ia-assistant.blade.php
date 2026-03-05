<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant QuickMatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        #chat-box::-webkit-scrollbar {
            width: 6px;
        }
        #chat-box::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-slate-100 p-2 md:p-10 font-sans leading-normal tracking-normal">

    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-3xl overflow-hidden flex flex-col h-[90vh]">
        
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white flex items-center justify-between shadow-lg">
            <div>
                <h1 class="text-xl font-bold italic flex items-center gap-2">
                    <i class="fas fa-rocket"></i> Assistant QuickMatch
                </h1>
                <p class="text-xs opacity-90 font-medium">Intelligence Artificielle & Base de données Réelle</p>
            </div>
            <div class="flex flex-col items-end gap-1">
                <span class="text-[10px] bg-white/20 px-3 py-1 rounded-full uppercase font-bold tracking-wider">Llama 3.2 Mode</span>
                <span class="text-[9px] text-blue-100 italic">Ollama Local Instance</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6 bg-slate-50/50" id="chat-box">
            
            @if(isset($question))
                <div class="flex justify-end mb-4">
                    <div class="bg-blue-600 text-white px-5 py-3 rounded-2xl rounded-tr-none shadow-md max-w-[85%] text-sm">
                        <p class="font-semibold text-[10px] uppercase opacity-75 mb-1 text-right">Vous</p>
                        {{ $question }}
                    </div>
                </div>

                <div class="flex justify-start items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-lg shrink-0">
                        <i class="fas fa-robot text-sm"></i>
                    </div>
                    
                    <div class="max-w-[90%] space-y-4">
                        <div class="bg-white border border-gray-100 p-6 rounded-2xl rounded-tl-none shadow-sm text-gray-800 text-sm leading-relaxed relative">
                            <span class="text-[10px] font-black text-blue-600 uppercase block mb-3 tracking-widest">Réponse QuickMatch</span>
                            <div class="prose prose-sm prose-blue">
                                {!! nl2br(e($reponse)) !!}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            @if(isset($entreprises) && count($entreprises) > 0)
                                @foreach($entreprises as $ent)
                                    <div class="bg-white p-4 rounded-xl border border-blue-50 shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-bold text-blue-900 text-xs truncate mr-2">{{ $ent->nom_complet }}</h4>
                                            <span class="text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-bold uppercase shrink-0">Stage</span>
                                        </div>
                                        
                                        <p class="text-[11px] text-blue-600 font-medium mb-1 flex items-center gap-1">
                                            <i class="fas fa-briefcase opacity-70"></i> {{ $ent->stage_titre ?? 'Poste à pourvoir' }}
                                        </p>
                                        
                                        <div class="flex items-center gap-2 text-[10px] text-gray-500 mb-3">
                                            <span class="flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> {{ $ent->ville }}</span>
                                            <span class="opacity-30">|</span>
                                            <span class="flex items-center gap-1"><i class="fas fa-industry"></i> {{ Str::limit($ent->secteur, 15) }}</span>
                                        </div>

                                        <div class="pt-3 border-t border-gray-50 flex justify-between items-center">
                                            <p class="text-[9px] font-mono text-gray-400">SIREN: {{ $ent->siren ?? 'Non renseigné' }}</p>
                                            <a href="mailto:{{ $ent->email_Ent }}" class="text-[10px] text-blue-600 font-bold hover:underline">
                                                Postuler <i class="fas fa-paper-plane ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-full bg-orange-50 border border-orange-100 p-3 rounded-lg text-[11px] text-orange-700 flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i> Aucun détail de fiche entreprise disponible pour cette recherche.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-inner">
                        <i class="fas fa-comment-dots text-4xl opacity-20"></i>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-semibold text-gray-500">Prêt à vous aider !</p>
                        <p class="text-xs opacity-75">Posez une question comme "Quels sont les stages en informatique à Toulouse ?"</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="p-6 bg-white border-t border-gray-100 shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.05)]">
            <form action="{{ route('ollama.ask') }}" method="POST" id="ask-form" class="relative">
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="question" required 
                           placeholder="Posez votre question ici..." 
                           class="flex-1 p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 outline-none text-sm transition-all shadow-inner">
                    
                    <button type="submit" id="btn-submit" 
                            class="bg-blue-600 text-white px-8 rounded-2xl hover:bg-blue-700 active:scale-95 transition-all font-bold text-sm shadow-lg shadow-blue-200 flex items-center gap-2">
                        <span>Envoyer</span>
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </div>

                <div id="loader" class="hidden absolute -top-10 left-0 right-0 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[11px] font-bold animate-pulse border border-blue-100 shadow-sm">
                        <i class="fas fa-circle-notch animate-spin"></i> L'IA analyse votre requête...
                    </div>
                </div>
            </form>
            <p class="mt-4 text-[9px] text-center text-gray-400 uppercase tracking-tighter">Powered by QuickMatch AI Engine v2.0 - 2024</p>
        </div>
    </div>

    <script>
        const form = document.getElementById('ask-form');
        const loader = document.getElementById('loader');
        const btn = document.getElementById('btn-submit');
        const box = document.getElementById('chat-box');

        form.addEventListener('submit', () => {
            loader.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        });

        // Auto-scroll to bottom
        window.onload = () => {
            box.scrollTop = box.scrollHeight;
        };
    </script>
</body>
</html>