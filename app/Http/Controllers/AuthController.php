<?php

namespace App\Http\Controllers;

use App\Contracts\Sms;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Cache;
use App\Models\User;

class AuthController extends Controller
{
    protected Sms $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    public function login()
    {
        $credentials['username'] = preg_replace('/[^0-9]/', '', request('username'));
        $credentials['password'] = request('password');

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Nevernie dannie'], 400);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = Auth::user();
        return response($user);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function send_code(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'phone_number' => 'required' 
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $phone_number = preg_replace('/[^0-9]/', '', $data['phone_number']);

        $is_exist = User::where('username', $phone_number)->exists();
        if($is_exist) {
            return response([
                'message' => 'Пользователь с таким номером регистрирован'
            ], 400);
        }

        $code_storage_time = 60;

        if(Cache::has($phone_number) && Cache::has($phone_number.'time')) {
            return response([
                'message' => 'Можете спросить код через '.($code_storage_time - (time() - Cache::get($phone_number.'time'))).' секунд',
                'second' => ($code_storage_time - (time() - Cache::get($phone_number.'time')))
            ], 400);
        }

        $code = mt_rand(100000, 999999);

        // otpravlenniy kod
        Cache::put($phone_number, $code, $code_storage_time);
        // vremya otpravki sms
        Cache::put($phone_number.'time', time(), $code_storage_time);
        // popitki
        Cache::put($phone_number.'count', 4, $code_storage_time);

        // $sms_text = 'okc.uz'.PHP_EOL.'Tasdiqlash kodi: '.$code;
        $sms_text = 'Kod podtverzhdeniya dlya registratsii na sayte okc.uz: '.$code.' / okc.uz saytida ro\'yxatdan o\'tish uchun tasdiqlash kodi: '.$code;

        $response = $this->sms->sendMessage($phone_number, $sms_text);

        if($response) {
            return response(['message' => 'Sms otpravlen', 'second' => 60], 200);
        } else {
            return response(['message' => 'Oshibka pri otpravke sms'], 400);
        }

    }

    public function check_code(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'phone_number' => 'required',
            'code' => 'required|max:6|min:6'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $phone_number = preg_replace('/[^0-9]/', '', $data['phone_number']);

        $is_exist = User::where('username', $phone_number)->exists();
        if($is_exist) {
            return response(['message' => 'Пользователь с таким номером регистрирован'], 400);
        }

        if(!Cache::has($phone_number)) {
            return response(['message' => 'Oshibka! Zaprosite noviy kod'], 400);
        }

        if(Cache::get($phone_number.'count') > 0) {
            if($data['code'] == Cache::get($phone_number)) {
                // udalim srok xraneniya koda podtverjdeniya
                Cache::pull($phone_number);
                Cache::put($phone_number, $data['code']);
                // daem 5 shansov
                Cache::pull($phone_number.'count');
                Cache::put($phone_number.'count', 4);
                return response(['message' => 'Код верный'], 200);
            } else {
                Cache::decrement($phone_number.'count');
                if(Cache::get($phone_number.'count') == 0) {
                    return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
                }
                return response(['message' => 'Kod neverniy! U vas est eshe '.(Cache::get($phone_number.'count')).' shansov', 'count' => (Cache::get($phone_number.'count'))], 400);
            }
        } else {
            return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
        }
    }

    public function register(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'phone_number' => 'required',
            'code' => 'required',
            'name' => 'required|max:255',
            'password' => 'required|confirmed|min:8',
            // 'email' => 'required|email|max:255',
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $phone_number = preg_replace('/[^0-9]/', '', $data['phone_number']);

        $is_exist = User::where('username', $phone_number)->exists();
        if($is_exist) {
            return response(['message' => 'Пользователь с таким номером регистрирован'], 400);
        }

        if(!Cache::has($phone_number)) {
            return response(['message' => 'Oshibka']);
        }

        if(Cache::get($phone_number.'count') > 0) {
            if($data['code'] == Cache::get($phone_number)) {
                $data['password'] = Hash::make($data['password']);

                $user = User::create([
                    'username' => $phone_number,
                    'phone_number' => $phone_number,
                    'password' => $data['password'],
                    'name' => $data['name'],
                    'sex' => $data['sex'] ?? 'female'
                ]);

                $token = auth()->attempt([
                    'username' => $phone_number,
                    'password' => $request->password
                ]);

                Cache::pull($phone_number.'count');
                Cache::pull($phone_number);

                return $this->respondWithToken($token);
            } else {
                Cache::decrement($phone_number.'count');
                if(Cache::get($phone_number.'count') == 0) {
                    return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
                }
                return response(['message' => 'Kod neverniy! U vas est eshe '.(Cache::get($phone_number.'count')).' shansov', 'count' => (Cache::get($phone_number.'count'))], 400);
            }
        } else {
            return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
        }

    }

    public function forgot(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'phone_number' => 'required'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $phone_number = preg_replace('/[^0-9]/', '', $data['phone_number']);

        $is_exist = User::where('username', $phone_number)->exists();
        if(!$is_exist) {
            return response(['message' => 'Пользователь не регистрирован'], 400);
        }

        $code_storage_time = 60;

        if(Cache::has($phone_number) && Cache::has($phone_number.'time')) {
            return response(['message' => 'Mojete sprosit kod cherez '.($code_storage_time - (time() - Cache::get($phone_number.'time'))).' sekund', 'second' => ($code_storage_time - (time() - Cache::get($phone_number.'time')))], 200);
        }

        $code = mt_rand(100000, 999999);

        // otpravlenniy kod
        Cache::put($phone_number, $code, $code_storage_time);
        // vremya otpravki sms
        Cache::put($phone_number.'time', time(), $code_storage_time);
        // popitki
        Cache::put($phone_number.'count', 4, $code_storage_time);

        $sms_text = 'Kod podtverzhdeniya dlya registratsii na sayte okc.uz: '.$code.' / okc.uz saytida ro\'yxatdan o\'tish uchun tasdiqlash kodi: '.$code;

        $response = $this->sms->sendMessage($phone_number, $sms_text);

        if($response) {
            return response(['message' => 'Sms otpravlen', 'second' => 60], 200);
        } else {
            return response(['message' => 'Oshibka pri otpravke sms'], 400);
        }
    }

    public function forgot_check_code(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'phone_number' => 'required',
            'code' => 'required|max:6|min:6'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $phone_number = preg_replace('/[^0-9]/', '', $data['phone_number']);

        $is_exist = User::where('username', $phone_number)->exists();
        if(!$is_exist) {
            return response(['message' => 'Пользователь не регистрирован'], 400);
        }

        if(!Cache::has($phone_number)) {
            return response(['message' => 'Oshibka! Zaprosite noviy kod'], 400);
        }

        if(Cache::get($phone_number.'count') > 0) {
            if($data['code'] == Cache::get($phone_number)) {
                // udalim srok xraneniya koda podtverjdeniya
                Cache::pull($phone_number);
                Cache::put($phone_number, $data['code']);
                // daem 5 shansov
                Cache::pull($phone_number.'count');
                Cache::put($phone_number.'count', 4);
                return response(['message' => 'Код верный'], 200);
            } else {
                Cache::decrement($phone_number.'count');
                if(Cache::get($phone_number.'count') == 0) {
                    return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
                }
                return response(['message' => 'Kod neverniy! U vas est eshe '.(Cache::get($phone_number.'count')).' shansov', 'count' => (Cache::get($phone_number.'count'))], 400);
            }
        } else {
            return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
        }
    }

    public function forgot_update(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'phone_number' => 'required',
            'code' => 'required',
            'password' => 'required|confirmed|min:8'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $phone_number = preg_replace('/[^0-9]/', '', $data['phone_number']);

        $is_exist = User::where('username', $phone_number)->exists();
        if(!$is_exist) {
            return response([
                'message' => 'Пользователь с таким номером регистрирован'
            ], 400);
        }

        if(!Cache::has($phone_number)) {
            return response(['message' => 'Oshibka']);
        }

        if(Cache::get($phone_number.'count') > 0) {
            if($data['code'] == Cache::get($phone_number)) {
                $data['password'] = Hash::make($data['password']);

                $user = User::where('username', $phone_number)->first();
                $user->update([
                    'username' => $phone_number,
                    'password' => $data['password']
                ]);

                $token = auth()->attempt([
                    'username' => $phone_number,
                    'password' => $request->password
                ]);

                Cache::pull($phone_number.'count');
                Cache::pull($phone_number);

                return $this->respondWithToken($token);
            } else {
                Cache::decrement($phone_number.'count');
                if(Cache::get($phone_number.'count') == 0) {
                    return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
                }
                return response(['message' => 'Kod neverniy! U vas est eshe '.(Cache::get($phone_number.'count')).' shansov', 'count' => (Cache::get($phone_number.'count'))], 400);
            }
        } else {
            return response(['message' => 'Vashi popitki ne ostalis! Zaprosite kod snova'], 400);
        }

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
        ]);
    }
}
