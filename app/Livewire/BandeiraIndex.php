<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bandeira;
use App\Models\GrupoEconomico;

class BandeiraIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $modalOpen = false;
    public $confirmingDelete = null;

    public $bandeiraId = null;
    public $nome = '';
    public $grupo_economico_id = null;

    public $grupoSearch = '';
    public $grupoResults = [];
    public $showGrupoList = false;

    public $hasGrupoEconomico = false;

    protected function rules()
    {
        return [
            'nome' => 'required|string|min:2',
            'grupo_economico_id' => 'required|exists:grupo_economico,id',
        ];
    }

    protected $messages = [
        'nome.required' => 'O nome da bandeira é obrigatório.',
        'nome.min' => 'O nome da bandeira deve ter pelo menos 2 caracteres.',
        'grupo_economico_id.required' => 'Selecione um Grupo Econômico.',
        'grupo_economico_id.exists' => 'O Grupo Econômico selecionado não existe.',
    ];

   
    public function mount()
    {
        $this->hasGrupoEconomico = GrupoEconomico::exists();
    }

    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGrupoSearch()
    {
        $this->showGrupoList = true;

        $this->grupoResults = GrupoEconomico::where('nome', 'like', '%' . $this->grupoSearch . '%')
            ->orderBy('nome')
            ->limit(10)
            ->get();
    }

    public function selectGrupo($id, $nome)
    {
        $this->grupo_economico_id = $id;
        $this->grupoSearch = $nome;
        $this->showGrupoList = false;
    }

   
    public function canOpenModal(): bool
    {
        if (!$this->hasGrupoEconomico) {
            session()->flash('message', 'Cadastre um Grupo Econômico antes de criar uma Bandeira.');
            return false;
        }
        return true;
    }

  
    public function openModal($id = null)
    {
        if (!$this->canOpenModal()) {
            return;
        }

        $this->resetValidation();
        $this->reset(['bandeiraId', 'nome', 'grupoSearch', 'grupo_economico_id']);

        if ($id) {
            $b = Bandeira::findOrFail($id);
            $this->bandeiraId = $b->id;
            $this->nome = $b->nome;
            $this->grupo_economico_id = $b->grupo_economico_id;
            $this->grupoSearch = optional($b->grupoEconomico)->nome;
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

        Bandeira::updateOrCreate(
            ['id' => $this->bandeiraId],
            [
                'nome' => $this->nome,
                'grupo_economico_id' => $this->grupo_economico_id,
            ]
        );

        session()->flash('message', $this->bandeiraId ? 'Bandeira atualizada com sucesso!' : 'Bandeira criada com sucesso!');

        $this->modalOpen = false;
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deleteConfirmed()
    {
        Bandeira::findOrFail($this->confirmingDelete)->delete();
        session()->flash('message', 'Bandeira removida com sucesso!');
        $this->confirmingDelete = null;
    }

    public function render()
    {
        $bandeiras = Bandeira::with('grupoEconomico')
            ->when($this->search, fn($q) => $q->where('nome', 'like', "%{$this->search}%"))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.bandeira-index', [
            'bandeiras' => $bandeiras,
        ]);
    }
}
