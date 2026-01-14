<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Patient - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Créer un compte patient</h1>
            <p class="text-gray-600 mt-2">Remplissez le formulaire pour obtenir votre IPU</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

           <form method="POST" action="{{ route('patient.register') }}" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nom *</label>
            <input type="text" name="last_name" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Prénom *</label>
            <input type="text" name="first_name" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Date de naissance *</label>
            <input type="date" name="date_of_birth" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Téléphone *</label>
            <input type="text" name="phone" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Email *</label>
        <input type="email" name="email" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Hôpital *</label>
        <input type="text" name="hospital_name" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Entrez le nom de votre hôpital">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Mot de passe *</label>
            <input type="password" name="password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Confirmer le mot de passe *</label>
            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div class="flex items-center">
        <input type="checkbox" name="terms" required class="h-4 w-4 text-blue-600 border-gray-300 rounded">
        <label class="ml-2 block text-sm text-gray-600">J'accepte les conditions d'utilisation *</label>
    </div>

    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg">
        Créer mon compte
    </button>
</form>
        </div>
    </div>
</body>
</html>