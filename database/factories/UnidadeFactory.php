<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unidade>
 */
class UnidadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome_fantasia' => $this->faker->company(),
            'razao_social' => $this->faker->company() . ' LTDA',
            'cnpj' => $this->faker->numerify('##.###.###/####-##'),
            'bandeira_id' => \App\Models\Bandeira::factory(),
        ];
    }

}
