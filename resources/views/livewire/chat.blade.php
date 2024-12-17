<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col h-[80vh]">
                <!-- Header Chat -->
                <div class="bg-base-200 p-4 border-b">
                    <div class="flex items-center space-x-4">
                        <div class="avatar online">
                            <div class="w-12 rounded-full">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" />
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">Active now</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div class="flex-1 overflow-y-auto p-6 bg-base-100" id="chat-messages" x-data x-init="() => {
                    $el.scrollTop = $el.scrollHeight;
                    Livewire.on('messageSent', () => {
                        setTimeout(() => {
                            $el.scrollTop = $el.scrollHeight;
                        }, 100);
                    });
                }" wire:poll.1s>
                    <div class="space-y-6">
                        @foreach ($messages as $msg)
                            <div @class([
                                'chat',
                                'chat-end' => $msg->from_user_id == auth()->id(),
                                'chat-start' => $msg->from_user_id != auth()->id(),
                            ])>
                                <div class="chat-image avatar">
                                    <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->fromUser->name) }}&background=random" />
                                    </div>
                                </div>
                                <div class="chat-header mb-1">
                                    {{ $msg->fromUser->name }}
                                    <time class="text-xs opacity-50 ml-1">{{ $msg->created_at->format('h:i A') }}</time>
                                </div>
                                <div @class([
                                    'chat-bubble',
                                    'chat-bubble-primary' => $msg->from_user_id == auth()->id(),
                                ])>
                                    {{ $msg->message }}
                                </div>
                                <div class="chat-footer opacity-50 text-xs flex items-center gap-1">
                                    {{ $msg->created_at->diffForHumans() }}
                                    @if($msg->from_user_id == auth()->id())
                                        <span class="inline-flex items-center">
                                            •
                                            @if($msg->read_at)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-blue-500 ml-1">
                                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 ml-1">
                                                    <path d="M3.505 2.365A41.369 41.369 0 019 2c1.863 0 3.697.124 5.495.365 1.247.167 2.18 1.108 2.435 2.268a4.45 4.45 0 00-.577-.069 43.141 43.141 0 00-4.706 0C9.229 4.696 7.5 6.727 7.5 8.998v2.24c0 1.413.67 2.735 1.76 3.562l-2.98 2.98A.75.75 0 015 17.25v-3.443c-.501-.048-1-.106-1.495-.172C2.033 13.438 1 12.162 1 10.72V5.28c0-1.441 1.033-2.717 2.505-2.914z" />
                                                </svg>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Message Input Area -->
                <div class="p-4 bg-base-200 border-t">
                    <form wire:submit="sendMessage" class="flex items-end gap-4">
                        <div class="flex-1">
                            <textarea
                                wire:model="message"
                                class="textarea textarea-bordered w-full bg-white resize-none"
                                placeholder="Type your message here..."
                                rows="1"
                                x-data="{ resize: () => { $el.style.height = '5px'; $el.style.height = $el.scrollHeight + 'px' } }"
                                x-init="resize()"
                                @input="resize()"
                            ></textarea>
                        </div>
                        <div class="flex-none">
                            <button type="submit" class="btn btn-primary gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
