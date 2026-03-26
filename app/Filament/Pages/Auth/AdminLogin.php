<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class AdminLogin extends Login
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => $exception->minutesUntilAvailable,
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => $exception->minutesUntilAvailable,
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        $remember = (bool) ($data['remember'] ?? false);
        $password = (string) ($data['password'] ?? '');
        $phoneCandidates = $this->getPhoneCandidates((string) ($data['phone'] ?? ''));

        $authenticated = false;

        foreach ($phoneCandidates as $phone) {
            if (Filament::auth()->attempt([
                'phone' => $phone,
                'password' => $password,
            ], $remember)) {
                $authenticated = true;
                break;
            }
        }

        if (! $authenticated) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('So dien thoai')
            ->tel()
            ->required()
            ->autocomplete('tel')
            ->autofocus()
            ->extraInputAttributes([
                'inputmode' => 'tel',
                'tabindex' => 1,
            ]);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.phone' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    /**
     * @return array<int, string>
     */
    protected function getPhoneCandidates(string $phone): array
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return [];
        }

        $candidates = [$digits];

        if (str_starts_with($digits, '0') && strlen($digits) >= 10) {
            $candidates[] = '84' . substr($digits, 1);
        }

        if (str_starts_with($digits, '84') && strlen($digits) >= 11) {
            $candidates[] = '0' . substr($digits, 2);
        }

        return array_values(array_unique(array_filter($candidates)));
    }
}
