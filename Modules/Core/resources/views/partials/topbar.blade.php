<header class="app-topbar">
    <button type="button" class="topbar-icon lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Abrir navegación">
        <span class="material-symbols-outlined">menu</span>
    </button>
    <label class="nexo-search">


    </label>
    <div class="app-topbar__actions">
        <button type="button" class="nexo-notification" aria-label="Notificaciones">
            <span class="material-symbols-outlined">notifications</span>
            <i></i>
        </button>

        <div class="user-menu" x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false">
            <button
                type="button"
                class="user-menu__btn"
                :aria-expanded="open"
                aria-haspopup="menu"
                @click="open = !open"
            >
                <span class="user-chip">
                    <span class="user-chip__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <span class="hidden sm:block">
                        <strong>{{ Auth::user()->name }}</strong>
                        <small>{{ Auth::user()->roleLabel() }}</small>
                    </span>
                    <span class="material-symbols-outlined hidden sm:block">expand_more</span>
                </span>
            </button>

            <div
                class="user-menu__panel"
                role="menu"
                :class="{ 'is-open': open }"
                x-show="open"
                x-transition:enter-start="opacity-0 !scale-95 !-translate-y-1"
                x-transition:enter-end="opacity-100 !scale-100 !translate-y-0"
                x-transition:leave-start="opacity-100 !scale-100 !translate-y-0"
                x-transition:leave-end="opacity-0 !scale-95 !-translate-y-1"
                style="display: none;"
            >
                <div class="user-menu__header">
                    <strong>{{ Auth::user()->name }}</strong>
                    <small>{{ Auth::user()->roleLabel() }} · {{ Auth::user()->email }}</small>
                </div>

                <a
                    href="{{ route('profile.edit') }}"
                    class="user-menu__item"
                    role="menuitem"
                    @click="open = false"
                >
                    <span class="material-symbols-outlined">settings</span>
                    <span>Configuración</span>
                </a>

                <div class="user-menu__divider"></div>

                <div class="user-menu__item user-menu__item--logout" role="menuitem">
                    <form method="POST" action="{{ route('logout') }}" novalidate>
                        @csrf
                        <button type="submit" @click="open = false">
                            <span class="material-symbols-outlined">logout</span>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
