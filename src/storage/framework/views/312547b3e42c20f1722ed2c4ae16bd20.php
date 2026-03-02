<?php $__env->startSection('title', 'Assistant IA'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-6">
    <div class="max-w-4xl mx-auto px-4">
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-8">

    <!-- Chat Interface -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Question Suggestions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-5 sticky top-24">
                <h3 class="font-semibold mb-4">Questions populaires</h3>
                <div class="space-y-2">
                    <button onclick="askQuestion('Quelles offres demandennt Python ?')" class="w-full text-left px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition text-sm">
                        "Quelles offres demandennt Python ?"
                    </button>
                    <button onclick="askQuestion('Des stages en data à Toulouse ?')" class="w-full text-left px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition text-sm">
                        "Des stages en data à Toulouse ?"
                    </button>
                    <button onclick="askQuestion('Des formations Bac+5 en marketing')" class="w-full text-left px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition text-sm">
                        "Des formations Bac+5 en marketing"
                    </button>
                    <button onclick="askQuestion('Métiers avec compétences JavaScript')" class="w-full text-left px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition text-sm">
                        "Métiers avec compétences JavaScript"
                    </button>
                    <button onclick="askQuestion('Alternances disponibles à Paris')" class="w-full text-left px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition text-sm">
                        "Alternances disponibles à Paris"
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Messages -->
                <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $chats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-end">
                            <div class="bg-indigo-600 text-white px-4 py-2 rounded-2xl rounded-br-md max-w-xs">
                                <?php echo e($chat->user_message); ?>

                            </div>
                        </div>
                        <?php if($chat->ai_response): ?>
                            <div class="flex justify-start">
                                <div class="bg-slate-100 text-slate-800 px-4 py-2 rounded-2xl rounded-bl-md max-w-xs">
                                    <?php echo e($chat->ai_response); ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-slate-400 py-8">
                            <i class="bi bi-chat-dots text-4xl mb-2"></i>
                            <p>Commencez une conversation...</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Input -->
                <form id="chatForm" class="border-t border-slate-200 p-4">
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            id="questionInput" 
                            class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none" 
                            placeholder="Ex: Quels métiers demandennt des compétences en Python et Data ?"
                            required
                        >
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function askQuestion(question) {
        document.getElementById('questionInput').value = question;
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }

    document.getElementById('chatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const input = document.getElementById('questionInput');
        const question = input.value;

        if (!question.trim()) return;

        // Add user message
        const messages = document.getElementById('chatMessages');
        messages.innerHTML += `
            <div class="flex justify-end">
                <div class="bg-indigo-600 text-white px-4 py-2 rounded-2xl rounded-br-md max-w-xs">
                    ${question}
                </div>
            </div>
            <div class="flex justify-start">
                <div class="bg-slate-100 text-slate-800 px-4 py-2 rounded-2xl rounded-bl-md">
                    <i class="bi bi-arrow-repeat animate-spin"></i> Recherche en cours...
                </div>
            </div>
        `;
        messages.scrollTop = messages.scrollHeight;
        input.value = '';

        const formData = new FormData();
        formData.append('question', question);

        try {
            const response = await fetch('<?php echo e(route("chatbot.ask")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                },
                body: formData
            });

            const data = await response.json();
            
            // Remove loading message
            messages.querySelector('.animate-spin')?.parentElement?.remove();

            let responseHtml = '<div class="flex justify-start"><div class="bg-slate-100 text-slate-800 px-4 py-2 rounded-2xl rounded-bl-md max-w-xs">';
            
            if (data.success && data.results && data.results.length > 0) {
                responseHtml += `<strong>${data.explanation || data.results.length + ' résultat(s) trouvé(s)'}</strong><br>`;
                data.results.slice(0, 3).forEach(r => {
                    responseHtml += `- ${r.title || r.name || JSON.stringify(r)}<br>`;
                });
            } else if (data.error) {
                responseHtml += `<span class="text-red-500">${data.error}</span>`;
            } else {
                responseHtml += data.reformulation || 'Question traitée. Essayez une question plus précise.';
            }
            
            responseHtml += '</div></div>';
            messages.innerHTML += responseHtml;
            messages.scrollTop = messages.scrollHeight;
        } catch (error) {
            console.error('Error:', error);
            messages.querySelector('.animate-spin')?.parentElement?.remove();
            messages.innerHTML += `
                <div class="flex justify-start">
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded-2xl rounded-bl-md">
                        Une erreur est survenue. Veuillez réessayer.
                    </div>
                </div>
            `;
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/chatbot/index.blade.php ENDPATH**/ ?>