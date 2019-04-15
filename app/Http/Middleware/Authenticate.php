<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Contracts\Auth\Factory as Auth;
    use App\User;

    class Authenticate
    {
        /**
         * The authentication guard factory instance.
         *
         * @var \Illuminate\Contracts\Auth\Factory
         */
        protected $auth;

        /**
         * Create a new middleware instance.
         *
         * @param \Illuminate\Contracts\Auth\Factory $auth
         *
         * @return void
         */
        public function __construct(Auth $auth)
        {
            $this->auth = $auth;
        }

        /**
         * Handle an incoming request.
         *
         * @param \Illuminate\Http\Request $request
         * @param \Closure                 $next
         * @param string|null              $guard
         *
         * @return mixed
         */
        public function handle($request, Closure $next, $guard = null)
        {
            if ($this->auth->guard($guard)->guest()) {
                if ($request->header('token')) {
                        $token = $request->header('token');
                        $check_token = User::where('token', $token)->first();
                        if (!$check_token) {
                            $res['status'] = 'fail';
                            $res['message'] = 'Anda harus masuk terlebih dahulu';
                            return response($res, 401);
                        } else {
                            return $next($request);
                        }
                } else {
                    $res['status'] = 'fail';
                    $res['message'] = 'Token tidak ditemukan';
                    return response($res, 401);
                }
            }
        }
    }
