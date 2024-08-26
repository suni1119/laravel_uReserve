<div style="text-align: center">
    <button wire:click="increment">+</button> <!--wire:click="メゾット名"で実行-->
    <h1>{{ $count }}</h1> <!--Counterクラス内プロパティを表示-->
    <div class="mb-8"></div>

    こんにちは、{{ $name }}さん<br>
    <input type="text" wire:model="name">
    <!-- <input type="text" wire:model.debounce.2000ms="name"> -->
    <!-- <input type="text" wire:model.lazy="name"> -->
    <!-- <input type="text" wire:model.defer="name"><br> -->
    <br>
    <button wire:mouseover="mouseOver">マウスを合わせてね</button>
</div>
