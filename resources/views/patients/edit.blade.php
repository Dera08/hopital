<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Patient - {{ $patient->first_name }} {{ $patient->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/patients">
                <i class="fas fa-hospital"></i> Gestion Patients
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Modifier Patient: {{ $patient->first_name }} {{ $patient->name }}</h2>
                    <a href="/patients/{{ $patient->id }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('patients.update', $patient) }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Informations Personnelles</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $patient->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="dob" class="form-label">Date de Naissance</label>
                                <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                       id="dob" name="dob"
                                       value="{{ old('dob', $patient->dob ? $patient->dob->format('Y-m-d') : '') }}">
                                @error('dob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Sexe</label>
                                <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender" name="gender">
                                    <option value="">Sélectionner...</option>
                                    <option value="Homme" {{ old('gender', $patient->gender) == 'Homme' ? 'selected' : '' }}>Masculin</option>
                                    <option value="Femme" {{ old('gender', $patient->gender) == 'Femme' ? 'selected' : '' }}>Féminin</option>
                                    <option value="Other" {{ old('gender', $patient->gender) == 'Other' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hospital_id" class="form-label">Hôpital</label>
                                <select class="form-select @error('hospital_id') is-invalid @enderror"
                                        id="hospital_id" name="hospital_id">
                                    <option value="">Sélectionner...</option>
                                    @foreach($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}" {{ old('hospital_id', $patient->hospital_id) == $hospital->id ? 'selected' : '' }}>
                                            {{ $hospital->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hospital_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="blood_group" class="form-label">Groupe Sanguin</label>
                                <select class="form-select @error('blood_group') is-invalid @enderror" 
                                        id="blood_group" name="blood_group">
                                    <option value="">Sélectionner...</option>
                                    <option value="A+" {{ old('blood_group', $patient->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('blood_group', $patient->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('blood_group', $patient->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('blood_group', $patient->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('blood_group', $patient->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('blood_group', $patient->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('blood_group', $patient->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('blood_group', $patient->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                </select>
                                @error('blood_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Coordonnées</h5>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $patient->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $patient->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address', $patient->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $patient->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Code Postal</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $patient->postal_code) }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h5 class="border-bottom pb-2 mb-3 mt-4">Contact d'Urgence</h5>

                            <div class="mb-3">
                                <label for="emergency_contact_name" class="form-label">Nom du Contact d'Urgence</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       id="emergency_contact_name" name="emergency_contact_name" 
                                       value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="emergency_contact_phone" class="form-label">Téléphone du Contact d'Urgence</label>
                                <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       id="emergency_contact_phone" name="emergency_contact_phone" 
                                       value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3">Informations Médicales</h5>

                            <div class="mb-3">
                                <label for="allergies" class="form-label">Allergies</label>
                                <textarea class="form-control @error('allergies') is-invalid @enderror" 
                                          id="allergies" name="allergies" rows="3">{{ old('allergies', $patient->allergies) }}</textarea>
                                @error('allergies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="medical_history" class="form-label">Antécédents Médicaux</label>
                                <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                                          id="medical_history" name="medical_history" rows="3">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                @error('medical_history')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à Jour
                            </button>
                            <a href="/patients/{{ $patient->id }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>