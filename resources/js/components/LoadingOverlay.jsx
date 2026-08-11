import { useEffect, useRef, useState } from 'react'
import { router } from '@inertiajs/react'

export default function LoadingOverlay() {
    const [visible, setVisible] = useState(false)
    const timer = useRef(null)

    useEffect(() => {
        const show = () => {
            clearTimeout(timer.current)
            timer.current = setTimeout(() => setVisible(true), 150)
        }

        const hide = () => {
            clearTimeout(timer.current)
            setVisible(false)
        }

        const offStart = router.on('start', show)
        const offFinish = router.on('finish', hide)
        const offError = router.on('error', hide)
        const offCancel = router.on('cancel', hide)

        return () => {
            clearTimeout(timer.current)
            offStart()
            offFinish()
            offError()
            offCancel()
        }
    }, [])

    return (
        <div
            id="app-loading-overlay"
            className={visible ? 'is-visible' : ''}
            role="status"
            aria-live="polite"
            aria-hidden={!visible}
        >
            <div className="loading-card">
                <img src="/static/img/logo_informatica.png" alt="UNT" />

                <div className="loading-spinner" aria-hidden="true"></div>

                <div className="loading-text">
                    <strong className="loading-dots">Cargando</strong>
                    <small>Un momento por favor</small>
                </div>
            </div>
        </div>
    )
}
