@extends('layouts.dashboard')

@section('title', 'Mon Profil - Étudiant')

@php
$user = auth()->user();
$regions = \App\Models\Region::all();
$allSkills = \App\Models\Skill::all();
$userSkills = $user->skills->pluck('id')->toArray();

$completion = 0;
if($user->name) $completion += 15;
if($user->email) $completion += 15;
if($user->phone) $completion += 10;
if($user->bio) $completion += 10;
if($user->region_id) $completion += 10;
if($user->search_type) $completion += 10;
if($user->availability_date) $completion += 10;
if($user->skills->count() > 0) $completion += 10;
if($user->cv_path) $completion += 10;
@endphp

@section('content')
<div class="p-8">
    <div class="mx-auto">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Mon Profil</h1>
        
        <!-- Completion Indicator -->
        <div class="mb-6">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-slate-500">Complétion du profil</span>
                <span class="text-indigo-600 font-medium">{{ $completion }}%</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full" style="width: <?php echo $completion; ?>%"></div>
            </div>
        </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar -->
                <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                    <div class="flex flex-col items-center">
                        <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden shadow-lg mb-3">
                            @if($user->avatar_path && file_exists(public_path($user->avatar_path)))
                                <img src="{{ asset($user->avatar_path) }}" alt="Avatar" class="h-full w-full object-cover">
                            @else
                                <span class="text-indigo-600 font-bold text-4xl">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <label class="inline-block cursor-pointer">
                            <span class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition inline-flex items-center gap-2">
                                <i class="bi bi-camera"></i> Changer
                            </span>
                            <input type="file" name="avatar" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                        <p class="text-xs text-slate-500 mt-1">JPG, PNG. Max 2Mo.</p>
                    </div>
                </div>

                <!-- Informations personnelles -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4"><i class="bi bi-person text-indigo-600 mr-2"></i>Informations personnelles</h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nom complet</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Région</label>
                            <select name="region_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Sélectionner une région</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ $user->region_id == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Bio / Présentation</label>
                        <textarea name="bio" rows="4" 
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Présentez-vous en quelques lignes...">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>

                <!-- Recherche d'emploi -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4"><i class="bi bi-briefcase text-indigo-600 mr-2"></i>Recherche d'emploi</h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Type de recherche</label>
                            <select name="search_type" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Sélectionner un type</option>
                                <option value="stage" {{ $user->search_type == 'stage' ? 'selected' : '' }}>Stage</option>
                                <option value="alternance" {{ $user->search_type == 'alternance' ? 'selected' : '' }}>Alternance</option>
                                <option value="premier_emploi" {{ $user->search_type == 'premier_emploi' ? 'selected' : '' }}>Premier emploi</option>
                                <option value="cdi" {{ $user->search_type == 'cdi' ? 'selected' : '' }}>CDI</option>
                                <option value="cdd" {{ $user->search_type == 'cdd' ? 'selected' : '' }}>CDD</option>
                                <option value="interim" {{ $user->search_type == 'interim' ? 'selected' : '' }}>Intérim</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date de disponibilité</label>
                            <input type="date" name="availability_date" value="{{ old('availability_date', $user->availability_date) }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-3">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_available" value="0">
                            <input type="checkbox" name="is_available" value="1" {{ $user->is_available ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ms-3 text-sm font-medium text-slate-700">Disponible immédiatement</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer">
                            <input type="hidden" name="driving_license" value="0">
                            <input type="checkbox" name="driving_license" value="1" {{ $user->driving_license ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ms-3 text-sm font-medium text-slate-700">Permis de conduire</span>
                        </label>
                    </div>
                </div>

                <!-- CV -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4"><i class="bi bi-file-earmark-text text-indigo-600 mr-2"></i>CV</h2>
                    
                    @if($user->cv_path && file_exists(public_path($user->cv_path)))
                        <div class="flex items-center justify-between bg-slate-50 p-4 rounded-lg mb-4">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-file-earmark-pdf text-2xl text-red-500"></i>
                                <span class="text-sm text-slate-700">CV uploadé</span>
                            </div>
                            <a href="{{ asset($user->cv_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-sm">
                                <i class="bi bi-eye mr-1"></i>Voir
                            </a>
                        </div>
                    @endif

                    <label class="block">
                        <span class="cursor-pointer bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-medium transition inline-flex items-center gap-2">
                            <i class="bi bi-upload"></i>
                            {{ $user->cv_path ? 'Changer le CV' : 'Uploader un CV' }}
                        </span>
                        <input type="file" name="cv" class="hidden" accept=".pdf,.doc,.docx">
                    </label>
                    <p class="text-sm text-slate-500 mt-2">PDF, DOC ou DOCX. Max 5Mo.</p>
                </div>

                <!-- Compétences -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4"><i class="bi bi-lightbulb text-indigo-600 mr-2"></i>Compétences</h2>
                    
                    <!-- Search Bar -->
                    <div class="relative mb-4">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="skill-search" placeholder="Rechercher une compétence..." 
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onkeyup="filterSkills(this.value)">
                    </div>
                    
                    <div class="flex flex-wrap gap-2" id="skills-container">
                        @foreach($allSkills as $skill)
                            <label class="skill-checkbox cursor-pointer" onclick="toggleSkill(this)">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" 
                                    {{ in_array($skill->id, $userSkills) ? 'checked' : '' }}
                                    class="hidden">
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 inline-block
                                    {{ in_array($skill->id, $userSkills) ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $skill->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <script>
                function toggleSkill(label) {
                    const checkbox = label.querySelector('input[type="checkbox"]');
                    const span = label.querySelector('span');
                    
                    checkbox.checked = !checkbox.checked;
                    
                    if (checkbox.checked) {
                        span.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                        span.classList.add('bg-indigo-600', 'text-white');
                    } else {
                        span.classList.remove('bg-indigo-600', 'text-white');
                        span.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    }
                }

                function filterSkills(searchTerm) {
                    const labels = document.querySelectorAll('.skill-checkbox');
                    const term = searchTerm.toLowerCase();
                    
                    labels.forEach(label => {
                        const skillName = label.querySelector('span').textContent.toLowerCase();
                        if (skillName.includes(term)) {
                            label.style.display = 'inline-flex';
                        } else {
                            label.style.display = 'none';
                        }
                    });
                }
                </script>

                <!-- Mot de passe (collapsible) -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <button type="button" onclick="this.querySelector('i').classList.toggle('bi-chevron-down'); this.querySelector('i').classList.toggle('bi-chevron-up'); document.getElementById('password-section').classList.toggle('hidden');" class="w-full flex items-center justify-between text-left">
                        <h2 class="text-lg font-semibold text-slate-800"><i class="bi bi-shield-lock text-indigo-600 mr-2"></i>Changer le mot de passe</h2>
                        <i class="bi bi-chevron-down text-slate-400"></i>
                    </button>
                    <div id="password-section" class="hidden mt-4">
                        <p class="text-sm text-slate-500 mb-4">Laissez vide si vous ne souhaitez pas changer de mot de passe.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mot de passe actuel</label>
                            <input type="password" name="current_password"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nouveau mot de passe</label>
                                <input type="password" name="password"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Confirmation</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition">
                        Annuler
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
