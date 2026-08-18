import { useEffect, useRef } from 'react'
import { createPortal } from 'react-dom'

export default function ConfirmModal({ open, title, message, confirmLabel = 'Confirmar', tone = 'primary', processing = false, onConfirm, onCancel }) {
    const cancelRef = useRef(null)

    useEffect(() => {
        if (!open) return undefined
        cancelRef.current?.focus()
        const onKeyDown = (event) => {
            if (event.key === 'Escape' && !processing) onCancel()
        }
        document.addEventListener('keydown', onKeyDown)
        return () => document.removeEventListener('keydown', onKeyDown)
    }, [open, processing, onCancel])

    if (!open) return null

    return createPortal(
        <div className="app-modal app-modal--confirm" role="presentation">
            <section className="app-confirm" role="alertdialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message">
                <div className="app-confirm__top">
                    <span className={`app-confirm__icon app-confirm__icon--${tone} material-symbols-outlined`} aria-hidden="true">
                        {tone === 'success' ? 'verified' : 'help'}
                    </span>
                    <div><h2 id="confirm-title">{title}</h2><p id="confirm-message">{message}</p></div>
                </div>
                <div className="app-confirm__actions">
                    <button ref={cancelRef} type="button" onClick={onCancel} disabled={processing} className="app-confirm__cancel">Cancelar</button>
                    <button type="button" onClick={onConfirm} disabled={processing} className={`app-confirm__submit app-confirm__submit--${tone}`}>
                        {processing ? 'Procesando…' : confirmLabel}
                    </button>
                </div>
            </section>
        </div>,
        document.body,
    )
}
