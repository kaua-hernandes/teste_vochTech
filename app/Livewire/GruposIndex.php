<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GrupoEconomico;

class GruposIndex extends Component
{
    use WithPagination;

    public $nome;
    public $grupoId;
    public $isModalOpen = false;
    public $confirmingId;
    public $search = '';

    protected $rules = [
        'nome' => 'required|string|max:255',
    ];

    protected $messages = [
        'nome.required' => 'O nome do Grupo Econômico é obrigatório.',
        'nome.string' => 'O nome deve ser uma sequência de caracteres válida.',
        'nome.max' => 'O nome não pode ter mais que 255 caracteres.',
    ];

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $grupos = GrupoEconomico::query()
            ->when($this->search, fn($q) => $q->where('nome', 'like', "%{$this->search}%"))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.grupos-index', [
            'grupos' => $grupos,
        ]);
    }

    public function abrirModal($id = null)
    {
        $this->resetValidation();

        if ($id) {
            $grupo = GrupoEconomico::findOrFail($id);
            $this->grupoId = $grupo->id;
            $this->nome = $grupo->nome;
        } else {
            $this->reset(['grupoId', 'nome']);
        }

        $this->isModalOpen = true;
    }

    public function fecharModal()
    {
        $this->isModalOpen = false;
    }

    public function salvar()
    {
        $this->validate();

        GrupoEconomico::updateOrCreate(
            ['id' => $this->grupoId],
            ['nome' => $this->nome]
        );

        $this->fecharModal();
        session()->flash('message', $this->grupoId ? 'Grupo Econômico atualizado com sucesso!' : 'Grupo Econômico criado com sucesso!');
    }

    public function confirmarDeletar($id)
    {
        $this->confirmingId = $id;
    }

    public function deletarConfirmado()
    {
        GrupoEconomico::findOrFail($this->confirmingId)->delete();
        $this->confirmingId = null;
        session()->flash('message', 'Grupo Econômico deletado com sucesso!');
    }
}
