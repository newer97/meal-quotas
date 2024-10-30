<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Login extends Component
{

    public $username = '';
    public $password = '';

    public function render()
    {
        return view('livewire.login');
    }

    public function login()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            $this->addError('email', 'These credentials do not match our records.');
            return;
        }

        Log::info('User logged in: ' . Auth::user()->username);

        return redirect('/');
    }
}
