<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;


class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect('register')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'user',
            'password' => Hash::make($request->password),
        ]);

        return redirect('login')->with('status', 'success');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect('dashboard')->with('success', 'User ' . $user->email . ' deleted');
    }

    public function changeRole($id)
    {
        $user = User::find($id);
        $user->role = $user->role == 'user' ? 'admin' : 'user';
        $user->save();

        return redirect('dashboard')->with('success', 'Role of User: ' . $user->email . ' has been changed to: ' . $user->role);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect('dashboard')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('dashboard')->with('success', 'Password changed');
    }
}
