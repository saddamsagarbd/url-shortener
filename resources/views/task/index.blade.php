<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a href="{{ route('task.create') }}" class="btn btn-primary">{{ __('Create Task') }}</a>
            <div class="clr"></div>
            <ul class="list-group">
                @forelse($tasks as $task)
                    <a href="{{ route('task.show', ['id' => $task->id]) }}">
                        @if($task->completed)
                            <li class="list-group-item text-decoration-line-through disabled">{{ $task->title }}</li>
                        @else
                            <li class="list-group-item">{{ $task->title }}</li>
                        @endif
                    </a>
                @empty
                    <li class="list-group-item disabled">No Task found</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>