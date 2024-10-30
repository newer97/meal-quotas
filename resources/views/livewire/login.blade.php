<div class="min-h-screen flex justify-center items-center">
    <div class="max-w-xl py-6 px-8 h-80 mt-20 bg-white rounded shadow-xl w-1/4">
        <div class="mb-6">
            <label for="username" class="block text-gray-800 font-bold">username:</label>
            <input type="text" name="username" id="username" placeholder="username" wire:model="username"
                class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" />
            @error('email')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-gray-800 font-bold">password:</label>
            <input type="password" name="password" id="password" placeholder="password" wire:model="password"
                class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" />
        </div>
        <button wire:click="login"
            class="cursor-pointer py-2 px-4 block mt-6 bg-indigo-500 text-white font-bold w-full text-center rounded">Login</button>

    </div>
</div>
