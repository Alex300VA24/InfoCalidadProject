import { createInertiaApp } from '@inertiajs/react'

createInertiaApp({
    title: (title) => (title ? `${title} · Gestión Académica` : 'Gestión Académica'),

    progress: {
        delay: 150,
        color: '#096bd1',
        showSpinner: false,
    },
})
