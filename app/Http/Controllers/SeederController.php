<?php


namespace App\Http\Controllers;


class SeederController
{
    public function populateData(){
        $user = new \App\Models\User();
        $user->email = "test@example.com";
        $user->name = "test";
        $user->password = \Illuminate\Support\Facades\Hash::make("12345678");
        $user->save();
        echo "Success";
    }
}
