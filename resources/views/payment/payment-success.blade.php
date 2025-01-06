<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支払い成功
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                <h3 class="text-green-600 font-semibold text-xl">支払いが完了しました！</h3>
                <p>予約が完了しました！</p>
                <a href="{{ route('mypage.index') }}" class="text-blue-500 underline mt-4">マイページへ戻る</a>
            </div>
        </div>
    </div>
</x-app-layout>
