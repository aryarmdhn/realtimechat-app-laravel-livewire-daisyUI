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
                            placeholder="Search users..."
                            class="input input-bordered w-full pl-10" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- User List -->
                <div class="divide-y divide-gray-200" wire:poll.1s>
                    @foreach ($users as $user)
                        <div class="hover:bg-base-200 transition-colors duration-150">
                            <a href="{{ route('chat', $user) }}"
                                class="p-4 flex items-center space-x-4">
                                <div class="avatar">
                                    <div class="w-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" />
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-lg truncate">{{ $user->name }}</h3>
                                        <span class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between mt-1">
                                        @if($lastMessage = $user->messages()->latest()->first())
                                            <p class="text-sm text-gray-600 truncate max-w-[240px]">
                                                {{ $lastMessage->message }}
                                                <span class="text-xs text-gray-400 ml-2">
                                                    {{ $lastMessage->created_at->diffForHumans() }}
                                                </span>
                                            </p>
                                            @if($user->unreadMessages()->count() > 0)
                                                <div class="badge badge-primary">
                                                    {{ $user->unreadMessages()->count() }}
                                                </div>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-600">
                                                Click to start conversation
                                            </p>
                                            <div class="badge badge-secondary">New</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-none ml-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
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
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">
                                {{ $search ? 'No users found for "' . $search . '"' : 'No users found' }}
                            </h3>
                            <p class="text-gray-500">
                                {{ $search ? 'Try searching with a different term.' : 'Looks like there are no other users registered yet.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
