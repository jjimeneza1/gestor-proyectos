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

    // Scopes
    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado)->orderBy('orden');
    }
}
