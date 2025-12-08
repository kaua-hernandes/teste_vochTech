<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Unidade extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'unidade';
    
    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'bandeira_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome_fantasia', 'razao_social', 'cnpj', 'bandeira_id'])
            ->logOnlyDirty()
            ->useLogName('unidade')
            ->setDescriptionForEvent(fn(string $eventName) => "Unidade foi {$eventName}");
    }

    public function bandeira()
    {
        return $this->belongsTo(Bandeira::class);
    }

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class);
    }
}
