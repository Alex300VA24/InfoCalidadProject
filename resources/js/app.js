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

// ================================
// Indicador de tiempos de respuesta (Turbo)
// Mide cada navegación (GET) y acción (formularios POST) y muestra el
// tiempo de respuesta del servidor para detectar páginas lentas.
// ================================
const perfStarts = new Map()
const perfHistory = []

const perfStateFor = (ms) => (ms <= 150 ? '' : ms <= 400 ? 'is-slow' : 'is-critical')

const ensurePerfBadge = () => {
    let badge = document.getElementById('app-perf-badge')
    if (!badge) {
        badge = document.createElement('div')
        badge.id = 'app-perf-badge'
        badge.innerHTML = '<span class="dot"></span><span class="label"></span><span class="ms"></span>'
        document.body.appendChild(badge)
    }
    return badge
}

const updatePerfBadge = (label, ms, ok) => {
    const badge = ensurePerfBadge()
    badge.className = ok ? perfStateFor(ms) : 'is-critical'
    badge.querySelector('.label').textContent = label
    badge.querySelector('.ms').textContent = `${ms} ms`
    const recent = perfHistory.slice(-5).map((h) => `${h.ms}ms ${h.label}`).join(' · ')
    badge.title = recent ? `Últimas respuestas: ${recent}` : 'Navega para medir tiempos de respuesta'
}

document.addEventListener('turbo:before-fetch-request', (e) => {
    const { fetchOptions } = e.detail
    if (!fetchOptions?.url) return
    perfStarts.set(String(fetchOptions.url), {
        t: performance.now(),
        method: (fetchOptions.method || 'GET').toUpperCase(),
    })
})

document.addEventListener('turbo:before-fetch-response', (e) => {
    const url = e.detail?.fetchResponse?.response?.url
    const start = url ? perfStarts.get(String(url)) : null
    if (!start) return
    perfStarts.delete(String(url))
    const ms = Math.round(performance.now() - start.t)
    const path = new URL(String(url)).pathname
    const label = `${start.method} ${path}`
    perfHistory.push({ label, ms })
    if (perfHistory.length > 20) perfHistory.shift()
    console.debug(`[turbo] ${label} -> ${ms} ms`)
    updatePerfBadge(label, ms, true)
})

document.addEventListener('turbo:fetch-request-error', (e) => {
    const url = e.detail?.fetchOptions?.url
    if (!url) return
    const start = perfStarts.get(String(url))
    if (!start) return
    perfStarts.delete(String(url))
    const path = new URL(String(url)).pathname
    updatePerfBadge(`ERR ${start.method} ${path}`, 0, false)
})

document.addEventListener('turbo:render', ensurePerfBadge)
document.addEventListener('DOMContentLoaded', ensurePerfBadge)

Alpine.start()
