<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class UserCompanySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $user = User::first();
        
        if ($company && $user) {
            $user->company_id = $company->id;
            $user->save();
            
            $this->command->info('✅ Usuario asignado a empresa: ' . $company->name);
        }
    }
}