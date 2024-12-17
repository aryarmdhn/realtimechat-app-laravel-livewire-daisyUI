<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Search Bar -->
                <div class="p-4 border-b">
                    <div class="relative">
                        <input type="text"
                            wire:model.live="search"
                            placeholder="Search messages..."
                            class="input input-bordered w-full pl-10" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Chat List -->
                <div class="divide-y divide-gray-200" wire:poll.1s>
                    @foreach ($users as $user)
                        <div class="hover:bg-base-200 transition-colors duration-150">
                            <a href="{{ route('chat', $user) }}"
                                class="p-4 flex items-center space-x-4">
                                <!-- Avatar with online indicator -->
                                <div class="avatar {{ $user->has_unread ? 'online' : '' }}">
                                    <div class="w-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" />
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <!-- User name and time -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <h3 class="font-semibold text-lg truncate">{{ $user->name }}</h3>
                                            @if($user->has_unread)
                                                <div class="w-2 h-2 bg-primary rounded-full ml-2"></div>
                                            @endif
                                        </div>
                                        @if($user->lastMessage)
                                            <span class="text-xs text-gray-500">
                                                {{ $user->lastMessage->created_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Last message -->
                                    <div class="flex items-center justify-between mt-1">
                                        @if($lastMessage = $user->lastMessage)
                                            <p class="text-sm {{ $user->has_unread ? 'font-medium text-gray-900' : 'text-gray-600' }} truncate max-w-[240px]">
                                                @if($lastMessage->from_user_id === auth()->id())
                                                    <span class="text-gray-400">You: </span>
                                                @endif
                                                {{ $lastMessage->message }}
                                            </p>
                                            @if($user->has_unread)
                                                <div class="badge badge-primary ml-2">{{ $user->unread_count }} new</div>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-600">
                                                Start a conversation
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                    @if($users->isEmpty())
                        <div class="p-8 text-center">
                            <div class="inline-flex mx-auto items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">
                                {{ $search ? 'No results found for "' . $search . '"' : 'No messages yet' }}
                            </h3>
                            <p class="text-gray-500">
                                {{ $search ? 'Try searching with different keywords' : 'Start a conversation with someone' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
