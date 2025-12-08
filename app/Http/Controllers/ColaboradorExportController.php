<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ColaboradoresExport;
use Maatwebsite\Excel\Facades\Excel;

class ColaboradorExportController extends Controller
{
    public function export(Request $request)
    {
        // Recebe o filtro de unidade do request
        $filters = $request->only('unidade_id');

        return Excel::download(new ColaboradoresExport($filters), 'colaboradores.xlsx');
    }
}
