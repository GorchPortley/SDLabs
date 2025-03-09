<?php
    
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    
    middleware('auth');
    name('settings.subscription');

	new class extends Component
	{
        public function mount(): void
        {
            
        }
    }

?>

<x-layouts.app>
    @volt('settings.subscription') 
        <div class="relative">
            <x-app.settings-layout
                title="Subscriptions"
                description="Your subscription details"
            >
                @role('admin')
                    <div>
                        You are logged in as an admin and have full access. Authenticate with a different user and visit this page to see the subscription checkout process.
                    </div>
                @else
                    @subscriber
                        
                        <div class="relative w-full h-auto">                            
                            <div>
                                <x-phosphor-seal-check-duotone class="flex-shrink-0 mr-1.5 -ml-1.5 w-6 h-6" /> 
                                <span>You are currently subscribed to the {{ auth()->user()->plan()->name }} {{ auth()->user()->planInterval() }} Plan.</span>
                            </div>
                            <p class="my-4">Manage your subscription by clicking below. Edit this page from the following file:  <code>resources/views/{{ $theme->folder }}/pages/settings/subscription.blade.php</code></p>
                            @if (session('update'))
                                <div class="my-4 text-sm text-green-600">Successfully updated your subscription</div>
                            @endif
                            <livewire:billing.update />
                        </div>
                       

                    @endsubscriber

                    @notsubscriber
                        <div class="mb-7">
                            <div class="flex items-center space-x-2">
                                <x-phosphor-shopping-bag-open-duotone class="flex-shrink-0 mr-1.5 -ml-1.5 w-6 h-6" />
                                <span>No active subscriptions found. Please select a plan below.</span>
                            </div>
                        </div>
                        <livewire:billing.checkout />
                        <p class="flex items-center mt-3 mb-4">
                            <x-phosphor-shield-check-duotone class="w-4 h-4 mr-1" />
                            <span class="mr-1">Billing is securely managed via </span><strong>{{ ucfirst(config('wave.billing_provider')) }} Payment Platform</strong>.
                        </p>
                    @endnotsubscriber
                @endrole
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
