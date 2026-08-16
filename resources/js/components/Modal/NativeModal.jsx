import { router, usePage } from '@inertiajs/react'
import { createElement, useEffect, useRef, useState } from 'react'
import { createPortal } from 'react-dom'
import ConfirmModal from './ConfirmModal'

const pages = import.meta.glob('../../pages/**/*.jsx')
const modalCache = new Map()
const modalRequests = new Map()
const MODAL_CACHE_MS = 120_000
const SPINNER_DELAY_MS = 150

const componentLoader = (name) => pages[`../../pages/${name}.jsx`]

async function fetchInertiaPage(href, signal, version) {
    const cached = modalCache.get(href)
    if (cached?.expiresAt > Date.now()) return cached.page
    const headers = {
        Accept: 'text/html, application/xhtml+xml',
        'X-Inertia': 'true',
        'X-Requested-With': 'XMLHttpRequest',
    }
    if (version) headers['X-Inertia-Version'] = version

    let response = await fetch(href, {
        signal,
        credentials: 'same-origin',
        headers,
    })
    if (response.status === 409 && response.headers.get('X-Inertia-Version')) {
        headers['X-Inertia-Version'] = response.headers.get('X-Inertia-Version')
        response = await fetch(href, { signal, credentials: 'same-origin', headers })
    }
    if (new URL(response.url).pathname === '/login') throw new Error('SESSION_EXPIRED')
    if (!response.ok) throw new Error(`HTTP ${response.status}`)
    const contentType = response.headers.get('content-type') ?? ''
    let page
    if (contentType.includes('application/json')) {
        page = await response.json()
    } else {
        const documentNode = new DOMParser().parseFromString(await response.text(), 'text/html')
        const root = documentNode.querySelector('[data-page]')
        if (!root) throw new Error('INVALID_INERTIA_PAYLOAD')
        page = JSON.parse(root.getAttribute('data-page'))
    }
    const loadComponent = componentLoader(page.component)
    if (!loadComponent) throw new Error(`Componente no encontrado: ${page.component}`)
    const module = await loadComponent()
    const loadedPage = { Component: module.default, props: page.props }
    modalCache.set(href, { page: loadedPage, expiresAt: Date.now() + MODAL_CACHE_MS })
    return loadedPage
}

function loadInertiaPage(href, signal, version) {
    const cached = modalCache.get(href)
    if (cached?.expiresAt > Date.now()) return Promise.resolve(cached.page)
    if (modalRequests.has(href)) return modalRequests.get(href)
    const request = fetchInertiaPage(href, signal, version).finally(() => modalRequests.delete(href))
    modalRequests.set(href, request)
    return request
}

export function prefetchModalPage(href, version) {
    if (!href || modalCache.has(href)) return
    loadInertiaPage(href, undefined, version).catch(() => {})
}

