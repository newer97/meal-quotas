<div class="min-h-screen flex justify-center items-center bg-gray-100">

    <div class="flex flex-col w-full max-w-4xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach ($meals as $meal)
                <div class="bg-white rounded-lg shadow-md p-4 cursor-pointer hover:shadow-lg transition"
                    wire:click="selectMeal({{ $meal->id }})">
                    <h3 class="text-lg font-semibold">{{ $meal->name }}</h3>
                    <p class="text-gray-600">{{ date('g:i A', strtotime($meal->start_time)) }} -
                        {{ date('g:i A', strtotime($meal->end_time)) }}</p>
                </div>
            @endforeach
        </div>

        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
            x-cloak x-show="$wire.showModal" x-init="$watch('$wire.showModal', value => {
                if (value) {
                    $nextTick(() => {
                        document.getElementById('student_number').focus();
                    });
                }
            })">
            <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Enter Student Number</h3>
                    <div class="mt-2 px-7 py-3">
                        <input type="text" wire:model.defer="studentNumber"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            id="student_number" placeholder="Enter student number" />
                    </div>
                    <div>
                        @error('studentNumber')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        @error('served')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="serve"
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Confirm Order
                        </button>
                        <button wire:click="closeModal"
                            class="ml-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
