<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Portfoliodatos extends Model
{
    protected $fillable = [
        'portfolio_id',
        'implicacion',
        'tecnologias',
        'cliente',
        'enlace_proyecto'
    ];
    protected $casts = [
        'tecnologias' => 'array',

    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Accessor para las tecnologías (opcional, por si quieres formatear)
     */
    protected function tecnologias(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_array($value)) {
                    return $value;
                }

                if (is_string($value)) {
                    // Si es un string JSON, decodificar
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $decoded;
                    }

                    // Si es un string separado por comas, convertir a array
                    return array_map('trim', explode(',', $value));
                }

                return [];
            },
            set: function ($value) {
                if (is_array($value)) {
                    return json_encode($value);
                }

                if (is_string($value)) {
                    // Si ya es un string JSON, dejarlo como está
                    json_decode($value);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $value;
                    }

                    // Si es un string separado por comas, convertir a JSON
                    $array = array_map('trim', explode(',', $value));
                    return json_encode($array);
                }

                return json_encode([]);
            }
        );
    }
    protected function enlaceProyecto(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value)) {
                    return null;
                }

                // Si no empieza con http:// o https://, lo agrega
                if (!preg_match("~^(?:f|ht)tps?://~i", $value)) {
                    return 'https://' . $value;
                }

                return $value;
            },
            set: function ($value) {
                if (empty($value)) {
                    return null;
                }

                // Eliminar espacios y asegurar formato limpio
                $value = trim($value);

                // Remover protocolo duplicado si existe
                $value = preg_replace('/^(https?:\/\/)(https?:\/\/)/i', '$1', $value);

                return $value;
            }
        );
    }

    /**
     * Verificar si tiene enlace de proyecto
     */
    public function tieneEnlace(): bool
    {
        return !empty($this->enlace_proyecto);
    }

    /**
     * Obtener tecnologías como lista HTML
     */
    public function tecnologiasComoLista(): string
    {
        if (empty($this->tecnologias)) {
            return '';
        }

        return implode(', ', $this->tecnologias);
    }

    /**
     * Obtener tecnologías como badges Bootstrap
     */
    public function tecnologiasComoBadges(): string
    {
        if (empty($this->tecnologias)) {
            return '';
        }

        $badges = array_map(function ($tech) {
            return '<span class="badge bg-primary me-1 mb-1">' . e($tech) . '</span>';
        }, $this->tecnologias);

        return implode(' ', $badges);
    }

    /**
     * Scope para filtrar por cliente
     */
    public function scopePorCliente($query, $cliente)
    {
        return $query->where('cliente', 'like', "%{$cliente}%");
    }

    /**
     * Scope para filtrar por tecnología
     */
    public function scopeConTecnologia($query, $tecnologia)
    {
        return $query->whereJsonContains('tecnologias', $tecnologia);
    }
}
