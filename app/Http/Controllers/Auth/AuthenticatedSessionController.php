<?php

namespace App\Http\Controllers\Auth;

use App\Events\StoreLoginHistory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\Auth\ForgotPasswordMail;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $redirectUrl = auth()->user()->hasRole('Customer') ? AppServiceProvider::CUSTOMER : AppServiceProvider::HOME;

        event(new StoreLoginHistory(auth()->user()));

        return redirect()->intended($redirectUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgetPassword()
    {
        return Inertia::render('Auth/ForgetPassword');
    }

    public function storeForgetPassword(ForgotPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::query()->where('email', $request->email)->first();

            $confirmation_code = Str::random(30);

            DB::table('password_reset_tokens')->where('email', '=', $request->email)->delete();
            DB::table('password_reset_tokens')->insert(['email' => $user->email, 'token' => $confirmation_code, 'created_at' => date('Y-m-d h:i:s')]);

            Mail::to($user->email)->send(new \App\Mail\ForgotPasswordMail(user: $user, code: $confirmation_code));
            DB::commit();

            return redirect()->back()->with('success', 'Password reset link sent to your email.');
        } catch (\Throwable $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to send password reset link.');
        }
    }

    public function resetPassword(string $code)
    {
        $data['token'] = DB::table('password_reset_tokens')->where('token', '=', $code)->first();
        return Inertia::render('Auth/ResetPassword', $data);
    }

    public function storeResetPassword(ResetPasswordRequest $request, string $code)
    {
        DB::beginTransaction();
        try {
            $token = DB::table('password_reset_tokens')->where('token', '=', $code)->first();
            User::query()->where('email', '=', $token->email)
                ->update([
                    'password' => Hash::make($request->password),
                ]);

            DB::table('password_reset_tokens')->where('token', '=', $code)->delete();

            DB::commit();

            return redirect()->route('login');

        } catch (\Throwable $exception) {
            DB::rollBack();
            dd($exception);
            return redirect()->back();
        }
    }
}
