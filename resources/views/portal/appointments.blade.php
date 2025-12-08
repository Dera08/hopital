<x-portal-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Rendez-vous') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Mes Rendez-vous</h3>
                        <a href="{{ route('portal.book-appointment') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Prendre un rendez-vous
                        </a>
                    </div>

                    @if($appointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($appointments as $appointment)
                                <div class="border rounded-lg p-4 {{ $appointment->status === 'completed' ? 'bg-green-50 border-green-200' : ($appointment->status === 'cancelled' ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200') }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <div>
                                                    <h4 class="font-semibold text-lg">{{ $appointment->appointment_datetime->format('d/m/Y H:i') }}</h4>
                                                    <p class="text-gray-600">{{ $appointment->doctor->name }} {{ $appointment->doctor->first_name ?? '' }}</p>
                                                    <p class="text-sm text-gray-500">{{ $appointment->service->name }}</p>
                                                </div>
                                            </div>

                                            @if($appointment->reason)
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-700"><strong>Motif:</strong> {{ $appointment->reason }}</p>
                                                </div>
                                            @endif

                                            @if($appointment->notes)
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-700"><strong>Notes:</strong> {{ $appointment->notes }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($appointment->status === 'scheduled') bg-yellow-100 text-yellow-800
                                                @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>

                                            @if($appointment->status === 'scheduled' && $appointment->appointment_datetime > now())
                                                <div class="mt-2">
                                                    <form method="POST" action="{{ route('portal.cancel-appointment', $appointment) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                                                            Annuler
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous</h3>
                            <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore de rendez-vous programmés.</p>
                            <div class="mt-6">
                                <a href="{{ route('portal.book-appointment') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Prendre un rendez-vous
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-portal-layout>
