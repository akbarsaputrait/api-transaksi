<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use App\User;

    class AuthController extends Controller
    {
        public function __construct()
        {
            //
        }

        public function register(Request $request)
        {
            $this->validate($request, [
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'name' => 'required',
                'password' => 'required'
            ], [
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah digunakan',
                'email.required' => 'Email harus diisi',
                'email.unique' => 'Email sudah digunakan',
                'email.email' => 'Format email salah',
                'name' => 'Nama harus diisi',
                'password' => 'Password harus diisi'
            ]);

            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = app('hash')->make($request->password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendaftar! Silahkam masuk'
            ]);
        }

        public function login(Request $request)
        {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email salah',
                'password' => 'Password harus diisi'
            ]);

            if (User::where('email', '=', $request->email)->exists()) {
                $user = User::where('email', '=', $request->email)->first();
                if (Hash::check($request->password, $user->password)) {
                    $token = sha1($user->email . time());

                    $user->token = $token;
                    $user->save();

                    return response()->json([
                        'data' => $user,
                        'status' => 'success',
                        'message' => 'Berhasil masuk!'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Password anda salah!'
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Email tidak terdaftar'
                ], 400);
            }
        }

        public function logout(Request $request)
        {
            $token = $request->header('token');
            $user = User::where('token', '=', $token)->first();
            $user->token = '';
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil keluar'
            ]);
        }
    }
