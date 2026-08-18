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

// Sombra del topbar al hacer scroll
window.addEventListener('scroll', () => {
    const t = document.querySelector('.app-topbar')
    if (t) t.classList.toggle('is-scrolled', window.scrollY > 8)
}, { passive: true })

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
