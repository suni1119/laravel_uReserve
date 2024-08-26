<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name;
    public $email;
    public $password;

    protected $rules = [ // 以下バリデーションの条件
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }



    public function register()
    {
        $this->validate(); // 以下バリデーションがかかる
        // dd($this); // $thisでclassの中のプロパティを取得できる
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password) // Hash::makeで暗号化

        ]);
        // dd($this); // $thisでclassの中のプロパティを取得できる
        
        session()->flash('message', '登録OKです');

        return to_route('livewire-test.index');

    }


    public function render()
    {
        return view('livewire.register');
    }
}
