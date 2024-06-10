<div class='container mx-auto h-screen flex flex-col py-5'>
    <div class='text-gray-300 text-lg '>AI Testing</div>
    <div class="rounded-2xl p-4 flex-grow bg-[#2c2d31] my-2">
        <h3 class='text-gray-300 text-lg py-4'>Conversation with Assistant Junn</h3>
        @foreach ($conversation as $message)
            <div class='flex'>
                <div class='text-[#5ba261] w-[50px] min-w-[50px] pb-5'>
                    <strong>
                        <img width='42px' src="{{ $message['role'] === 'user' ? asset('images/junn.png') : asset('images/assistant.png') }}"/>
                    </strong>
                </div>
                <div class='text-gray-200 pl-10 pb-5'>
                    @if ($message['role'] === 'user')
                        <p>{{ $message['content'] }}</p>
                    @else
                        {!! $message['content'] !!}
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="submit">
        <div class='flex'>
            <label class='text-[#5ba261] flex-nowrap m-2 my-5' for="query">Ask&nbsp;something:</label>
            <input class='pl-10 my-5 rounded-full p-2 w-full bg-[#2c2d31] text-white' type="text" id="query" wire:model="query">

            <label class='text-[#5ba261] m-2 my-5' for="responseType">Response Type:</label>
            <select class='pl-2 my-5 rounded-full p-2 bg-[#2c2d31] text-white' id="responseType" wire:model="responseType">
                <option value="text">Text</option>
                <option value="image">Image</option>
            </select>
            
            <button type="submit" class='bg-[#5ba261] rounded-full mx-3 my-5 px-5 py-2'>Submit</button>
        </div>
        
        <!-- Loading indicator -->
        <div wire:loading class="flex justify-center my-5">
            <div class="spinner"></div>
            <p class="text-white ml-3">Loading...</p>
        </div>
    </form>
</div>
