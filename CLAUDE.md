# Contexto del proyecto — Gestor de Proyectos

## Stack técnico

| Capa          | Tecnología                              |
|---------------|-----------------------------------------|
| Framework     | Laravel 8 · PHP 7.4                     |
| Autenticación | Laravel Breeze v1.10 (Blade stack)      |
| CSS           | Tailwind CSS (via Laravel Mix/PostCSS)  |
| JS interactivo| Alpine.js v3                            |
| Build tool    | Laravel Mix 6 (webpack)                 |
| Base de datos | MySQL · `gestor_proyectos`              |

## Credenciales de prueba

```
Email:      admin@gestor.com
Contraseña: Admin@2024#Secure!
```

> Seeder: `php artisan db:seed --class=UserSeeder`

---

## Layout principal: `resources/views/layouts/app.blade.php`

Layout con sidebar lateral oscuro (`bg-gray-900`). Incluye:
- Sidebar fijo en desktop, overlay en mobile (Alpine.js)
- Topbar con título de página y menú de usuario
- Flash messages automáticos desde `session()`
- Modal global de confirmación de eliminación
- Sistema de notificaciones toast

### Slots disponibles

| Slot          | Tipo   | Descripción                                  |
|---------------|--------|----------------------------------------------|
| `$slot`       | main   | Contenido principal de la página             |
| `$header`     | string | Título en el topbar                          |
| `$breadcrumbs`| blade  | Área de breadcrumbs (ver componente abajo)   |
| `$title`      | string | `<title>` del HTML (default: config app.name)|
| `@stack('styles')` | —  | CSS adicional por vista                 |
| `@stack('scripts')`| —  | JS adicional por vista                  |

### Ejemplo de página completa

```blade
<x-app-layout>
    <x-slot name="title">Proyectos</x-slot>
    <x-slot name="header">Proyectos</x-slot>
    <x-slot name="breadcrumbs">
        <x-crud.breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Proyectos'],
        ]"/>
    </x-slot>

    <x-crud.page-header title="Proyectos" subtitle="Gestión de proyectos">
        <x-slot name="actions">
            <a href="{{ route('proyectos.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                + Nuevo proyecto
            </a>
        </x-slot>
    </x-crud.page-header>

    <x-crud.table :headers="['Nombre', 'Estado', 'Fecha', 'Acciones']">
        @forelse($proyectos as $p)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $p->nombre }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $p->estado }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4">
                    <x-crud.action-buttons
                        :edit-url="route('proyectos.edit', $p)"
                        :delete-url="route('proyectos.destroy', $p)"
                        :delete-name="$p->nombre"
                    />
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-16">
                    <x-crud.empty-state
                        message="No hay proyectos registrados."
                        :create-url="route('proyectos.create')"
                        create-label="+ Nuevo proyecto"
                    />
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $proyectos->links() }}
        </x-slot>
    </x-crud.table>
</x-app-layout>
```

---

## Alpine.js — `crudApp` (global en el `<body>`)

Registrado en `resources/js/app.js` via `Alpine.data('crudApp', ...)`.

### API

```javascript
// Abre el modal de confirmación de eliminación
confirmDelete(url, nombreDelRegistro, tipo = 'danger')
// tipo: 'danger' (rojo) | 'warning' (amarillo)

// Cierra el modal (también con tecla Escape)
closeModal()

// Muestra notificación toast (esquina inferior derecha)
notify(mensaje, tipo = 'success', duracionMs = 4000)
// tipo: 'success' | 'error' | 'warning' | 'info'
// duracion 0 = no se cierra automáticamente
```

### Uso desde Blade

```blade
{{-- Botón eliminar con confirmación --}}
<button @click="confirmDelete('{{ route('items.destroy', $item) }}', '{{ $item->nombre }}')">
    Eliminar
</button>

{{-- Toast desde JS (ej: después de copiar al portapapeles) --}}
<button @click="notify('¡Copiado!', 'success', 2000)">Copiar</button>
```

---

## Flash messages desde controladores

```php
// En el controlador, redirigir con mensaje flash:
return redirect()->route('proyectos.index')
    ->with('success', 'Proyecto creado correctamente.');

// Tipos disponibles: success, error, warning, info
```

Se renderizan automáticamente en el layout via `<x-alert>`.

---

## Componentes disponibles

### `<x-alert>`
```blade
<x-alert type="success" message="Operación exitosa." />
<x-alert type="error"   message="Error al guardar." :dismissible="false" />
{{-- tipos: success | error | warning | info --}}
```

### `<x-crud.page-header>`
```blade
<x-crud.page-header title="Usuarios" subtitle="Gestión de usuarios del sistema">
    <x-slot name="actions">
        <a href="{{ route('usuarios.create') }}" class="...">+ Nuevo</a>
    </x-slot>
</x-crud.page-header>
```

### `<x-crud.table>`
```blade
<x-crud.table :headers="['Col 1', 'Col 2', 'Acciones']">
    {{-- filas <tr> aquí --}}
    <x-slot name="pagination">{{ $items->links() }}</x-slot>
</x-crud.table>
```

