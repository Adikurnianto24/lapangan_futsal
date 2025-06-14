<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('admin_id')) {
            return redirect('/dashboard');
        }
        return response()->view('login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('nama_pengguna', $request->username)->first();
        if ($admin && (Hash::check($request->password, $admin->katasandi) || $request->password === $admin->katasandi)) { // Hash::check lebih aman, sisakan fallback sementara
            session(['admin_id' => $admin->id_admin]);
            return redirect('/dashboard')->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
        }
        return back()->withErrors(['login' => 'Nama pengguna atau kata sandi salah']);
    }

    public function logout()
    {
        session()->forget('admin_id');
        return redirect('/login');
    }
} 