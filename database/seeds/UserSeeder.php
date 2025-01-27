<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\User\UserAddress;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $email = 'lily@wifetch.com';
        if(!User::where('email', '=', $email)->first()){
            $user = new App\Models\User\User();
            $user->name = 'Lily Admin';
            $user->email = $email;
            $user->home_phone = '4444444';
            $user->mobile_phone = '12462625075';
            $user->is_admin = 1;
            $user->password = \Illuminate\Support\Facades\Hash::make('asd123123');
            if($user->save()){
                $this->createDefaultAddress($user, ['country_id' => 1, 'parish_id' => 1]);
            }
        }

        $email = 'lily+merchant@wifetch.com';
        if(!User::where('email', '=', $email)->first()){
            $user = new App\Models\User\User();
            $user->name = 'Lily Merchant';
            $user->email = $email;
            $user->home_phone = '4444444';
            $user->mobile_phone = '12462625075';
            $user->is_merchant = 1;
            $user->password = \Illuminate\Support\Facades\Hash::make('asd123123');
            if($user->save()){
                $this->createDefaultAddress($user, ['country_id' => 1, 'parish_id' => 2]);
            }
        }

        $email = 'lily+buyer@wifetch.com';
        if(!User::where('email', '=', $email)->first()){
            $user = new App\Models\User\User();
            $user->name = 'Lily Buyer';
            $user->email = $email;
            $user->home_phone = '4444444';
            $user->mobile_phone = '12462625075';
            $user->password = \Illuminate\Support\Facades\Hash::make('asd123123');
            if($user->save()){
                $this->createDefaultAddress($user, ['country_id' => 1, 'parish_id' => 3]);
            }
        }


        $email = 'patosmack@gmail.com';
        if(!User::where('email', '=', $email)->first()){
            $user = new App\Models\User\User();
            $user->name = 'Patricio Admin';
            $user->email = $email;
            $user->home_phone = '4819709';
            $user->mobile_phone = '5493515929601';
            $user->is_admin = 1;
            $user->password = \Illuminate\Support\Facades\Hash::make('P3u*y.muteki1');
            if($user->save()){
                $this->createDefaultAddress($user, ['country_id' => 1, 'parish_id' => 1]);
            }
        }

        $email = 'patosmack+merchant@gmail.com';
        if(!User::where('email', '=', $email)->first()){
            $user = new App\Models\User\User();
            $user->name = 'Patricio Merchant';
            $user->email = $email;
            $user->home_phone = '4819709';
            $user->mobile_phone = '5493515929601';
            $user->is_merchant = 1;
            $user->password = \Illuminate\Support\Facades\Hash::make('P3u*y.muteki1');
            if($user->save()){
                $this->createDefaultAddress($user, ['country_id' => 1, 'parish_id' => 2]);
            }
        }

        $email = 'patosmack+buyer@gmail.com';
        if(!User::where('email', '=', $email)->first()){
            $user = new App\Models\User\User();
            $user->name = 'Patricio Buyer';
            $user->email = $email;
            $user->home_phone = '4819709';
            $user->mobile_phone = '5493515929601';
            $user->password = \Illuminate\Support\Facades\Hash::make('P3u*y.muteki1');
            if($user->save()){
                $this->createDefaultAddress($user, ['country_id' => 1, 'parish_id' => 3]);
            }
        }

    }




    /**
     * Create a new user instance after a valid registration.
     * @param  User  $user
     * @param  array  $data
     * @return bool
     */
    protected function createDefaultAddress(User $user, array $data){
        $userAddress = new UserAddress();
        $userAddress->name = 'Primary';
        $userAddress->user_id = $user->id;
        $userAddress->parish_id = $data['parish_id'];
        $userAddress->country_id = $data['country_id'];
        $userAddress->current = 1;
        $userAddress->enabled = 1;
        return $userAddress->save();
    }
}
