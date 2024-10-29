<div class="min-h-screen flex justify-center items-center bg-gray-100">
    <script defer>
        function onScanSuccess(qrCodeMessage) {
            const studentNumberInput = document.getElementById("student_number");
            studentNumberInput.value = qrCodeMessage;
            studentNumberInput.dispatchEvent(new Event('input'));
            document.getElementById('serve-button-confirm').click();
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            console.warn(`Code scan error = ${error}`);
        }
    </script>
    <div class="flex flex-col w-full max-w-4xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach ($meals as $meal)
                <div class="bg-white rounded-lg shadow-md p-4 cursor-pointer hover:shadow-lg transition"
                    wire:click="selectMeal({{ $meal->id }})">
                    <h3 class="text-lg font-semibold">{{ $meal->name }}</h3>
                    <p class="text-gray-600">
                        {{ \Carbon\Carbon::parse($meal->start_time)->setTimezone(config('app.user_timezone'))->format('g:i A') }}
                        -
                        {{ \Carbon\Carbon::parse($meal->end_time)->setTimezone(config('app.user_timezone'))->format('g:i A') }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
            x-cloak x-show="$wire.showModal" x-init="$watch('$wire.showModal', value => {
                if (value) {
                    $nextTick(() => {
                        window.html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                    });
                }
            })">
            <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Scan Student ID</h3>
                    <div class="mt-2 px-7 py-3">
                        <input type="text" wire:model.defer="studentNumber" disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed"
                            id="student_number" placeholder="Enter student number" />
                    </div>
                    <div id="reader" width="600px" wire:ignore></div>
                    <div>
                        @error('error_serve')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="serve" id="serve-button-confirm" hidden
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Confirm
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
