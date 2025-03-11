<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    #[Validate('required|string')]
    public string $chat = '';
    public array $messages = [];
    public array $chats = [];
    public int $chat_id = 0;

    public function mount()
    {
        $this->chats=Auth::user()->conversations()->get()->toArray();
        if ($this->chat_id > 0) {
            $chats = Auth::user()->conversations()->where('id', $this->chat_id)->first()?->messages()->get();
            if ($chats) {
                foreach ($chats as $chat) {
                    $this->messages[] = [
                        'user' => ($chat->sender == 'user'),
                        'message' => $chat->content
                    ];
                }
            }
        }
    }

    #[NoReturn] public function message(): void
    {
        $this->validate();
        //$this->chat;  //here we are getting chat message after form submission
        $this->messages[] = [
            'user' => true,
            'message' => $this->chat
        ];
        $conversation_title = Str::substr($this->chat,0,180).'...';
        $conversation = Auth::user()->conversations()->find($this->chat_id) ?? Auth::user()->conversations()->create(['title' => $conversation_title]);
        //dd($conversation);
        $conversation->messages()->create([
            'sender'=>'user',
            'content'=>$this->chat
        ]);
        $this->dispatch('userMsg', user_msg: $this->chat,convID:$conversation->id);
        $this->chat = ''; // Clear input after sending
    }

    #[On('userMsg')]
    public function aiResponse($user_msg,$convID): void
    {
        sleep(1);
        $conversation = Auth::user()->conversations()->find($convID);
        $ai_response='Sure! What do you need help with you Message: ' . $user_msg;
        $this->messages[] = [
            'user' => false,
            'message' => $ai_response
        ];
        $conversation->messages()->create([
            'sender'=>'assistant',
            'content'=>$ai_response
        ]);
    }

}; ?>

<div class="flex">
    <!-- Sidebar -->
    <div class="max-w-1xl w-100 h-full flex flex-col p-4">
    <div class="w-64 bg-gray-800 p-4 space-y-4 overflow-y-auto h-full">
{{--        <h2 class="text-lg font-semibold">Previous Chats</h2>--}}
{{--        <ul>--}}
{{--            @foreach($chats as $chat)--}}
{{--            <li class="p-2 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600" wire>{{$chat['title']}}</li>--}}
{{--            @endforeach--}}
{{--            <li class="p-2 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600" >New Chat</li>--}}
{{--            <li class="p-2 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600">Chat 3</li>--}}
{{--        </ul>--}}
        <flux:navlist variant="outline">
            <flux:navlist.group heading="Previous Chats" class="grid">
                <flux:navlist.item icon="chat-bubble-bottom-center" :href="route('chat_page')" :current="request()->routeIs('chat_page')" wire:navigate>{{ __('New Chat') }}</flux:navlist.item>
                @foreach($chats as $chat)
                <flux:navlist.item icon="chat-bubble-bottom-center-text" :href="route('chat_page',$chat['id'])" :current="request()->routeIs('chat_page')" wire:navigate>{{ $chat['title'] }}</flux:navlist.item>
                @endforeach
            </flux:navlist.group>
        </flux:navlist>
    </div>
    </div>
    <div class="max-w-3xl w-full h-full flex flex-col p-4">
        <div class="flex-1 overflow-y-auto space-y-4 p-4 bg-gray-800 shadow-lg rounded-lg">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path fill="#FFD43B"
                              d="M9.4 86.6C-3.1 74.1-3.1 53.9 9.4 41.4s32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 9.4 86.6zM256 416l288 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-288 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/>
                    </svg>
                </div>
                <div class="bg-gray-700 p-3 rounded-lg max-w-lg">Hello! How can I assist you today?</div>
            </div>
            @foreach($messages as $message)
                @if($message['user'])
                    <div class="flex items-start space-x-3 justify-end">
                        <div class="bg-blue-500 p-3 rounded-lg max-w-lg">{{$message['message']}}</div>
                        {{--                        <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="User">--}}
                        <div class="w-10 h-10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path fill="#FFD43B"
                                      d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464l349.5 0c-8.9-63.3-63.3-112-129-112l-91.4 0c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3z"/>
                            </svg>
                        </div>
                    </div>
                @else
                    <div class="flex items-start space-x-3">
                        {{--                        <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full" alt="Bot">--}}
                        <div class="w-10 h-10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path fill="#FFD43B"
                                      d="M9.4 86.6C-3.1 74.1-3.1 53.9 9.4 41.4s32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 9.4 86.6zM256 416l288 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-288 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/>
                            </svg>
                        </div>
                        <div class="bg-gray-700 p-3 rounded-lg max-w-lg">{{$message['message']}}</div>
                    </div>
                @endif
            @endforeach

        </div>
        {{--        <div class="mt-4 flex items-center space-x-2 bg-gray-800 p-3 rounded-lg fixed bottom-0 w-full max-w-3xl mx-auto">--}}
        {{--            <input type="text" class="flex-1 p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Type a message...">--}}
        {{--            <button class="bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600">Send</button>--}}
        {{--        </div>--}}
        <div
            class="mt-4 flex items-center space-x-2 bg-gray-800 p-3 rounded-lg fixed bottom-0 w-full max-w-3xl mx-auto">
            <form wire:submit="message" class="w-full flex items-center space-x-2">
                <flux:input
                    wire:model="chat"
                    {{--                label="{{ __('Prompt Here') }}"--}}
                    type="text"
                    name="chat"
                    required
                    autofocus
                    autocomplete="message"
                    placeholder="Prompt Here"
                />
                {{--            <input type="text" class="flex-1 p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Type a message...">--}}
                <flux:button variant="primary" type="submit" class="w-20">{{ __('Send') }}</flux:button>
            </form>
        </div>
    </div>
</div>
