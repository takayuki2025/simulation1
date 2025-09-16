<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Providers\RouteServiceProvider;

class EmailVerificationController extends BaseController
{

    // メール認証通知ページを表示します。
    public function notice()
    {
        return view('auth.verify-email');
    }


    // メール認証リクエストを処理します。
    public function verify(EmailVerificationRequest $request)
    {
        // ユーザーのメールがすでに確認済みかチェック
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('profile_edit'));
        }

        // メールを検証済みとしてマーク
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // 認証後のリダイレクト先を'profile_edit'ルートにし、`with('verified', true)`でメッセージを渡す
        return redirect()->intended(route('profile_edit'))->with('verified', true);
    }


    // メール認証通知を再送信します。
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}