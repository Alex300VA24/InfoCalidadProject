<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Accesibilidad
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Ajusta el tamaño del texto y de toda la vista para mejorar la legibilidad.
        </p>
    </header>

    <form method="post" action="{{ route('profile.accessibility.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label value="Tamaño del texto" />

            <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                @foreach ([100 => 'Normal', 125 => 'Grande', 150 => 'Muy grande'] as $value => $label)
                    <label class="cursor-pointer">
                        <input
                            type="radio"
                            name="text_scale"
                            value="{{ $value }}"
                            @checked($user->text_scale === $value)
                            class="peer sr-only"
                        >
                        <span class="block rounded-lg border border-gray-300 p-3 text-center text-sm font-medium text-gray-700 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:ring-1 peer-checked:ring-indigo-500">
                            {{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('text_scale')" />
        </div>

        <div>
            <x-input-label value="Tamaño de la vista" />

            <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach ([100 => 'Normal', 110 => '110%', 125 => '125%', 150 => '150%'] as $value => $label)
                    <label class="cursor-pointer">
                        <input
                            type="radio"
                            name="view_scale"
                            value="{{ $value }}"
                            @checked($user->view_scale === $value)
                            class="peer sr-only"
                        >
                        <span class="block rounded-lg border border-gray-300 p-3 text-center text-sm font-medium text-gray-700 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:ring-1 peer-checked:ring-indigo-500">
                            {{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('view_scale')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'accessibility-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
