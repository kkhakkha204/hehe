<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class OtpPasswordPromptController extends Controller
{
    public function create(Request $request): RedirectResponse|Response
    {
        if (! $this->canShowPrompt($request)) {
            return redirect()->to($this->resolveIntendedUrl($request));
        }

        return response()->view('auth.otp-password-prompt', [], 200, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->canShowPrompt($request)) {
            return redirect()->to($this->resolveIntendedUrl($request));
        }

        $data = $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()->mixedCase(),
            ],
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($data['password']),
        ])->save();

        $redirect = $this->finishPrompt($request);

        return $redirect->with('status', 'Đã cập nhật mật khẩu thành công.');
    }

    public function skip(Request $request): RedirectResponse
    {
        return $this->finishPrompt($request);
    }

    protected function finishPrompt(Request $request): RedirectResponse
    {
        $intendedUrl = $this->resolveIntendedUrl($request);

        $request->session()->forget([
            'otp_password_prompt_user_id',
            'otp_password_prompt_intended',
        ]);

        return redirect()->to($intendedUrl);
    }

    protected function canShowPrompt(Request $request): bool
    {
        // return true;
        $promptUserId = (int) $request->session()->get('otp_password_prompt_user_id', 0);
        $currentUserId = (int) $request->user()->id;

        return $promptUserId > 0 && $promptUserId === $currentUserId;
    }

    protected function resolveIntendedUrl(Request $request): string
    {
        return route('home');
    }
}
