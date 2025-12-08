<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Colaborador extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'colaborador';

    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'unidade_id'
    ];

   
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome', 'email', 'cpf', 'unidade_id'])
            ->logOnlyDirty()
            ->useLogName('colaborador')
            ->setDescriptionForEvent(fn(string $eventName) => "Colaborador foi {$eventName}");
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }
}