### `<x-crud.action-buttons>`
```blade
<x-crud.action-buttons
    :show-url="route('items.show', $item)"      {{-- opcional --}}
    :edit-url="route('items.edit', $item)"      {{-- opcional --}}
    :delete-url="route('items.destroy', $item)" {{-- opcional --}}
    :delete-name="$item->nombre"
/>
```

### `<x-crud.breadcrumb>`
```blade
<x-crud.breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Módulo',    'url' => route('modulo.index')],
    ['label' => 'Subpágina'],   {{-- sin url = ítem actual --}}
]"/>
```

### `<x-crud.empty-state>`
```blade
<x-crud.empty-state
    message="No hay registros."
    :create-url="route('items.create')"
    create-label="+ Nuevo"
/>
```

### `<x-sidebar-link>` (usado en `navigation.blade.php`)
```blade
<x-sidebar-link :href="route('modulo.index')" :active="request()->routeIs('modulo.*')">
    <x-slot name="icon">
        <svg class="w-5 h-5" ...>...</svg>
    </x-slot>
    Nombre del módulo
</x-sidebar-link>
```

---

## Añadir un nuevo módulo CRUD

1. **Ruta** en `routes/web.php`:
   ```php
   Route::resource('proyectos', ProyectoController::class)->middleware('auth');
   ```

2. **Controlador**:
   ```
   php artisan make:controller ProyectoController --resource --model=Proyecto
   ```

3. **Modelo + migración**:
   ```
   php artisan make:model Proyecto -m
   php artisan migrate
   ```

4. **Enlace en sidebar** (`resources/views/layouts/navigation.blade.php`):
   - Agrega un `<x-sidebar-link>` en la sección de módulos.

5. **Vistas** en `resources/views/proyectos/`:
   - `index.blade.php` — usa `<x-crud.table>`
   - `create.blade.php` — formulario de creación
   - `edit.blade.php` — formulario de edición

---

## Clases Tailwind de referencia

```html
<!-- Botón primario -->
<a class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">

<!-- Botón secundario -->
<a class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">

<!-- Botón peligro -->
<button class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">

<!-- Input de formulario -->
<input class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

<!-- Label -->
<label class="block text-sm font-medium text-gray-700 mb-1">

<!-- Mensaje de error de validación -->
<p class="mt-1 text-xs text-red-600">{{ $message }}</p>

<!-- Badge/Etiqueta -->
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
```

---

## Comandos útiles

```bash
php artisan serve                        # Servidor de desarrollo
npm run dev                              # Compilar assets (una vez)
npm run watch                            # Compilar assets en modo watch
php artisan migrate                      # Ejecutar migraciones
php artisan db:seed --class=UserSeeder  # Crear usuario admin
php artisan make:controller NombreController --resource --model=Nombre
php artisan make:model Nombre -m         # Modelo + migración
php artisan route:list                   # Ver todas las rutas
php artisan view:clear                   # Limpiar caché de vistas
php artisan config:clear                 # Limpiar caché de configuración
```

---

## Schema de base de datos

Ver `schema.md` para el diseño completo con ER, tipos de columna y consultas de referencia.

### Tablas del dominio

| Tabla       | Descripción                                | Migración                                |
|-------------|--------------------------------------------|------------------------------------------|
| `users`     | Usuarios del sistema (Breeze)              | `2014_10_12_000000_create_users_table`   |
| `proyectos` | Proyectos con nombre, descripción y fecha  | `2026_02_17_205902_create_proyectos_table` |
| `tareas`    | Tareas con prioridad y estado Kanban       | `2026_02_17_205912_create_tareas_table`  |

### Enums de `tareas`

```php
// prioridad
'alta' | 'media' | 'baja'          // default: 'media'

// estado
'backlog' | 'en_progreso' | 'testing' | 'terminada'   // default: 'backlog'
```

### Relaciones

```
User (1) ──► (N) Proyecto (1) ──► (N) Tarea
```

Ambas FK tienen `cascadeOnDelete` (borrar proyecto borra sus tareas).

---

## Historial de cambios relevantes

- **Instalado** Laravel Breeze v1.10.0 (compatible PHP 7.4)
- **Corregido** `@vite()` → `{{ mix() }}` en layouts (incompatibilidad Laravel 8 vs 9)
- **DB_HOST** cambiado de `127.0.0.1` a `localhost` (Laragon en Windows usa named pipe)
- **Creada** BD `gestor_proyectos` y ejecutadas migraciones base
- **Creado** `UserSeeder` con usuario admin (`admin@gestor.com`)
- **Implementada** estructura CRUD reutilizable: sidebar layout + 6 componentes Blade + funciones Alpine.js
- **Creado** `schema.md` con ER, tablas `proyectos` y `tareas`, migraciones y modelos Eloquent
- **Ejecutadas** migraciones `proyectos` y `tareas` en BD
- **Implementado** CRUD completo de Proyectos: `ProyectoController` (resource), `ProyectoRequest`, Model con scope, 5 vistas (index, create, edit, show, _form), rutas REST en `web.php`, enlace en sidebar
