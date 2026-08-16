import { Link } from '@inertiajs/react'

export default function GuestLayout({ children }) {
    return (
        <main className="public-auth relative min-h-screen overflow-hidden bg-ink-950 text-ink-950">
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_15%_15%,rgba(33,136,243,.22),transparent_30%),linear-gradient(135deg,#081a23_0%,#0a3047_55%,#071922_100%)]" />
            <div className="relative mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[.9fr_1.1fr]">
                <section className="hidden flex-col justify-between px-12 py-12 text-white lg:flex">
                    <Link href="/" className="flex w-fit items-center gap-3 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <img src="/static/img/logo_informatica.png" alt="Facultad de Ingeniería Informática" className="h-12 w-12 rounded-xl object-cover" />
                        <span><strong className="block text-sm">UNT · Ingeniería Informática</strong><span className="mt-1 block text-xs text-brand-200">Plataforma de Calidad Académica</span></span>
                    </Link>
                    <div className="max-w-lg pb-10">
                        <span className="public-auth__seal material-symbols-outlined">verified_user</span>
                        <p className="mt-6 text-balance text-4xl font-black leading-tight tracking-[-0.03em]">Procesos académicos conectados por un propósito común</p>
                        <p className="mt-5 max-w-md text-base leading-8 text-slate-300">Acceda al entorno institucional para participar en la gestión y mejora continua de la formación.</p>
                        <div className="public-auth__trust"><span><i></i>Conexión institucional segura</span><span>Acceso según rol académico</span></div>
                    </div>
                    <p className="text-xs text-slate-500">Universidad Nacional de Trujillo</p>
                </section>
                <section className="relative flex min-h-screen items-center justify-center bg-canvas px-5 py-10 sm:px-8 lg:rounded-l-[2rem]">
                    <div className="w-full max-w-lg">
                        <Link href="/" className="mb-7 flex items-center gap-3 rounded-xl lg:hidden"><img src="/static/img/logo_informatica.png" alt="" className="h-11 w-11 rounded-xl object-cover" /><span><strong className="block text-sm text-ink-950">UNT · Ingeniería Informática</strong><span className="text-xs text-ink-600">Plataforma de Calidad Académica</span></span></Link>
                        {children}
                        <p className="mt-7 text-center text-xs text-ink-500">Acceso institucional protegido · Universidad Nacional de Trujillo</p>
                    </div>
                </section>
            </div>
        </main>
    )
}
