<?php

namespace App\Commands;

use App\Models\User;

class ImportUsersCommand {

    public function command($args) {
        $files_count = 0;
        $users_count = 0;
        $files = glob('/home/balus/users_to_import/*');
        foreach ($files as $file) {
            $files_count++;
            $users = json_decode(file_get_contents($file));
            foreach ($users as $user) {
                $users_count++;
                User::create([
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'name' => $user->email,
                    'password' => password_hash(uniqid(),PASSWORD_DEFAULT),
                    'activ_code' => urlencode(uniqid()),
                    'cvadded' => false,
                    'activ' => false,
                    'group_id' => 0
                ]);

                /*
                $new_user = new User();
                $new_user->first_name = $user->first_name;
                $new_user->last_name = $user->last_name;
                $new_user->email = $user->email;
                $new_user->name = $user->email;
                $new_user->password = password_hash(uniqid(),PASSWORD_DEFAULT);
                $new_user->activ_code = urlencode(uniqid());
                $new_user->cvadded = false;
                $new_user->save();*/
            }
        }
        exit($users_count . ' users added from ' . $files_count . ' files.');
    }
}