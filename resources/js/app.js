import { start as turboStart } from '@hotwired/turbo'
import Alpine from 'alpinejs'

window.Alpine = Alpine

turboStart()

// Ejecuta una función cuando el DOM está listo, ya sea en carga completa o en navegación Turbo
window.__onReady = (fn) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn)
    } else {
        fn()
    }
}

// Al navegar con Turbo el <body> se reemplaza: reinicializa los componentes Alpine
document.addEventListener('turbo:render', () => {
    Alpine.initTree(document.body)
})

// Tras cada render (carga o navegación) restaura la entrada del contenido
const settleMain = () => {
    const main = document.querySelector('.app-main, .app-shell, .page-content, .nexo-dashboard')
    if (!main) return
    main.style.transition = 'opacity 260ms ease, transform 260ms ease'
    requestAnimationFrame(() => {
        main.style.opacity = '1'
        main.style.transform = ''
    })
}
document.addEventListener('turbo:load', settleMain)
window.addEventListener('pageshow', settleMain)

// Fade-out al hacer clic en enlaces internos (delegado: persiste entre navegaciones Turbo)
document.addEventListener('click', (e) => {
    const a = e.target.closest?.('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])')
    if (!a || a.hasAttribute('download') || a.dataset.turbo === 'false') return
    const url = new URL(a.href, window.location.origin)
    if (url.origin !== window.location.origin) return
    const main = document.querySelector('.app-main, .app-shell, .page-content, .nexo-dashboard')
    if (!main) return
    let start = null
    const duration = 120
    requestAnimationFrame(function step(t) {
        if (!start) start = t
        const p = Math.min((t - start) / duration, 1)
        main.style.opacity = String(1 - p * 0.55)
        main.style.transform = 'translateY(' + (p * 5) + 'px)'
        if (p < 1) requestAnimationFrame(step)
    })
})

// Sombra del topbar al hacer scroll
window.addEventListener('scroll', () => {
    const t = document.querySelector('.app-topbar')
    if (t) t.classList.toggle('is-scrolled', window.scrollY > 8)
}, { passive: true })

// ================================
// Overlay de carga (Turbo)
// ================================
let overlayTimer = null

const ensureOverlay = () => {
    if (document.getElementById('app-loading-overlay')) return
    const el = document.createElement('div')
    el.id = 'app-loading-overlay'
    el.innerHTML = `
        <div class="loading-card" role="status" aria-live="polite">
            <img src="/static/img/logo_informatica.png" alt="UNT">
            <div class="loading-spinner" aria-hidden="true"></div>
            <div class="loading-text">
                <strong class="loading-dots">Cargando</strong>
                <small>Un momento por favor</small>
            </div>
        </div>`
    document.body.appendChild(el)
}

const showOverlay = () => {
    ensureOverlay()
    clearTimeout(overlayTimer)
    overlayTimer = setTimeout(() => {
        const el = document.getElementById('app-loading-overlay')
        if (el) el.classList.add('is-visible')
    }, 150)
}

const hideOverlay = () => {
    clearTimeout(overlayTimer)
    const el = document.getElementById('app-loading-overlay')
    if (el) el.classList.remove('is-visible')
}

document.addEventListener('turbo:before-visit', showOverlay)
document.addEventListener('turbo:submit-start', showOverlay)
document.addEventListener('turbo:render', hideOverlay)
document.addEventListener('turbo:load', hideOverlay)
document.addEventListener('turbo:fetch-request-error', hideOverlay)
document.addEventListener('turbo:render', ensureOverlay)
document.addEventListener('DOMContentLoaded', ensureOverlay)

// Red de seguridad: si algo falla, nunca dejar la overlay pegada
setInterval(hideOverlay, 15000)

Alpine.start()
