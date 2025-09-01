<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\JsonResponse;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * 新規登録後に一度だけアクセス可能なURLにリダイレクトします。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): \Symfony\Component\HttpFoundation\Response
    {
        // JSONリクエストの場合は、認証済みであることを示すJSONレスポンスを返す
        if ($request->wantsJson()) {
            return new JsonResponse(['registered' => true], 200);
        }
        
        // 通常のリクエストの場合は、特定のルートにリダイレクト
        return redirect()->route('profile_edit');
    }
}