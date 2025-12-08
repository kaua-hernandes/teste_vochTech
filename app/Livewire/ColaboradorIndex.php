<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Colaborador;
use App\Models\Unidade;
use App\Jobs\ExportColaboradoresJob;
use Illuminate\Support\Facades\Storage;

class ColaboradorIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $modalOpen = false;
    public $confirmingDelete = null;

    public $colaboradorId = null;
    public $nome = '';
    public $email = '';
    public $cpf = '';
    public $unidade_id = null;

    public $unidadeSearch = '';
    public $unidadeResults = [];
    public $showUnidadeList = false;

    public $hasUnidade = false;

    // EXPORTAÇÃO
    public $exportModalOpen = false;
    public $unidades = [];
    public $filterUnidade = '';
    public $exportFile = null; 

    // FLAG QUE INDICA QUE O USUÁRIO CLICOU EM "GERAR"
    public $exportReady = false;

    protected function rules()
    {
        return [
            'nome' => 'required|string|min:2',
            'email' => 'required|email|unique:colaborador,email,' . $this->colaboradorId,
            'cpf' => 'required|string|size:14|unique:colaborador,cpf,' . $this->colaboradorId,
            'unidade_id' => 'required|exists:unidade,id',
        ];
    }

    protected $messages = [
        'nome.required' => 'O nome é obrigatório.',
        'nome.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'email.required' => 'O e-mail é obrigatório.',
        'email.email' => 'Informe um e-mail válido.',
        'email.unique' => 'Este e-mail já está cadastrado.',
        'cpf.required' => 'O CPF é obrigatório.',
        'cpf.size' => 'O CPF deve ter 14 caracteres.',
        'cpf.unique' => 'Este CPF já está cadastrado.',
        'unidade_id.required' => 'Selecione uma unidade.',
        'unidade_id.exists' => 'A unidade selecionada não existe.',
    ];

    public function mount()
    {
        $this->hasUnidade = Unidade::exists();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUnidadeSearch()
    {
        $this->showUnidadeList = true;
        $this->unidadeResults = Unidade::where('nome_fantasia', 'like', '%' . $this->unidadeSearch . '%')
            ->orderBy('nome_fantasia')
            ->limit(10)
            ->get();
    }

    public function selectUnidade($id, $nome)
    {
        $this->unidade_id = $id;
        $this->unidadeSearch = $nome;
        $this->showUnidadeList = false;
    }

    public function openModal($id = null)
    {
        if (!$this->hasUnidade) {
            session()->flash('message', 'Cadastre uma Unidade antes de criar um Colaborador.');
            return;
        }

        $this->resetValidation();
        $this->reset(['colaboradorId', 'nome', 'email', 'cpf', 'unidade_id', 'unidadeSearch']);

        if ($id) {
            $c = Colaborador::findOrFail($id);
            $this->colaboradorId = $c->id;
            $this->nome = $c->nome;
            $this->email = $c->email;
            $this->cpf = $c->cpf;
            $this->unidade_id = $c->unidade_id;
            $this->unidadeSearch = optional($c->unidade)->nome_fantasia;
        }

        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
    }

    public function save()
    {
        $this->validate();

        Colaborador::updateOrCreate(
            ['id' => $this->colaboradorId],
            [
                'nome' => $this->nome,
                'email' => $this->email,
                'cpf' => $this->cpf,
                'unidade_id' => $this->unidade_id,
            ]
        );

        session()->flash('message', $this->colaboradorId ? 'Colaborador atualizado!' : 'Colaborador criado!');
        $this->modalOpen = false;
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deleteConfirmed()
    {
        Colaborador::findOrFail($this->confirmingDelete)->delete();
        session()->flash('message', 'Colaborador removido com sucesso!');
        $this->confirmingDelete = null;
    }

    public function openExportModal()
    {
        $this->exportModalOpen = true;
        $this->unidades = Unidade::orderBy('nome_fantasia')->get();
        $this->filterUnidade = '';
        $this->exportFile = null;
        $this->exportReady = false;
    }

    public function export()
    {
        $filters = [];
        if ($this->filterUnidade) {
            $filters['unidade_id'] = $this->filterUnidade;
        }

        $fileName = 'colaboradores.xlsx';

        ExportColaboradoresJob::dispatch($filters, $fileName);

        $this->exportModalOpen = false;
        $this->exportFile = null;
        $this->exportReady = true;

        session()->flash('message', 'Exportação iniciada! Agora clique em "Verificar arquivo".');
    }

    public function checkExport()
    {
        $fileName = 'colaboradores.xlsx';
        if (Storage::disk('public')->exists($fileName)) {
            $this->exportFile = asset('storage/' . $fileName);
            session()->flash('message', 'O arquivo está pronto!');
        } else {
            session()->flash('message', 'O arquivo ainda não está pronto.');
        }
    }

    public function render()
    {
        $colaboradores = Colaborador::with('unidade')
            ->when($this->search, fn($q) => $q->where('nome', 'like', "%{$this->search}%"))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.colaborador-index', compact('colaboradores'));
    }
}
