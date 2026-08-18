export function AuthPanel({ icon, title, description, children }) {
    return (
        <div className="rounded-2xl bg-white p-6 shadow-[0_28px_70px_-38px_rgba(8,38,70,.5)] sm:p-9">
            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-700" aria-hidden="true"><span className="material-symbols-outlined text-[25px]">{icon}</span></div>
            <h1 className="mt-6 text-3xl font-black tracking-[-0.03em] text-ink-950">{title}</h1>
            <p className="mt-3 max-w-md text-sm leading-7 text-ink-600">{description}</p>
            <div className="mt-7">{children}</div>
        </div>
    )
}

export const fieldClass = 'block min-h-12 w-full rounded-xl border border-ink-200 bg-ink-50 px-4 py-3 text-sm font-medium text-ink-950 outline-none transition placeholder:text-ink-500 hover:border-ink-300 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10'
export const primaryButtonClass = 'inline-flex min-h-12 items-center justify-center rounded-xl bg-brand-700 px-6 py-3 text-sm font-extrabold text-white shadow-[0_14px_32px_-18px_rgba(8,85,170,.8)] transition hover:bg-brand-600 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-brand-300/50 disabled:cursor-not-allowed disabled:opacity-60'
export const secondaryLinkClass = 'rounded-md text-sm font-bold text-brand-700 hover:text-brand-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2'
export const errorClass = 'mt-2 text-sm font-semibold text-red-700'
export const noticeClass = 'mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold leading-6 text-emerald-800'
