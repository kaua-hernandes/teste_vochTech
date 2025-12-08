<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Unidade;
use App\Models\Bandeira;

class UnidadeIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $modalOpen = false;
    public $confirmingDelete = null;

    public $unidadeId = null;
    public $nome_fantasia = '';
    public $razao_social = '';
    public $cnpj = '';
    public $bandeira_id = null;

    public $bandeiraSearch = '';
    public $bandeiraResults = [];
    public $showBandeiraList = false;

    public $hasBandeira = false;

   
    protected function rules()
    {
        return [
            'nome_fantasia' => 'required|string|min:2',
            'razao_social'  => 'required|string|min:2',
            'cnpj'          => 'required|string|size:14|unique:unidade,cnpj,' . $this->unidadeId,
            'bandeira_id'   => 'required|exists:bandeira,id',
        ];
    }

    protected $messages = [
        'nome_fantasia.required' => 'O nome fantasia é obrigatório.',
        'nome_fantasia.min'      => 'O nome fantasia deve ter pelo menos 2 caracteres.',
        'razao_social.required'  => 'A razão social é obrigatória.',
        'razao_social.min'       => 'A razão social deve ter pelo menos 2 caracteres.',
        'cnpj.required'          => 'O CNPJ é obrigatório.',
        'cnpj.size'              => 'O CNPJ deve ter exatamente 14 caracteres.',
        'cnpj.unique'            => 'Este CNPJ já está cadastrado.',
        'bandeira_id.required'   => 'Selecione uma bandeira.',
        'bandeira_id.exists'     => 'A bandeira selecionada não existe.',
    ];

   
    public function mount()
    {
        $this->hasBandeira = Bandeira::exists();
    }

  
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingBandeiraSearch()
    {
        $this->showBandeiraList = true;

        $this->bandeiraResults = Bandeira::with('grupoEconomico')
            ->where('nome', 'like', '%' . $this->bandeiraSearch . '%')
            ->orderBy('nome')
            ->limit(10)
            ->get();
    }

    public function selectBandeira($id, $nome)
    {
        $this->bandeira_id = $id;
        $this->bandeiraSearch = $nome;
        $this->showBandeiraList = false;
    }

   
    public function canOpenModal(): bool
    {
        if (! $this->hasBandeira) {
            session()->flash('message', 'Cadastre uma Bandeira antes de criar uma Unidade.');
            return false;
        }
        return true;
    }

    
    public function openModal($id = null)
    {
        if (! $this->canOpenModal()) {
            return;
        }

        $this->resetValidation();
        $this->reset([
            'unidadeId',
            'nome_fantasia',
            'razao_social',
            'cnpj',
            'bandeira_id',
            'bandeiraSearch',
        ]);

        if ($id) {
            $u = Unidade::findOrFail($id);
            $this->unidadeId      = $u->id;
            $this->nome_fantasia  = $u->nome_fantasia;
            $this->razao_social   = $u->razao_social;
            $this->cnpj           = $u->cnpj;
            $this->bandeira_id    = $u->bandeira_id;
            $this->bandeiraSearch = optional($u->bandeira)->nome;
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

        Unidade::updateOrCreate(
            ['id' => $this->unidadeId],
            [
                'nome_fantasia' => $this->nome_fantasia,
                'razao_social'  => $this->razao_social,
                'cnpj'          => $this->cnpj,
                'bandeira_id'   => $this->bandeira_id,
            ]
        );

        session()->flash(
            'message',
            $this->unidadeId ? 'Unidade atualizada com sucesso!' : 'Unidade criada com sucesso!'
        );

        $this->modalOpen = false;
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deleteConfirmed()
    {
        Unidade::findOrFail($this->confirmingDelete)->delete();
        session()->flash('message', 'Unidade removida com sucesso!');
        $this->confirmingDelete = null;
    }

   
    public function render()
    {
        $unidades = Unidade::with('bandeira.grupoEconomico')
            ->when($this->search, fn($q) =>
                $q->where('nome_fantasia', 'like', "%{$this->search}%")
            )
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.unidade-index', [
            'unidades' => $unidades,
        ]);
    }
}
