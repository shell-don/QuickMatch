Configuration de l'IA (Ollama)
Pour que l'assistant fonctionne, vous devez :

Installer Ollama sur votre machine (ollama.com).

Lancer la commande suivante dans votre terminal :

Bash
ollama pull llama3.2:1b
Lancer les migrations et les données de test :

Bash
php artisan migrate:fresh --seed
