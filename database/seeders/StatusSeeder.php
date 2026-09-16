<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['key' => 'new', 'label' => 'New', 'type' => 'lead', 'active' => true],
            ['key' => 'contacted', 'label' => 'Contacted', 'type' => 'lead', 'active' => true],
            ['key' => 'interested', 'label' => 'Interested', 'type' => 'lead', 'active' => true],
            ['key' => 'follow_up', 'label' => 'Follow Up', 'type' => 'lead', 'active' => true],
            ['key' => 'demo_scheduled', 'label' => 'Demo Scheduled', 'type' => 'lead', 'active' => true],
            ['key' => 'converted', 'label' => 'Converted', 'type' => 'lead', 'active' => true],
            ['key' => 'lost', 'label' => 'Lost', 'type' => 'lead', 'active' => true],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(['key' => $status['key'], 'type' => $status['type']], $status);
        }
    }
}
