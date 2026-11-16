<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Supplier> */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'type' => fake()->randomElement(array_keys(Supplier::TYPES)),
            'nik' => fake()->numerify('################'),
            'npwp' => fake()->numerify('##.###.###.#-###.###'),
            'address' => fake()->address(),
            'regency' => fake()->randomElement(['Kampar', 'Siak', 'Rokan Hulu', 'Pelalawan', 'Indragiri Hulu']),
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
            'status' => Supplier::STATUS_ACTIVE,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => Supplier::STATUS_INACTIVE,
            'deactivation_reason' => 'Dokumen tidak valid',
            'deactivated_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => Supplier::STATUS_DRAFT]);
    }
}

