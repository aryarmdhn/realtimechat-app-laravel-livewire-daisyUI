<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class Chat extends Component
{
    public User $user;
    public $message = '';

    public function mount(User $user)
    {
        $this->user = $user;
        // Mark messages as read
        Message::where('from_user_id', $this->user->id)
            ->where('to_user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.chat', [
            'messages' => Message::where(function ($query) {
                $query->where('from_user_id', auth()->id())
                    ->where('to_user_id', $this->user->id);
            })->orWhere(function ($query) {
                $query->where('from_user_id', $this->user->id)
                    ->where('to_user_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get()
        ]);
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $this->user->id,
            'message' => $this->message,
        ]);

        $this->reset('message');
    }
}
