@extends('layouts.app')

@section('title', $news->title)

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <x-layout.breadcrumbs :items="[
            ['label' => 'Actualités', 'url' => route('news.index')],
            ['label' => Str::limit($news->title, 40)]
        ]" />
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-8">
    <article class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($news->image_url)
            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-64 object-cover">
        @else
            <div class="w-full h-64 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                <i class="bi bi-newspaper text-6xl text-white/50"></i>
            </div>
        @endif
        
        <div class="p-8">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">
                    {{ $news->category ?? 'Actualité' }}
                </span>
                <span class="text-slate-400 text-sm">
                    <i class="bi bi-calendar3 mr-1"></i>{{ $news->published_at?->format('d/m/Y') }}
                </span>
                <span class="text-slate-400 text-sm">
                    <i class="bi bi-source mr-1"></i>{{ $news->source }}
                </span>
            </div>
            
            <h1 class="text-2xl font-bold text-slate-800 mb-6">{{ $news->title }}</h1>
            
            @if($news->ai_summary)
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-6 rounded-r-lg">
                    <p class="text-indigo-800">
                        <i class="bi bi-stars text-indigo-600 mr-2"></i>
                        <strong>Résumé IA:</strong> {{ $news->ai_summary }}
                    </p>
                </div>
            @endif
            
            @if($news->summary)
                <p class="text-lg text-slate-600 mb-6">{{ $news->summary }}</p>
            @endif
            
            @if($news->content)
                <div class="prose prose-slate max-w-none">
                    {!! nl2br(e($news->content)) !!}
                </div>
            @endif
            
            @if($news->url)
                <div class="mt-8 pt-6 border-t border-slate-200">
                    <a href="{{ $news->url }}" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium">
                        <i class="bi bi-box-arrow-up-right"></i> Lire l'article complet
                    </a>
                </div>
            @endif
        </div>
    </article>
    
    <div class="mt-6">
        <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600">
            <i class="bi bi-arrow-left"></i> Retour aux actualités
        </a>
    </div>
</div>
@endsection
