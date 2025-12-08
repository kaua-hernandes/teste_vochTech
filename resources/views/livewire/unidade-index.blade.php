<div class="p-6">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">Unidades</h1>

        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">

            <input type="text"
                   wire:model.live.debounce.1000ms="search"
                   placeholder="Buscar por nome..."
                   class="border px-4 py-2 rounded shadow w-full md:w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <button wire:click="openModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded shadow transition">
                + Nova Unidade
            </button>
        </div>
    </div>


    @if (session()->has('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif



    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nome Fantasia
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        CNPJ
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Bandeira
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Grupo Econômico
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>


            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($unidades as $u)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $u->id }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $u->nome_fantasia }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $u->cnpj }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $u->bandeira->nome ?? '—' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $u->bandeira->grupoEconomico->nome ?? '—' }}
                        </td>

                        <td class="px-6 py-4 flex space-x-2">

                            <button wire:click="openModal({{ $u->id }})"
                                    class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 18.07a4.5 4.5 0 0 1-1.897 1.13L6 20.25l1.096-4.432a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18.75 10.5H12m0 0H7.5" />
                                </svg>
                                Editar
                            </button>

                            <button wire:click="confirmDelete({{ $u->id }})"
                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0-.352-9m-4.672-4.148h14.348M7.214 4.148v.852m-1.786 0V18a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V5m-4 0v-.852m-4 0V5" />
                                </svg>
                                Deletar
                            </button>

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma unidade encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>


    <div class="mt-4">
        {{ $unidades->links() }}
    </div>



    @if($modalOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">

                <h2 class="text-2xl font-bold mb-4 text-gray-800">
                    {{ $unidadeId ? 'Editar Unidade' : 'Nova Unidade' }}
                </h2>


                <input type="text"
                       wire:model.defer="nome_fantasia"
                       placeholder="Nome Fantasia"
                       class="border border-gray-300 rounded px-4 py-2 w-full mb-4 focus:ring-2 focus:ring-blue-500">

                @error('nome_fantasia') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror


                <input type="text"
                       wire:model.defer="razao_social"
                       placeholder="Razão Social"
                       class="border border-gray-300 rounded px-4 py-2 w-full mb-4">

                @error('razao_social') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror


                <input type="text"
                       wire:model.defer="cnpj"
                       placeholder="CNPJ"
                       class="border border-gray-300 rounded px-4 py-2 w-full mb-4">

                @error('cnpj') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror


                <div class="relative mb-4">

                    <input type="text"
                           wire:model.live.debounce.1000ms="bandeiraSearch"
                           placeholder="Buscar Bandeira..."
                           class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-2 focus:ring-blue-500">

                    @if($showBandeiraList)
                        <ul class="absolute bg-white w-full border rounded shadow max-h-40 overflow-y-auto z-50">
                            @forelse($bandeiraResults as $b)
                                <li wire:click="selectBandeira({{ $b->id }}, '{{ $b->nome }}')"
                                    class="px-4 py-2 hover:bg-blue-100 cursor-pointer">
                                    {{ $b->nome }}
                                    <span class="text-gray-500 text-xs"> — {{ $b->grupoEconomico->nome ?? '' }}</span>
                                </li>
                            @empty
                                <li class="px-4 py-2 text-gray-500">Nenhum resultado encontrado.</li>
                            @endforelse
                        </ul>
                    @endif
                </div>

                @error('bandeira_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror


                <div class="flex justify-end space-x-3 mt-4">

                    <button wire:click="closeModal"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                        Cancelar
                    </button>

                    <button wire:click="save"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                        Salvar
                    </button>

                </div>

            </div>
        </div>
    @endif



    @if($confirmingDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm text-center">

                <h2 class="text-xl font-bold mb-4 text-gray-800">Tem certeza?</h2>

                <p class="mb-4 text-gray-600">
                    Esta ação irá excluir a unidade permanentemente.
                </p>

                <div class="flex justify-center space-x-4">
                    <button wire:click="$set('confirmingDelete', null)"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                        Cancelar
                    </button>

                    <button wire:click="deleteConfirmed"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">
                        Deletar
                    </button>
                </div>

            </div>

        </div>
    @endif


</div>