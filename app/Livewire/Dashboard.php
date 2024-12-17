<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';

    public function render()
    {
        $userIds = Message::where('from_user_id', auth()->id())
            ->orWhere('to_user_id', auth()->id())
            ->pluck('from_user_id')
            ->merge(Message::where('from_user_id', auth()->id())
                ->orWhere('to_user_id', auth()->id())
                ->pluck('to_user_id'))
            ->unique()
            ->reject(fn($id) => $id === auth()->id())
            ->values();

        $users = User::whereIn('id', $userIds)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->get()
            ->map(function($user) {
                $unreadCount = Message::where('from_user_id', $user->id)
                    ->where('to_user_id', auth()->id())
                    ->whereNull('read_at')
                    ->count();

                $lastMessage = Message::where(function($query) use ($user) {
                    $query->where(function($q) use ($user) {
                        $q->where('from_user_id', $user->id)
                          ->where('to_user_id', auth()->id());
                    })->orWhere(function($q) use ($user) {
                        $q->where('from_user_id', auth()->id())
                          ->where('to_user_id', $user->id);
                    });
                })
                ->latest()
                ->first();

                $user->last_message = $lastMessage;
                $user->has_unread = $unreadCount > 0;
                $user->unread_count = $unreadCount;
                return $user;
            })
            ->sortByDesc(function($user) {
                return $user->last_message?->created_at;
            })
            ->values();

        return view('livewire.dashboard', [
            'users' => $users
        ]);
    }
}
