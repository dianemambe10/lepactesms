<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       factory(App\User::class, 3 )->create()->each(function ($u){
           $u->sms()
           ->saveMany(
             factory(App\sms::class, rand(5,9))->make()
           );
       });

       factory(App\Volontaires::class,500)->create();
    }
}
