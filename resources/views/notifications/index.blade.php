{{-- resources/views/notifications/index.blade.php --}}
@extends('front.layout.layout')

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <h2 class="text-2xl font-semibold mb-4">Notifications</h2>

    @if($unreadCount > 0)
        <div class="mb-4 text-sm text-gray-600">
            You had {{ $unreadCount }} unread notifications. Marked as read.
        </div>
    @endif

    <ul class="space-y-3">
        @forelse($notifications as $notification)
            <li class="border rounded p-3">
                <a href="{{ $notification->data['url'] ?? '#' }}" class="hover:underline">
                    {{ $notification->data['message'] ?? 'New notification' }}
                </a>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </li>
        @empty
            <li class="text-gray-500">No notifications found.</li>
        @endforelse
    </ul>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
