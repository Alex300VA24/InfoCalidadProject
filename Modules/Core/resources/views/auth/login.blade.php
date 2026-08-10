<x-guest-layout>
    <x-auth-session-status
        class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        :status="session('status')"
    />

    @include('auth.partials.login-card')

    <div class="mt-6 text-center">
        <a
            href="/"
            class="group inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-500 transition-colors duration-300 hover:text-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
        >
            <span
                class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:-translate-x-1"
            >
                arrow_back
            </span>

            Volver a la selección de módulos
        </a>
    </div>

    <script data-turbo-eval="true">
        window.__onReady(() => {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');
            const passwordIcon = document.getElementById('password-icon');

            if (!passwordInput || !toggleButton || !passwordIcon) {
                return;
            }

            toggleButton.addEventListener('click', () => {
                const isPasswordVisible = passwordInput.type === 'text';

                passwordInput.type = isPasswordVisible ? 'password' : 'text';
                passwordIcon.textContent = isPasswordVisible ? 'visibility' : 'visibility_off';

                toggleButton.setAttribute('aria-label', isPasswordVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
                toggleButton.setAttribute('aria-pressed', String(!isPasswordVisible));
            });
        });
    </script>
</x-guest-layout>
