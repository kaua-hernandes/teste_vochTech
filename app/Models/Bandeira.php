<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Bandeira extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'bandeira';

    protected $fillable = [
        'nome',
        'grupo_economico_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome', 'grupo_economico_id'])
            ->logOnlyDirty()
            ->useLogName('bandeira')
            ->setDescriptionForEvent(fn(string $eventName) => "Bandeira foi {$eventName}");
    }

    public function grupoEconomico()
    {
        return $this->belongsTo(GrupoEconomico::class);
    }

    public function unidades()
    {
        return $this->hasMany(Unidade::class);
    }
}
