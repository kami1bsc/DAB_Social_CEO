<?php

use Illuminate\Database\Seeder;

use App\User;
use Carbon\Carbon;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',		
            'email' => 'admin@admin.com',
            'profile_image' => 'https://pickaface.net/gallery/avatar/elite_pc_soln5240403213d5e.png',		
            'password' => bcrypt('admin'),
            'type' => USER_TYPES['admin'],            
        ]);
        
        $user = User::create([
            'name' => 'Kamran',		 
            'email' => 'kamranabrar90@gmail.com',
            'phone' => '+92 03236691890',
            'profile_image'	 => 'https://onyxdiary.com/wp-content/uploads/2018/05/iamshagari_-20180522-0003-260x300.jpg',
            'latitude' => '31.402349',
            'longitude' => '74.258506',            
            'password' => bcrypt('abcdefgh'),
            'type' => USER_TYPES['user']
        ]);

        $user = User::create([
            'name' => 'Tania',		 
            'email' => 'tania@gmail.com',            
            'phone' => '+92 03227828654',
            'profile_image'	 => 'https://res.6chcdn.feednews.com/assets/v2/79a7478c65ef38a4c47c7cdc96c513d8?quality=uhq&resize=320',
            'latitude' => '31.402349',
            'longitude' => '74.258506',
            'password' => bcrypt('abcdefgh'),
            'type' => USER_TYPES['user']
        ]);

        $user = User::create([
            'name' => 'Jamal',		 
            'email' => 'jamal@gmail.com',            
            'phone' => '+92 03227828654',
            'profile_image'	 => 'https://www.mandelarhodes.org/static/611eb9376c7ff3bf3559db95e09152ff/0c30b/jonathan-ruwanika.jpg',
            'latitude' => '31.402349',
            'longitude' => '74.258506',            
            'password' => bcrypt('abcdefgh'),
            'type' => USER_TYPES['user']
        ]);
    }
}
