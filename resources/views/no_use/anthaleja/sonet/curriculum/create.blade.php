@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.curriculum') }}</h1>

    <form action="{{ route('curriculum.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="skills" class="form-label">{{ __('messages.skills') }}</label>
            <textarea name="skills" id="skills" class="form-control">{{ old('skills', $curriculum->skills) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="experience" class="form-label">{{ __('messages.experience') }}</label>
            <textarea name="experience" id="experience" class="form-control">{{ old('experience', $curriculum->experience) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="education" class="form-label">{{ __('messages.education') }}</label>
            <textarea name="education" id="education" class="form-control">{{ old('education', $curriculum->education) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="cv_file" class="form-label">{{ __('messages.upload_cv') }}</label>
            <input type="file" name="cv_file" id="cv_file" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.save_curriculum') }}</button>
    </form>
@endsection
