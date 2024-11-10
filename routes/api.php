<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryApiController;
use App\Models\User;

Route::apiResource('/categories',CategoryApiController::class);
Route::post('/login',function(){
    $email=request()->email;
    $password=request()->password;
    if(!$email or !$password){
        return response(['msg'=>'email and password required'],400);
    }
    $user=User::where("email",$email)->firstOrFail();
    if(password_verify($password,$user->password)){
        return $user->createToken("api")->plainTextToken;
    }
    return response(['msg'=>'incorrect email or password'],401);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
