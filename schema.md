# Schema — Gestor de Proyectos

## Diagrama entidad-relación

```
┌──────────────────────────────────┐         ┌────────────────────────────────────────────┐
│            users                 │         │              proyectos                     │
├──────────────────────────────────┤         ├────────────────────────────────────────────┤
│ PK  id              bigint       │◄──┐     │ PK  id              bigint                 │
│     name            varchar(255) │   │     │ FK  user_id         bigint  → users.id     │
│     email           varchar(255) │   └─────│     nombre          varchar(150)           │
│     password        varchar(255) │         │     descripcion      text (nullable)        │
│     email_verified_at timestamp  │         │     fecha_limite     date (nullable)        │
│     created_at      timestamp    │         │     created_at       timestamp              │
│     updated_at      timestamp    │         │     updated_at       timestamp              │
└──────────────────────────────────┘         └──────────────┬─────────────────────────────┘
                                                            │ 1
                                                            │
                                                            │ N
                                             ┌──────────────▼─────────────────────────────┐
                                             │               tareas                       │
                                             ├────────────────────────────────────────────┤
                                             │ PK  id              bigint                 │
                                             │ FK  proyecto_id     bigint → proyectos.id  │
                                             │     titulo          varchar(200)           │
                                             │     descripcion      text (nullable)        │
                                             │     prioridad        enum                  │
                                             │                       'alta'               │
                                             │                       'media'              │
                                             │                       'baja'               │
                                             │     estado           enum                  │
                                             │                       'backlog'            │
                                             │                       'en_progreso'        │
                                             │                       'testing'            │
                                             │                       'terminada'          │
                                             │     orden            smallint (default: 0) │
                                             │     created_at       timestamp             │
                                             │     updated_at       timestamp             │
                                             └────────────────────────────────────────────┘
```

---

## Tabla: `proyectos`

| Columna       | Tipo           | Nulo | Default | Descripción                          |
|---------------|----------------|------|---------|--------------------------------------|
| `id`          | bigint PK      | NO   | —       | Identificador autoincremental        |
| `user_id`     | bigint FK      | NO   | —       | Usuario creador → `users.id`         |
| `nombre`      | varchar(150)   | NO   | —       | Nombre del proyecto                  |
| `descripcion` | text           | SÍ   | NULL    | Descripción extendida                |
| `fecha_limite`| date           | SÍ   | NULL    | Fecha de entrega esperada            |
| `created_at`  | timestamp      | SÍ   | NULL    | Fecha de creación (Laravel auto)     |
| `updated_at`  | timestamp      | SÍ   | NULL    | Fecha de actualización (Laravel auto)|

**Índices**

| Nombre                         | Columnas   | Tipo    |
|-------------------------------|------------|---------|
| `proyectos_pkey`               | `id`       | PRIMARY |
| `proyectos_user_id_foreign`    | `user_id`  | INDEX   |

**Constraints**

- `user_id` → `users.id` ON DELETE CASCADE

---

## Tabla: `tareas`

| Columna        | Tipo           | Nulo | Default    | Descripción                           |
|----------------|----------------|------|------------|---------------------------------------|
| `id`           | bigint PK      | NO   | —          | Identificador autoincremental         |
| `proyecto_id`  | bigint FK      | NO   | —          | Proyecto al que pertenece             |
| `titulo`       | varchar(200)   | NO   | —          | Título de la tarea                    |
| `descripcion`  | text           | SÍ   | NULL       | Detalle o criterios de aceptación     |
| `prioridad`    | enum           | NO   | `'media'`  | Nivel de prioridad                    |
| `estado`       | enum           | NO   | `'backlog'`| Estado en el flujo de trabajo         |
| `orden`        | smallint       | NO   | `0`        | Posición para ordenar dentro del estado|
| `created_at`   | timestamp      | SÍ   | NULL       | Fecha de creación (Laravel auto)      |
| `updated_at`   | timestamp      | SÍ   | NULL       | Fecha de actualización (Laravel auto) |

**Valores del enum `prioridad`**

