import { usePage } from '@inertiajs/react'
import { useState } from 'react'
import NativeModal, { prefetchModalPage } from './NativeModal'

export default function ModalLink({ href, title, context, icon, size = 'wide', returnPath, className, children, ...props }) {
    const [open, setOpen] = useState(false)
    const { version } = usePage()
    const prefetch = () => prefetchModalPage(href, version)

    return (
        <>
            <a
                {...props}
                href={href}
                className={className}
                onMouseEnter={prefetch}
                onFocus={prefetch}
                onClick={(event) => {
                    props.onClick?.(event)
                    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return
                    event.preventDefault()
                    setOpen(true)
                }}
            >
                {children}
            </a>
            <NativeModal
                open={open}
                href={href}
                title={title}
                context={context}
                icon={icon}
                size={size}
                exitPaths={returnPath ? [returnPath] : []}
                onClose={() => setOpen(false)}
            />
        </>
    )
}
