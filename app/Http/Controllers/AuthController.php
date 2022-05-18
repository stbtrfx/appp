<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use Laravel\Socialite\Facades\Socialite;
use App\Provider;


use App\User;
use Validator;
use Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','socialLogin']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // dd($request->identify);

        $value = request()->input('identify'); // email@gmail  or 293293923293
// dd($value);
        $field = filter_var($value, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        request()->merge([$field =>$value ]);

        $credentials = request([$field, 'password']);
        // dd($credentials);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if($request->has('fcm_token')){
            $fcm_token = $request->fcm_token;
            $user = Auth::guard('api')->user();
            $user->update(['fcm_token'=> $fcm_token]);
            }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $data = $request->all();
        $data['code'] = Str::random(10);
        // $user = User::create(array_merge(
        //             $validator->validated(),
        //             ['password' => bcrypt($request->password)],
        //             $user->attachRole(5);
        //         ));
        // $code= Str::random(10);
    //  dd($data);
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'code'  => $data['code'],
        ]);


        $user->attachRole(5); // customer default

        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $user
        // ], 201);


        $credentials = request(['email', 'password']);
        // dd($credentials);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if($request->has('fcm_token')){
            // dd('here');
            $fcm_token = $request->fcm_token;
            $user->update(['fcm_token'=> $fcm_token]);
            }

// add points
         if($request->aff_code){
            $code_owner =  User::where('code',$request->aff_code)->first();
            $new_user= $user;
            if(isset($code_owner)){
                    $code_owner->balance += 100;
                    $new_user->balance += 100;
                    $code_owner->save();
                    $new_user->save();
            }else{
                return response()->json(['success'=>'false', 'error'=>'الكود غير صحيح']);
            }
         }
          
// end add pints
        return $this->respondWithToken($token);

    }




    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::guard('api')->user();
        return response()->json([
            'data'=>$user,
            'access_token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }


// *****************************************************************************************


    public function socialLogin(Request $request)
{

    $provider = $request->input('provider'); // or $request->input('provider_name') for multiple providers
    $token = $request->input('access_token');    // get the provider's user. (In the provider server)
    $providerUser = Socialite::driver($provider)->userFromToken($token);    // check if access token exists etc..    // search for a user in our server with the specified provider id and provider name
//    dd($providerUser);
    $user = Provider::where('provider', $provider)->where('provider_id', $providerUser->id)->first();    // if there is no record with these data, create a new user
    //dd($user);
    if($user == null){
        // dd('if');

        // $check_user = User::where('email',$providerUser->getEmail())->first();

        // if($check_user != null){
        //     return response()->json(['success'=>'false','data'=>'The email has already been taken']);
        // }


            $userGetReal = new User;
            $userGetReal->name=$providerUser->getName();
            $userGetReal->email=$providerUser->getEmail();

            if($request->has('fcm_token')){
            $fcm_token = $request->fcm_token;
            $userGetReal->fcm_token = $fcm_token;
            }

            $userGetReal->save(); //save in users table

            $userGetReal->attachRole(5); // customer default

            $provider_user = new Provider ;
            $provider_user->provider_id= $providerUser->getId();
            $provider_user->provider= $provider;
            $provider_user->user_id= $userGetReal->id;
            $provider_user->save(); //save in providers table
            // $token = $userGetReal->createToken(env('APP_NAME'))->accessToken;
    }    // create a token for the user, so they can login

    else{
        // dd('else');
        // login
        $userGetReal = User::find($user->user_id);

        if($request->has('fcm_token')){
            $fcm_token = $request->fcm_token;
            $userGetReal->update(['fcm_token'=> $fcm_token]);
            }

        // $token = $user->createToken(env('APP_NAME'))->accessToken;
    }

    //  $token = $user->createToken(env('APP_NAME'))->accessToken;    // return the token for usage
    return response()->json([
        'success' => true,
        'data' => $userGetReal
    ]);
}

}
