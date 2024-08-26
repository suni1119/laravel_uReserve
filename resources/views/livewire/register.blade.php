<div>
    <form wire:submit.prevent="register"> <!--preventを付けていると再読み込みを防げる-->
        <label for="name">名前</label> <!--labelはforとidで紐づく-->
        <input id="name" type="text" wire:model="name"><br>
        @error('name')<div class="text-red-400">{{ $message }}</div>@enderror
        
        <label for="email">メールアドレス</label> 
        <input id="email" type="email" wire:model="email"><br>
        @error('email')<div class="text-red-400">{{ $message }}</div>@enderror

        <label for="password">パスワード</label> 
        <input id="password" type="password" wire:model="password"><br>
        @error('password')<div class="text-red-400">{{ $message }}</div>@enderror

        <button>登録する</button>
    </form>
</div>
