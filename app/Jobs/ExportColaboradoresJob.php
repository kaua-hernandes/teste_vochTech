<?php

namespace App\Jobs;

use App\Exports\ColaboradoresExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportColaboradoresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filters;
    public $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct(array $filters = [], string $fileName = 'colaboradores.xlsx')
    {
        $this->filters = $filters;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Gera o Excel filtrando os colaboradores
        Excel::store(new ColaboradoresExport($this->filters), $this->fileName, 'public');
    }
}
