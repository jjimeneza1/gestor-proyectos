import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * ================================================================
 * crudApp — Componente Alpine.js global para el layout de CRUDs.
 *
 * Registrado con Alpine.data() y disponible en toda la app
 * a través del atributo x-data="crudApp" en el <body>.
 *
 * API pública:
 *   confirmDelete(url, name, type)  → abre modal de confirmación
 *   closeModal()                    → cierra el modal
 *   notify(message, type, duration) → muestra notificación toast
 * ================================================================
 */
Alpine.data('crudApp', () => ({

    // -----------------------------------------------------------------
    // Estado del sidebar
    // Abierto por defecto en pantallas >= 1024px (lg)
    // -----------------------------------------------------------------
    sidebarOpen: window.innerWidth >= 1024,

    // -----------------------------------------------------------------
    // Modal de confirmación de eliminación
    // -----------------------------------------------------------------
    modal: {
        show:    false,
        title:   'Confirmar eliminación',
        message: '',
        action:  '',
        type:    'danger', // 'danger' | 'warning'
    },

    // -----------------------------------------------------------------
    // Notificaciones toast
    // -----------------------------------------------------------------
    notifications:  [],
    _notifCounter:  0,

    // =================================================================
    // MODAL
    // =================================================================

    /**
     * Abre el modal de confirmación antes de eliminar un registro.
     *
     * @param {string} action  URL del endpoint (ruta destroy del recurso)
     * @param {string} name    Nombre legible del registro (ej: "Proyecto Alpha")
     * @param {string} type    'danger' (rojo) | 'warning' (amarillo)
     *
     * Ejemplo de uso en Blade:
     *   <button @click="confirmDelete('{{ route('proyectos.destroy', $p) }}', '{{ $p->nombre }}')">
     *       Eliminar
     *   </button>
     */
    confirmDelete(action, name = '', type = 'danger') {
        this.modal = {
            show:    true,
            title:   'Confirmar eliminación',
            message: name
                ? `¿Estás seguro de que deseas eliminar "${name}"? Esta acción no se puede deshacer.`
                : '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.',
            action,
            type,
        };
    },

    /**
     * Cierra el modal sin ejecutar ninguna acción.
     * También se dispara con la tecla Escape (ver @keydown.escape en el layout).
     */
    closeModal() {
        this.modal.show = false;
    },

    // =================================================================
    // NOTIFICACIONES TOAST
    // =================================================================

    /**
     * Muestra una notificación toast en la esquina inferior derecha.
     *
     * @param {string} message   Texto del mensaje
     * @param {string} type      'success' | 'error' | 'warning' | 'info'
     * @param {number} duration  Ms hasta auto-cierre. 0 = no se cierra solo.
     *
     * Ejemplo de uso en Blade / Alpine:
     *   <button @click="notify('Guardado correctamente', 'success')">Guardar</button>
     *
     * Desde un controlador usa flash messages (se convierten a toast automáticamente
     * si se desea) o directamente con session()->flash().
     */
    notify(message, type = 'success', duration = 4000) {
        const id = ++this._notifCounter;
        this.notifications.push({ id, message, type, visible: true });

        if (duration > 0) {
            setTimeout(() => this.removeNotification(id), duration);
        }
    },

    /**
     * Elimina una notificación por su ID (con fade-out animado).
     * @param {number} id
     */
    removeNotification(id) {
        const n = this.notifications.find(n => n.id === id);
        if (n) n.visible = false;
        // Espera a que termine la transición de salida antes de quitar del array
        setTimeout(() => {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }, 300);
    },

}));

Alpine.start();