export default function NativeModal({ open, href, title, onClose, size = 'wide', context = 'Gestión Curricular', icon = 'account_balance', exitPaths = [] }) {
    const { version } = usePage()
    const closeButtonRef = useRef(null)
    const openerRef = useRef(null)
    const contentRef = useRef(null)
    const dirtyRef = useRef(false)
    const submitButtonRef = useRef(null)
    const promotedActionRef = useRef(null)
    const promotedCancelRef = useRef(null)
    const [page, setPage] = useState(null)
    const [error, setError] = useState(false)
    const [confirmingClose, setConfirmingClose] = useState(false)
    const [modalAction, setModalAction] = useState(null)
    const [submitting, setSubmitting] = useState(false)
    const [showDelayedLoading, setShowDelayedLoading] = useState(false)

    const performClose = () => {
        dirtyRef.current = false
        setConfirmingClose(false)
        onClose()
    }

    const requestClose = () => dirtyRef.current ? setConfirmingClose(true) : performClose()

    useEffect(() => {
        if (!open) return undefined
        openerRef.current = document.activeElement
        const cached = modalCache.get(href)
        const hasCachedPage = cached?.expiresAt > Date.now()
        setPage(hasCachedPage ? cached.page : null)
        setError(false)
        setModalAction(null)
        setSubmitting(false)
        setShowDelayedLoading(false)
        dirtyRef.current = false
        const controller = new AbortController()
        const loadingTimer = hasCachedPage ? null : window.setTimeout(() => setShowDelayedLoading(true), SPINNER_DELAY_MS)
        const previousOverflow = document.documentElement.style.overflow
        document.documentElement.style.overflow = 'hidden'
        loadInertiaPage(href, controller.signal, version).then((loadedPage) => {
            if (loadingTimer) window.clearTimeout(loadingTimer)
            setPage(loadedPage)
            setShowDelayedLoading(false)
        }).catch((reason) => {
            if (loadingTimer) window.clearTimeout(loadingTimer)
            if (reason.name !== 'AbortError') setError(true)
        })
        const removeSuccessListener = router.on('success', (event) => {
            const target = new URL(event.detail.page.url, window.location.origin).pathname
            if (exitPaths.includes(target)) {
                modalCache.delete(href)
                performClose()
            }
        })
        const removeFinishListener = router.on('finish', () => {
            submitButtonRef.current?.classList.remove('is-processing')
            submitButtonRef.current?.removeAttribute('aria-busy')
            submitButtonRef.current = null
            setSubmitting(false)
        })
        const onKeyDown = (event) => {
            if (event.key === 'Escape') requestClose()
            if (event.key !== 'Tab') return
            const controls = [...(contentRef.current?.closest('[role="dialog"]')?.querySelectorAll('button:not([disabled]), a[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])') ?? [])]
            if (!controls.length) return
            if (event.shiftKey && document.activeElement === controls[0]) { event.preventDefault(); controls.at(-1).focus() }
            else if (!event.shiftKey && document.activeElement === controls.at(-1)) { event.preventDefault(); controls[0].focus() }
        }
        document.addEventListener('keydown', onKeyDown)
        return () => {
            controller.abort()
            window.clearTimeout(loadingTimer)
            removeSuccessListener()
            removeFinishListener()
            document.removeEventListener('keydown', onKeyDown)
            document.documentElement.style.overflow = previousOverflow
            openerRef.current?.focus?.()
        }
    }, [open, href, version])

    useEffect(() => {
        if (!open) return
        requestAnimationFrame(() => closeButtonRef.current?.focus())
    }, [open])

    useEffect(() => {
        if (!page || !contentRef.current) return undefined
        let observer
        const frame = requestAnimationFrame(() => {
            const buttons = [...contentRef.current.querySelectorAll('button[type="submit"], input[type="submit"]')]
            if (buttons.length !== 1) return
            const button = buttons[0]
            const label = button.dataset.modalLabel || button.textContent.trim() || button.value || 'Guardar cambios'
            promotedActionRef.current = button
            button.classList.add('modal-promoted-action')
            const cancel = [...contentRef.current.querySelectorAll('a, button[type="button"]')].find((element) => element.textContent.trim().toLowerCase() === 'cancelar')
            cancel?.classList.add('modal-promoted-cancel')
            promotedCancelRef.current = cancel ?? null
            const sync = () => setModalAction({
                label,
                disabled: button.disabled,
            })
            sync()
            observer = new MutationObserver(sync)
            observer.observe(button, { attributes: true, attributeFilter: ['disabled'] })
        })
        return () => {
            cancelAnimationFrame(frame)
            observer?.disconnect()
            promotedActionRef.current?.classList.remove('modal-promoted-action')
            promotedCancelRef.current?.classList.remove('modal-promoted-cancel')
            promotedActionRef.current = null
            promotedCancelRef.current = null
        }
    }, [page])

    if (!open) return null

    const onContentClick = (event) => {
        const link = event.target.closest('a[href]')
        if (!link) return
        const target = new URL(link.href, window.location.origin).pathname
        if (exitPaths.includes(target)) {
            event.preventDefault()
            requestClose()
        }
    }

    const onContentSubmit = (event) => {
        dirtyRef.current = false
        const submitButton = event.nativeEvent?.submitter
        if (!submitButton) return
        submitButtonRef.current = submitButton
        setSubmitting(true)
        submitButton.classList.add('is-processing')
        submitButton.setAttribute('aria-busy', 'true')
    }

    return createPortal(
        <div className="app-modal" role="presentation" onMouseDown={(event) => event.target === event.currentTarget && requestClose()}>
            <section className={`app-modal__dialog app-modal__dialog--${size}`} role="dialog" aria-modal="true" aria-labelledby="native-modal-title">
                <header className="app-modal__header">
                    <div className="app-modal__heading"><span className="app-modal__mark material-symbols-outlined" aria-hidden="true">{icon}</span><div><h2 id="native-modal-title">{title}</h2><p>{context} · Universidad Nacional de Trujillo</p></div></div>
                    <button ref={closeButtonRef} type="button" onClick={requestClose} aria-label="Cerrar ventana"><span className="material-symbols-outlined" aria-hidden="true">close</span></button>
                </header>
                <div ref={contentRef} className={`app-modal__body native-modal-content${!page && !error ? ' native-modal-content--pending' : ''}`} onClick={onContentClick} onInput={() => { dirtyRef.current = true }} onChange={() => { dirtyRef.current = true }} onSubmit={onContentSubmit}>
                    {!page && !error && showDelayedLoading && <div className="app-modal__loading-state" role="status" aria-live="polite"><span className="app-modal__loading-pulse" aria-hidden="true"></span><strong>Cargando contenido</strong><p>Preparando la información solicitada…</p></div>}
                    {error && <div className="app-modal__error" role="alert"><span className="material-symbols-outlined">cloud_off</span><strong>No se pudo cargar el contenido</strong><p>Inténtalo nuevamente.</p><button type="button" onClick={() => { setError(false); loadInertiaPage(href, undefined, version).then(setPage).catch(() => setError(true)) }}>Reintentar</button></div>}
                    {page && createElement(page.Component, page.props)}
                </div>
                {modalAction && <footer className="app-modal__actions">
                    <button type="button" className="app-modal__cancel" onClick={requestClose} disabled={submitting}>Cancelar</button>
                    <button type="button" className="app-modal__submit" disabled={submitting || modalAction.disabled} aria-busy={submitting} onClick={() => promotedActionRef.current?.form?.requestSubmit(promotedActionRef.current)}>
                        {submitting && <span className="app-modal__button-spinner" aria-hidden="true"></span>}
                        <span>{submitting ? 'Procesando…' : modalAction.label}</span>
                    </button>
                </footer>}
            </section>
            <ConfirmModal open={confirmingClose} title="Descartar cambios" message="Hay información modificada que todavía no se ha guardado." confirmLabel="Descartar y cerrar" onConfirm={performClose} onCancel={() => setConfirmingClose(false)} />
        </div>,
        document.body,
    )
}
