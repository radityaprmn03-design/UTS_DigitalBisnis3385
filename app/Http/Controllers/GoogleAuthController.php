<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        session()->put('url.intended', url()->previous());
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Create a new user with organizer role
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(str()->random(16)), // Random password
                    'role' => 'organizer',
                ]);
            }
            
            Auth::login($user);
            
            $intended = session()->pull('url.intended');
            
            // Jika intended URL kosong atau mengarah ke halaman login, alihkan ke dashboard / tiket
            if (!$intended || str_contains($intended, '/login')) {
                $redirectUrl = in_array($user->role, ['superadmin', 'admin', 'organizer']) 
                    ? route('admin.dashboard') 
                    : route('ticket');
            } else {
                $redirectUrl = $intended;
            }

            return redirect($redirectUrl)->with('success', 'Berhasil login melalui Google!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login melalui Google: ' . $e->getMessage());
        }
    }
}
