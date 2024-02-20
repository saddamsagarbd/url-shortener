<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
        <div class="card">
            <h5 class="card-header">{{ $task->title }}</h5>
            <div class="card-body">
                <h5 class="card-title">{{ $task->title }}</h5>
                <p class="card-text">{{ $task->description }}</p>
                <p class="card-text">{{ $task->long_description }}</p>
                <a href="#" class="btn btn-primary">{{ ($task->completed)?"Complete":"Not complete" }}</a>
                <a href="{{ route('task.index') }}" class="btn btn-primary">Go Back</a>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>