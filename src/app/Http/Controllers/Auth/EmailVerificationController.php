<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\Events\Verified;


// 修正後: Laravel標準のフォームリクエストをインポート
use Illuminate\Foundation\Auth\EmailVerificationRequest;
// use Illuminate\Auth\Events\Verified; // Verifiedイベントのインポートも忘れずに
use App\Providers\RouteServiceProvider;
// use App\Http\Requests\EmailVerificationRequest;

class EmailVerificationController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function onetime(Request $request): RedirectResponse
    // {
    //     dd($request);
    //     // ユーザーのメールがすでに確認済みかチェック
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return redirect()->intended(config('fortify.home'));
    //     }

    //     // メールを検証済みとしてマーク
    //     if ($request->user()->markEmailAsVerified()) {
    //         event(new Verified($request->user()));
    //     }

    //     return redirect()->intended(config('fortify.home'));
    // }



    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * メール認証リクエストを処理します。
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
public function verify(EmailVerificationRequest $request)
{
    if ($request->user()->hasVerifiedEmail()) {
        // ここを `route('profile_edit')` に変更します。
        // これにより、Laravelがルート名を元に正しいURLを生成します。
        return redirect()->intended(route('profile_edit'));
    }

    if ($request->user()->markEmailAsVerified()) {
        event(new Verified($request->user()));
    }

    // ここも同様に `route('profile_edit')` に変更します。
    return redirect()->intended(route('profile_edit'))->with('verified', true);
}

    /**
     * メール認証通知を再送信します。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

}