<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'client_name' => $this->faker->company(),
            'contact_number' => $this->faker->phoneNumber(),
            'contact_person_name' => $this->faker->name(),
            'mobile_no' => $this->faker->phoneNumber(),
            'email_id' => $this->faker->companyEmail(),
            'area' => $this->faker->word(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'client_details' => $this->faker->paragraph(),
            'calling_status' => $this->faker->randomElement(['Contacted', 'Pending', 'Not Contacted']),
            'feedback' => $this->faker->paragraph(),
            'vacancy_status' => $this->faker->randomElement(['Open', 'Closed', 'Pending']),
            'requirement_status' => $this->faker->randomElement(['Active', 'Inactive']),
            'proposal_status' => $this->faker->randomElement(['Sent', 'Accepted', 'Rejected', 'Pending']),
            'empannel' => $this->faker->randomElement(['Yes', 'No']),
            'internship_payment' => $this->faker->randomElement(['Paid', 'Unpaid', 'Pending']),
            'final_remark' => $this->faker->paragraph(),
        ];
    }
}
