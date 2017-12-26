<?php

use Illuminate\Database\Seeder;
use App\Models\User;

/**
 * 填充follower数据表
 *
 * Class FollowersTableSeeder
 */
class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        //获取去除id为1的所有用户的id的数组
        $followers = $users->slice( 1 );
        $follower_ids = $followers->pluck( 'id' )->toArray();

        $user->follow( $follower_ids );

        foreach ( $followers as $follower ) {
            $follower->follow( $user_id );
        }
    }
}
