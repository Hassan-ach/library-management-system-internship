@extends('admin.dashboard')


@section('content')
   <!-- resources/views/admin/settings/edit.blade.php -->
   <br>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Paramètres de la Bibliothèque
                    </h5>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Durée max d'emprunt -->
                        <div class="mb-4">
                            <label for="duree_emprunt" class="form-label fw-bold">
                                <i class="fas fa-calendar-day me-2 text-primary"></i>
                                Durée maximale d'emprunt (jours)
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg border-primary" 
                                   id="duree_emprunt" 
                                   name="DUREE_EMPRUNT_MAX"
                                   value="{{ $settings->DUREE_EMPRUNT_MAX }}" 
                                   min="1" 
                                   required>
                            <div class="form-text text-muted">
                                Durée maximum avant qu'un livre soit considéré comme en retard
                            </div>
                        </div>

                        <!-- Nombre max d'emprunts -->
                        <div class="mb-4">
                            <label for="max_emprunts" class="form-label fw-bold">
                                <i class="fas fa-book me-2 text-primary"></i>
                                Nombre maximal d'emprunts par utilisateur
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg border-primary" 
                                   id="max_emprunts" 
                                   name="NOMBRE_EMPRUNTS_MAX"
                                   value="{{ $settings->NOMBRE_EMPRUNTS_MAX }}" 
                                   min="1" 
                                   required>
                            <div class="form-text text-muted">
                                Nombre de livres qu'un utilisateur peut emprunter simultanément
                            </div>
                        </div>

                        <!-- Durée de réservation -->
                        <div class="mb-4">
                            <label for="duree_reservation" class="form-label fw-bold">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                Durée de réservation (jours)
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg border-primary" 
                                   id="duree_reservation" 
                                   name="DUREE_RESERVATION"
                                   value="{{ $settings->DUREE_RESERVATION }}" 
                                   min="1" 
                                   required>
                            <div class="form-text text-muted">
                                Après ce délai, les réservations non traitées seront automatiquement rejetées
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-undo me-1"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Dernière modification: {{ $settings->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add validation script -->
@section('scripts')
<script>
    // Form validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection
@endsection