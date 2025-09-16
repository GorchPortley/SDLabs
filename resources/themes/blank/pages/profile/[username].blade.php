<?php

    use function Laravel\Folio\{middleware, name};
    use Livewire\Volt\Component;
    use Livewire\Attributes\Computed;
    name('wave.profile');

    new class extends Component
    {
        public $username;
        #[Computed]
        public function user()
        {
            return config('wave.user_model')::where('username', '=', $this->username)
                ->with(['roles', 'designs' => function($query) {
                    $query->where('active', 1);
                }])
                ->firstOrFail();
        }
    }
?>

<x-dynamic-component :component="((auth()->guest()) ? 'layouts.marketing' : 'layouts.app')">
    @volt('wave.profile')

        <x-dynamic-component :component="((auth()->guest()) ? 'container' : 'app.container')">

            @guest
                <x-marketing.heading
                    level="h2"
                    class="mt-5"
                    :title="$this->user->name"
                    :description="'Currently viewing ' . $this->user->username . '\'s profile'"
                    align="left"
                />
            @endguest

            <div class="flex lg:px-6 px-0 lg:flex-row flex-col @guest pb-20 pt-10 @endguest lg:space-y-0 space-y-5 lg:space-x-5 h-full">
                <x-card class="flex flex-col justify-center items-center p-10 w-full lg:w-1/3">
                        <img src="{{ $this->user->avatar() }}" class="w-24 h-24 rounded-full border-4 border-zinc-200">
                        <h2 class="mt-8 text-2xl font-bold">{{ $this->user->name }}</h2>
                        <p class="my-1 font-medium text-blue-blue">{{ '@' . $this->user->username }}</p>

                        @if (auth()->check() && auth()->user()->isAdmin())
                            <a href="{{ route('impersonate', $this->user->id) }}" class="px-3 py-1 my-2 text-xs font-medium text-white rounded text-zinc-600 bg-zinc-200">Impersonate</a>
                        @endif
                        <p class="mx-auto max-w-lg text-base text-center text-zinc-500">{{ $this->user->profile('about') }}</p>
                </x-card>

                <x-card class="p-10 lg:w-2/3 lg:flex-2 space-y-4">
                    <div><H1 class="text-2xl font-bold">Submitted Designs</H1></div>
                    @foreach($this->user->designs as $design)
                        <a href="{{env('APP_URL')}}/designs/design/{{$design->id}}" class="block">
                            <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-center">
                                    <h1 class="font-bold text-lg">{{$design->name}}</h1>
                                    <span class="text-sm text-gray-600">{{$design->category}}</span>
                                </div>
                                <p class="text-gray-500 mt-2">{{$design->tag}}</p>
                            </div>
                        </a>
                    @endforeach
                </x-card>
            </div>

        </x-dynamic-component>
    @endvolt

</x-dynamic-component>
