<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Citizen;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
       Model::unguard();

       Citizen::truncate();

       factory(Citizen::class, 100)->create();

       Model::reguard();
    }
}
