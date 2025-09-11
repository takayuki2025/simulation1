<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

use App\Http\Responses\VerifyEmailResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;



class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Fortifyのメール認証完了後のリダイレクトをカスタマイズ
        $this->app->singleton(
            VerifyEmailResponseContract::class,
            VerifyEmailResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(50)->by($email . $request->ip());
        });

        // ログイン後のリダイレクトを /onetime に集約
        Fortify::redirects('login', function () {
            return route('onetime.show');
        });

        // メール認証完了後のリダイレクトを /onetime に集約
        Fortify::redirects('verification', function () {
            return route('onetime.show');
        });

        // プロフィール更新後のリダイレクトを /onetime に集約
        Fortify::redirects('user-profile-information', function () {
            return route('onetime.show');
        });

        // パスワードリセット後のリダイレクトを /onetime に集約
        Fortify::redirects('password-reset', function () {
            return route('onetime.show');
        });

        // ビューのカスタマイズ
        Fortify::verifyEmailView(function () {
            return view('email_check');
        });
    }
}