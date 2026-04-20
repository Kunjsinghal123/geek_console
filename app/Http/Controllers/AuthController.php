<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\OtpVerification;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showSignIn()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.sign-in');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $key = 'otp_send_' . $request->email;

        if (cache()->has($key)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'OTP_RATE_LIMITED',
                    'message' => 'Please wait before requesting another OTP'
                ]
            ], 429);
        }

        $store = Store::where('store_email', $request->email)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'STORE_NOT_FOUND',
                    'message' => 'Store not found with this email'
                ]
            ], 404);
        }

        $otp = random_int(100000, 999999);

        OtpVerification::updateOrCreate(
            ['store_email' => $request->email],
            [
                'otp' => Hash::make($otp),
                'expires_at' => Carbon::now()->addMinutes(5)
            ]
        );

        cache()->put($key, true, now()->addSeconds(30));

        Mail::to($request->email)->send(new SendOtpMail($otp, $store->store_name));

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'OTP sent successfully'
            ]
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $record = OtpVerification::where('store_email', $request->email)->first();

        // Invalid OTP
        if (!$record || !Hash::check($request->otp, $record->otp)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_OTP',
                    'message' => 'Invalid OTP'
                ]
            ], 400);
        }

        // Expired OTP
        if ($record->isExpired()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'OTP_EXPIRED',
                    'message' => 'OTP expired'
                ]
            ], 400);
        }

        // Clear OTP
        $record->update([
            'otp' => null,
            'expires_at' => null
        ]);

        // Get store
        $store = Store::where('store_email', $request->email)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'STORE_NOT_FOUND',
                    'message' => 'Store not found with this email'
                ]
            ], 404);
        }

        // Login store
        Auth::login($store);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'OTP verified successfully',
                'redirect' => route('dashboard')
            ]
        ], 200);
    }

    public function signOut(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
