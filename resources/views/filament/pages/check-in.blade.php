<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-4">

            {{-- QR Camera Scanner --}}
            <div>
                <div id="qr-reader" style="width:100%; max-width:500px; margin: 0 auto;"></div>
                <div id="qr-reader-results" class="mt-2 text-center text-sm text-gray-500"></div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex-1 h-px bg-gray-300"></div>
                <span class="text-gray-400 text-sm">or enter manually</span>
                <div class="flex-1 h-px bg-gray-300"></div>
            </div>

            {{-- Manual Input --}}
            <form wire:submit="checkIn">
                {{ $this->form }}
                <x-filament::button type="submit" class="mt-4">
                    Check In
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>

    @if ($message)
        <x-filament::section>
            <div
                style="padding: 16px; border-radius: 8px; background-color: {{ $success ? '#d1fae5' : '#fee2e2' }}; color: {{ $success ? '#065f46' : '#991b1b' }}; font-size: 1.1rem;">
                {{ $message }}
            </div>
        </x-filament::section>
    @endif

    {{-- html5-qrcode library --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const html5QrCode = new Html5Qrcode("qr-reader");

            const config = {
                fps: 10,
                qrbox: {width: 250, height: 250},
                rememberLastUsedCamera: true,
            };

            html5QrCode.start(
                {facingMode: "environment"},
                config,
                function (decodedText) {
                    // Stop scanning immediately after a successful scan
                    html5QrCode.stop().then(() => {
                        // Fill the Livewire input with the scanned value
                        const input = document.querySelector('input[wire\\:model\\.live="data.qr_code"], input[wire\\:model="data.qr_code"]');
                        if (input) {
                            input.value = decodedText;
                            input.dispatchEvent(new Event('input'));
                        }

                        // Show result
                        document.getElementById('qr-reader-results').innerText = 'Scanned: ' + decodedText;

                        // Auto submit after short delay
                        setTimeout(() => {
                        @this.checkIn()
                            ;

                            // Restart scanner after 3 seconds for next scan
                            setTimeout(() => {
                                html5QrCode.start(
                                    {facingMode: "environment"},
                                    config,
                                    arguments.callee,
                                    function () {
                                    }
                                );
                                document.getElementById('qr-reader-results').innerText = '';
                            }, 3000);
                        }, 500);
                    });
                },
                function (errorMessage) {
                    // scan errors are ignored
                }
            ).catch(err => {
                document.getElementById('qr-reader-results').innerText = 'Camera not available: ' + err;
            });
        });
    </script>
</x-filament-panels::page>
