<x-guest-layout>
    <div class="mb-6">
        <h3 class="text-lg font-bold text-navy">Crear una cuenta</h3>
        <p class="text-sm text-slate-500 mt-1">Regístrese para acceder al módulo de Gestión Curricular.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nombre completo')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej. Juan Pérez" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ejemplo@correo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="role_id" :value="__('Rol de acceso')" />
            <p class="mt-1 text-sm text-slate-500">Seleccione el rol con el que utilizará este módulo.</p>
            <select id="role_id" name="role_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-navy focus:ring-navy">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $role->slug === 'docente' ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Mín. 8 caracteres" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repita la contraseña" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-slate-500 hover:text-navy rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy" href="{{ route('login') }}">
                {{ __('¿Ya tiene cuenta?') }}
            </a>

            <button type="submit" class="px-6 py-2 bg-navy text-white font-bold rounded-lg text-sm hover:bg-[#343d96] transition-colors">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>
