import { createInertiaApp } from '@inertiajs/react'

createInertiaApp({
    title: (title) => (title ? `${title} · Gestión Académica` : 'Gestión Académica'),

    progress: false,
})
