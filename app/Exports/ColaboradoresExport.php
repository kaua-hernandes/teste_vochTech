<?php

namespace App\Exports;

use App\Models\Colaborador;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ColaboradoresExport implements FromCollection, WithHeadings
{
    public $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Colaborador::query()->with('unidade');

        if (!empty($this->filters['unidade_id'])) {
            $query->where('unidade_id', $this->filters['unidade_id']);
        }

        return $query->get()->map(function($colaborador) {
            return [
                'ID' => $colaborador->id,
                'Nome' => $colaborador->nome,
                'E-mail' => $colaborador->email,
                'CPF' => $colaborador->cpf,
                'Unidade' => optional($colaborador->unidade)->nome_fantasia,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nome', 'E-mail', 'CPF', 'Unidade'];
    }
}
