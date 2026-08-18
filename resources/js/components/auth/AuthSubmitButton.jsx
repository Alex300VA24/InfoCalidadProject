import { primaryButtonClass } from './AuthPanel'

export default function AuthSubmitButton({ processing, idleLabel, processingLabel, icon = 'arrow_forward' }) {
    return (
        <button type="submit" disabled={processing} aria-busy={processing} className={`${primaryButtonClass} w-full gap-2`}>
            <span className={`material-symbols-outlined text-[19px] ${processing ? 'auth-spin' : ''}`} aria-hidden="true">{processing ? 'progress_activity' : icon}</span>
            {processing ? processingLabel : idleLabel}
        </button>
    )
}
