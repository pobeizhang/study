<?php

use Illuminate\Database\Seeder;
use \App\Models\User;

/**
 * 用户表数据填充类
 *
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        $users = factory( User::class )->times( 50 )->make();
        User::insert( $users->makeVisible( [ 'password', 'remember_token' ] )->toArray() );

        $user = User::find( 1 );
        $user->name = 'ArleyDu';
        $user->email = 'arleydu@163.com';
        $user->password = bcrypt( 'admin888' );
        $user->is_admin = true;
        $user->save();
    }
}
