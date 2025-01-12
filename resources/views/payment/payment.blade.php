<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支払い画面
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>イベント名: {{ $reservation->event->name }}</h3>
                    <p>合計金額: {{ number_format($reservation->total_price) }} 円</p>

                    <form method="GET" action="{{ route('stripe.checkout', ['id' => $reservation->id]) }}">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            支払いをする
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
