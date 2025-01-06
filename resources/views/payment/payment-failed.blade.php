<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支払い失敗
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                <h3 class="text-red-600 font-semibold text-xl">支払いに失敗しました</h3>
                <p>もう一度お試しください。</p>
                <a href="{{ route('payment.form', ['reservation' => $reservation->id]) }}" class="text-blue-500 underline mt-4">支払い画面に戻る</a>
            </div>
        </div>
    </div>
</x-app-layout>