| Valor    | Significado                                |
|----------|--------------------------------------------|
| `alta`   | Urgente, impacta en entrega o bloquea algo |
| `media`  | Importante pero no bloquea                 |
| `baja`   | Deseable, puede postergarse                |

**Valores del enum `estado`**

| Valor         | Significado                                 |
|---------------|---------------------------------------------|
| `backlog`     | Pendiente de iniciar                        |
| `en_progreso` | En desarrollo activo                        |
| `testing`     | En revisión / QA                            |
| `terminada`   | Completada y aceptada                       |

**Índices**

| Nombre                           | Columnas       | Tipo    |
|----------------------------------|----------------|---------|
| `tareas_pkey`                    | `id`           | PRIMARY |
| `tareas_proyecto_id_foreign`     | `proyecto_id`  | INDEX   |
| `tareas_estado_orden_index`      | `estado, orden`| INDEX   |

**Constraints**

- `proyecto_id` → `proyectos.id` ON DELETE CASCADE

---

## Relaciones entre modelos (Eloquent)

```
User          hasMany    Proyecto
Proyecto      belongsTo  User
Proyecto      hasMany    Tarea
Tarea         belongsTo  Proyecto
```

---

## Migraciones

### `create_proyectos_table`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectosTable extends Migration
{
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->date('fecha_limite')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
}
```

### `create_tareas_table`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->cascadeOnDelete();

            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();

            $table->enum('prioridad', ['alta', 'media', 'baja'])
                  ->default('media');

            $table->enum('estado', ['backlog', 'en_progreso', 'testing', 'terminada'])
                  ->default('backlog');

            $table->smallInteger('orden')->default(0);

            $table->timestamps();

            // Índice compuesto para consultas de tablero Kanban
            $table->index(['estado', 'orden']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}
```

---

## Modelos Eloquent

### `app/Models/Proyecto.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    // Scopes útiles
    public function scopeDelUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
```

### `app/Models/Tarea.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyecto_id',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'orden',
    ];

    // Constantes de prioridad
    const PRIORIDAD_ALTA  = 'alta';
    const PRIORIDAD_MEDIA = 'media';
    const PRIORIDAD_BAJA  = 'baja';

    // Constantes de estado
    const ESTADO_BACKLOG     = 'backlog';
    const ESTADO_EN_PROGRESO = 'en_progreso';
    const ESTADO_TESTING     = 'testing';
    const ESTADO_TERMINADA   = 'terminada';

    // Mapas para UI
    public static array $prioridades = [
        self::PRIORIDAD_ALTA  => 'Alta',
        self::PRIORIDAD_MEDIA => 'Media',
        self::PRIORIDAD_BAJA  => 'Baja',
    ];

    public static array $estados = [
        self::ESTADO_BACKLOG     => 'Backlog',
        self::ESTADO_EN_PROGRESO => 'En progreso',
        self::ESTADO_TESTING     => 'Testing',
        self::ESTADO_TERMINADA   => 'Terminada',
    ];

    // Relaciones
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Scopes útiles
    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado)->orderBy('orden');
    }

    public function scopePorPrioridad($query, string $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }
}
```

---

## Comandos para ejecutar las migraciones

```bash
# Generar los archivos de migración
php artisan make:migration create_proyectos_table
php artisan make:migration create_tareas_table

# Ejecutar migraciones
php artisan migrate

# Revertir la última tanda (si se necesita corregir algo)
php artisan migrate:rollback

# Reiniciar toda la BD (en desarrollo)
php artisan migrate:fresh
```

---

## Consultas de referencia

```php
// Proyectos del usuario autenticado
$proyectos = Proyecto::where('user_id', auth()->id())
    ->orderBy('fecha_limite')
    ->get();

// Tareas de un proyecto agrupadas por estado (para tablero Kanban)
$columnas = Tarea::where('proyecto_id', $proyecto->id)
    ->orderBy('orden')
    ->get()
    ->groupBy('estado');

// Tareas de alta prioridad en progreso
$urgentes = Tarea::where('proyecto_id', $proyecto->id)
    ->where('prioridad', Tarea::PRIORIDAD_ALTA)
    ->where('estado', Tarea::ESTADO_EN_PROGRESO)
    ->get();
```
