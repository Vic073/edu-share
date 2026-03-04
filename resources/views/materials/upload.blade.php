@extends('layouts.app')

@section('title', 'Upload Material — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-8 pb-12">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-dark-900 dark:text-white mb-2">Upload Material</h1>
            <p class="text-dark-500 dark:text-dark-400">Share your educational resources with students and colleagues.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Upload Form --}}
            <div class="lg:col-span-2">
                <div class="card p-6 md:p-8">
                    
                    @if($errors->any())
                    <div class="alert-error mb-6">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                        <ul class="text-sm text-red-700 dark:text-red-300 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        
                        {{-- Drop Zone --}}
                        <div class="dropzone mb-6" id="dropzone">
                            <input type="file" id="fileInput" name="file" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip" required>
                            
                            <div class="text-center pointer-events-none">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-primary-600 dark:text-primary-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-dark-900 dark:text-white mb-1">
                                    Drag & Drop Files Here
                                </h3>
                                <p class="text-dark-500 dark:text-dark-400 mb-4 text-sm">or click to browse</p>
                                <button type="button" class="btn-secondary pointer-events-auto">
                                    <i class="fas fa-folder-open"></i> Browse Files
                                </button>
                                <p class="text-xs text-dark-400 dark:text-dark-500 mt-4">
                                    Max file size: 50MB. Allowed: PDF, DOC, PPT, TXT, ZIP
                                </p>
                            </div>
                        </div>
                        
                        {{-- File Preview (app.js UI will inject here) --}}
                        <div id="filePreviewArea" class="hidden mb-6">
                            <h4 class="text-sm font-medium text-dark-700 dark:text-dark-300 mb-2">Selected File</h4>
                            <div id="fileList"></div>
                        </div>

                        {{-- Fields --}}
                        <div class="space-y-5">
                            
                            {{-- Title --}}
                            <div>
                                <label for="title" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                                    Material Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" required value="{{ old('title') }}"
                                       class="input" placeholder="e.g., Introduction to Computer Science Notes">
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="3" required
                                          class="input resize-none" placeholder="Brief description of the material contents...">{{ old('description') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Type --}}
                                <div>
                                    <label for="type" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                                        Material Type <span class="text-red-500">*</span>
                                    </label>
                                    <select id="type" name="type" required class="input bg-white dark:bg-dark-800">
                                        <option value="">Select type</option>
                                        <option value="lecture_notes" {{ old('type') == 'lecture_notes' ? 'selected' : '' }}>Lecture Notes</option>
                                        <option value="slides" {{ old('type') == 'slides' ? 'selected' : '' }}>Presentation Slides</option>
                                        <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment/Project</option>
                                        <option value="past_paper" {{ old('type') == 'past_paper' ? 'selected' : '' }}>Past Exam Paper</option>
                                        <option value="solution" {{ old('type') == 'solution' ? 'selected' : '' }}>Solutions/Answers</option>
                                        <option value="textbook" {{ old('type') == 'textbook' ? 'selected' : '' }}>Textbook/Ebook</option>
                                        <option value="study_guide" {{ old('type') == 'study_guide' ? 'selected' : '' }}>Study Guide</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                
                                {{-- Visibility --}}
                                <div>
                                    <label for="visibility" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                                        Visibility <span class="text-red-500">*</span>
                                    </label>
                                    <select id="visibility" name="visibility" required class="input bg-white dark:bg-dark-800">
                                        <option value="public" {{ old('visibility', 'public') == 'public' ? 'selected' : '' }}>Public — Visible to everyone</option>
                                        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private — Only visible to me</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                {{-- Institution --}}
                                <div>
                                    <label for="institution_id" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Institution</label>
                                    <select id="institution_id" name="institution_id" class="input bg-white dark:bg-dark-800">
                                        <option value="">My Institution</option>
                                        @foreach($institutions as $inst)
                                            <option value="{{ $inst->id }}" {{ (old('institution_id') ?? auth()->user()->institution_id) == $inst->id ? 'selected' : '' }}>
                                                {{ $inst->abbreviation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Course Code Fix --}}
                                <div class="md:col-span-2">
                                    <label for="course_code" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                                        Course Code/Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="course_code" name="course_code" required value="{{ old('course_code') }}"
                                           class="input" placeholder="e.g., CSC101 or Intro to Programming">
                                    <p class="text-xs text-dark-400 mt-1.5">Type the exact course code to help others find it.</p>
                                </div>
                            </div>

                        </div>

                        {{-- Terms checkbox --}}
                        <div class="mt-8 pt-6 border-t border-dark-200 dark:border-dark-700">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" name="terms" required class="w-5 h-5 mt-0.5 rounded border-dark-300 dark:border-dark-600 bg-white dark:bg-dark-900 text-primary-600 focus:ring-primary-500 cursor-pointer transition-all hover:bg-primary-50 dark:hover:bg-primary-900/10">
                                <span class="text-sm text-dark-600 dark:text-dark-400 leading-relaxed group-hover:text-dark-900 dark:group-hover:text-dark-200 transition-colors">
                                    I confirm that this material does not violate copyright laws and I have the right to share it. <span class="text-red-500">*</span>
                                </span>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-8 flex gap-3 justify-end">
                            <a href="{{ route('materials') }}" class="btn-secondary">Cancel</a>
                            <button type="submit" class="btn-primary px-8">
                                <i class="fas fa-cloud-upload-alt"></i> Upload
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Guidelines Sidebar --}}
            <div>
                <div class="card p-6 sticky top-24">
                    <h3 class="font-semibold text-dark-900 dark:text-white mb-5 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-amber-500"></i> Upload Guidelines
                    </h3>
                    
                    <ul class="space-y-5">
                        <li class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0 text-primary-600 dark:text-primary-400 font-bold text-sm">
                                1
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-dark-900 dark:text-white mb-0.5">Appropriate Content</h4>
                                <p class="text-xs text-dark-500 dark:text-dark-400 leading-relaxed">Only upload educational materials. No personal, sensitive, or malicious files allowed.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0 text-primary-600 dark:text-primary-400 font-bold text-sm">
                                2
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-dark-900 dark:text-white mb-0.5">Clear Information</h4>
                                <p class="text-xs text-dark-500 dark:text-dark-400 leading-relaxed">Use descriptive titles and accurate course codes so students can find your upload easily.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0 text-primary-600 dark:text-primary-400 font-bold text-sm">
                                3
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-dark-900 dark:text-white mb-0.5">Copyrighted Material</h4>
                                <p class="text-xs text-dark-500 dark:text-dark-400 leading-relaxed">Ensure you have the rights to share the document. Do not upload commercial textbooks.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
