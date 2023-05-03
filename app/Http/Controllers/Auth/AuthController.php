<?php

namespace App\Http\Controllers\Auth;

use App\class\Message;
use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register function
    public function register(Request $request)
    {
        // Validade the data
        $validator = Validator::make($request->all(), [
            'name'=>['required','max:30'],
            'email'=>['required','unique:users','email','max:60'],
            'password'=>['required','min:6']
        ]);

        // Verificar el error
        if( $validator->fails() ){
            $message = new Message(false, 'Error de validación de datos', $validator->errors(), 422, null, null);
            return $message->message();
        }

        // Save the data on the DB
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password)
        ]);

        // TODO: Falta generar token
        $token = JWTAuth::fromUser($user);

        // Generating token and true access
        $message = new Message(true, 'Bienvenido(a) al sistema', false, 200, $user, $this->responseWithToken($token));
        return $message->message();
    }

    // Login function
    public function login(Request $request)
    {
        // Get credentials
        $credentials = $request->only('email','password');

        // Validade the data
        $validator = Validator::make($credentials,[
            'email'=>['required','email'],
            'password'=>['required']
        ]);

        // verify the validators
        if($validator->fails()){
            $message = new Message(false, 'Error de validación de datos', $validator->errors(), 422, null, null);
            return $message->message();
        }

        // ? Generating token and verify credentials
        try {

            if (!$token = JWTAuth::attempt($credentials)) {
                $message = new Message(false, 'Credenciales incorrectas', true, 401, null, null);
                return $message->message();
            }
            // Generate token and autentication
            Auth::attempt($credentials);
            // Get user
            $user = User::where('email', $credentials['email'])->get()->first();

        } catch (JWTException $e) {

            $message = new Message(false, 'No se pudo generar el token del usuario', true, 500, null, null);
            return $message->message();
        }

        // Generating token and true access
        $message = new Message(true, "Bienvenido(a) al sistema $user->name", false, 200, $user, $this->responseWithToken($token));
        return $message->message();
    }

    // Get me
    public function me()
    {
        $message = new Message(true, 'me', false, 200, Auth::user(), null);
        return $message->message();
    }

    // Logout function
    public function logout()
    {
        Auth::logout();
        $message = new Message(true, 'Sesión cerrada correctamente', false, 200, null, null);
        return $message->message();
    }

    // Response with token
    public function responseWithToken($token)
    {
        return Response()->json([
            'access_token'=>$token,
            'token_type'  =>'bearer',
            'expires_in'  =>Auth::factory()->getTTL() * 60
        ]);
    }

    // refreshToken
    public function refreshToken()
    {
        $token = Auth::refresh();
        return response()->json($this->responseWithToken($token));
    }

}
