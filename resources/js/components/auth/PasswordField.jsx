import { useState } from 'react'
import { errorClass, fieldClass } from './AuthPanel'

export default function PasswordField({ id, label, value, onChange, error, autoComplete = 'current-password', autoFocus = false, placeholder = 'Ingrese su contraseña' }) {
    const [visible, setVisible] = useState(false)
    return (
        <div>
            <label htmlFor={id} className="mb-2 block text-sm font-bold text-ink-800">{label}</label>
            <div className="relative">
                <input id={id} type={visible ? 'text' : 'password'} value={value} onChange={onChange} autoComplete={autoComplete} autoFocus={autoFocus} required placeholder={placeholder} aria-invalid={Boolean(error)} aria-describedby={error ? `${id}-error` : undefined} className={`${fieldClass} pr-12`} />
                <button type="button" onClick={() => setVisible((current) => !current)} aria-label={visible ? `Ocultar ${label.toLowerCase()}` : `Mostrar ${label.toLowerCase()}`} aria-pressed={visible} className="absolute inset-y-0 right-0 min-w-11 px-3 text-ink-500 hover:text-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand-400">
                    <span className="material-symbols-outlined text-[20px]" aria-hidden="true">{visible ? 'visibility_off' : 'visibility'}</span>
                </button>
            </div>
            {error && <p id={`${id}-error`} className={errorClass}>{error}</p>}
        </div>
    )
}
