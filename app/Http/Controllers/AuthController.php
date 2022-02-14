<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('Auth/Login');
    }

    public function checkLogin(Request $request)
    {
        $user = User::where('email', $request->login)->first();

        if ($user) {
            $status = 'exist';
        } else {
            $status = 'not exist';
        }
        return response()->json([
            'status' => $status
        ]);
    }

    public function signIn(Request $request)
    {
        $this->validate($request, [
            'login' => 'required',
            'password' => 'required'
        ]);
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => trim($request->login), 'password' => trim($request->password), 'is_active' => 1])) {
                return response()->json([
                    'status' => 'signed'
                ]);
            } else {
                return response()->json([
                    'message' => 'Неверный пароль!'
                ], 403);
            }
        } else {
            if (Auth::attempt(['phone' => trim($request->login), 'password' => trim($request->password), 'is_active' => true])) {
                return response()->json([
                    'status' => 'signed'
                ]);
            } else {
                return response()->json([
//                    'message' => 'Неверный email (номер телефона) или пароль'
                    'message' => 'Неверный пароль!'
                ], 403);
            }
        }
        return response()->json([
            'message' => 'Введите корректные данные'
        ], 403);
    }

    public function register()
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required|min:7',
        ]);

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL) && strlen($request->phone) !== 9) {
            return response()->json([
                'message' => 'Неправильный формат email'
            ], 403);
        }

        if (!is_numeric($request->phone) && !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => 'Неправильный формат номера телефона'
            ], 403);
        }

        $user = User::where('phone', $request->phone)->where('email', $request->email)->count();

        if ($user > 0) {
            return response()->json([
                'message' => 'Пользователь с такими данными уже существует!'
            ], 403);
        }


        $confirm = random_int(1000, 9999);

        if ($request->country === 'tj') {
            $data = [
                'PhoneNumber' => '' . $request->phone . '',
                'text' => 'Код для авторизации: ' . $confirm . '',
                'SenderAddress' => 'Admin',
            ];


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sms2.aliftech.net/api/v1/Sms',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    'X-Api-Key: 0bab5e22-417f-41d5-898c-5a6555b709e0',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        } else {


            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($request->email)->send(new ConfirmMail($confirm));
            }

        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password
        ]);

        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'confirm' => $confirm,
            'created_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => 200,
            'conf' => $confirm,
//            'sms_response' => $response,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function profile()
    {
    }

    public function resetPassword()
    {
        return Inertia::render('Auth/ResetPassword');
    }

    public function confirmPassword($type, Request $request)
    {
        if ($type === 'resent') {
            $user = User::where('phone', $request->phone)->where('email', $request->email)->first();
            $confirmData = DB::table('password_resets')->where('user_id', $user->id)->where('quantity', '>=', 5)->where('created_at', '>', Carbon::parse()->now()->subMinutes(60)->format('Y-m-d H:i:s'))->first();
            if ($confirmData) {
                return response()->json([
                    'message' => 'Слишком много попыток, повторите позже!'
                ], 403);
            }
            $confirm = random_int(1000, 9999);

            $data = [
                'PhoneNumber' => '' . $request->phone . '',
                'text' => 'Код для авторизации: ' . $confirm . '',
                'SenderAddress' => 'Admin',
            ];


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sms2.aliftech.net/api/v1/Sms',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    'X-Api-Key: 0bab5e22-417f-41d5-898c-5a6555b709e0',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);


//            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
//                Mail::to($request->email)->send(new ConfirmMail($confirm));
//            }
            $conf = DB::table('password_resets')->where('user_id', $user->id)->update([
                'confirm' => $confirm,
                'quantity' => DB::raw('quantity + 1'),
                'created_at' => Carbon::now()
            ]);
            return response()->json([
                'status' => 200,
                'conf' => $confirm
            ]);
        } else if ($type === 'confirm') {
            $user = User::where('phone', $request->phone)->where('email', $request->email)->first();
            $conf = DB::table('password_resets')->where('user_id', $user->id)->where('confirm', $request->confirmCode)->count();

            if ($conf > 0) {
                $confirm = random_int(1000, 9999);
                $conf = DB::table('password_resets')->where('user_id', $user->id)->delete();
                $user->update([
                    'is_active' => true
                ]);
                return response()->json([
                    'message' => 'Вы успешно зарегистрировались!'
                ]);
            } else {
                return response()->json([
                    'message' => 'Неверный код!'
                ], 403);
            }
        } else if ($type === 'reset-password') {
            $is_email = filter_var($request->login, FILTER_VALIDATE_EMAIL);
            if (!$is_email || !((strlen($request->phone) !== 9 && !is_numeric($request->phone)))) {
                return response()->json([
                    'message' => 'Неправильный формат email'
                ], 403);
            }

            if (!is_numeric($request->phone) && !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'message' => 'Неправильный формат номера телефона'
                ], 403);
            }

            $user = $is_email ? User::where('email', $request->email)->count() : User::where('phone', $request->phone)->first();

            if ($user) {
                return response()->json([
                    'message' => 'Пользователь с такими данными не существует!'
                ], 403);
            }
            $confirm = random_int(1000, 9999);

            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($request->email)->send(new ConfirmMail($confirm));
            }

            DB::table('password_resets')->insert([
                'user_id' => $user->id,
                'confirm' => $confirm,
                'created_at' => Carbon::now()
            ]);

            return response()->json([
                'status' => 200,
                'conf' => $confirm
            ]);
        }
        return response()->json([
            'message' => 'Неверный код!'
        ], 403);
    }
}

