<?php require_once '../../public/inc/header.php'; ?>

<style>
    .admin-edit-header {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 15px;
        text-align: center;
    }
    
    .edit-form-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .edit-form-card .card-header {
        background: linear-gradient(90deg, #3498db, #2980b9);
        color: white;
        padding: 1.5rem;
        border: none;
        text-align: center;
    }
    
    .edit-form-card .card-body {
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .btn-custom {
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(45deg, #3498db, #2980b9);
        color: white;
    }
    
    .btn-secondary-custom {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
    }
    
    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
</style>

<div class="container">
    <!-- Header Section -->
    <div class="admin-edit-header">
        <div class="container">
            <h1 class="mb-2">Modifier la Commande</h1>
            <p class="mb-0 opacity-75">Commande #<?php echo htmlspecialchars($commande['code_commande'] ?? $commande['id_commande']); ?></p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="edit-form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i>
                        Informations de la Commande
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h6>Erreurs de validation:</h6>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total">Total (FCFA) *</label>
                                    <input type="number" step="0.01" class="form-control" id="total" name="total" 
                                           value="<?php echo htmlspecialchars($commande['total']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frais_livraison">Frais de livraison (FCFA)</label>
                                    <input type="number" step="0.01" class="form-control" id="frais_livraison" name="frais_livraison" 
                                           value="<?php echo htmlspecialchars($commande['frais_livraison']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="statut">Statut *</label>
                                    <select class="form-control" id="statut" name="statut" required>
                                        <option value="recue" <?php echo $commande['statut'] === 'recue' ? 'selected' : ''; ?>>Reçue</option>
                                        <option value="preparation" <?php echo $commande['statut'] === 'preparation' ? 'selected' : ''; ?>>En préparation</option>
                                        <option value="prete" <?php echo $commande['statut'] === 'prete' ? 'selected' : ''; ?>>Prête</option>
                                        <option value="livree" <?php echo $commande['statut'] === 'livree' ? 'selected' : ''; ?>>Livrée</option>
                                        <option value="annulee" <?php echo $commande['statut'] === 'annulee' ? 'selected' : ''; ?>>Annulée</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paiement">Mode de paiement *</label>
                                    <select class="form-control" id="paiement" name="paiement" required>
                                        <option value="à la livraison" <?php echo $commande['paiement'] === 'à la livraison' ? 'selected' : ''; ?>>À la livraison</option>
                                        <option value="carte bancaire" <?php echo $commande['paiement'] === 'carte bancaire' ? 'selected' : ''; ?>>Carte bancaire</option>
                                        <option value="mobile money" <?php echo $commande['paiement'] === 'mobile money' ? 'selected' : ''; ?>>Mobile Money</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="adresse_livraison">Adresse de livraison *</label>
                            <textarea class="form-control" id="adresse_livraison" name="adresse_livraison" rows="3" required><?php echo htmlspecialchars($commande['adresse_livraison']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="instructions">Instructions spéciales</label>
                            <textarea class="form-control" id="instructions" name="instructions" rows="3"><?php echo htmlspecialchars($commande['instructions']); ?></textarea>
                        </div>

                        <div class="form-actions">
                            <a href="<?php echo RACINE; ?>admin/commandes/show/<?php echo $commande['id_commande']; ?>" 
                               class="btn-custom btn-secondary-custom">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn-custom btn-primary-custom">
                                <i class="fas fa-save"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-calculate total when amounts change
document.getElementById('frais_livraison').addEventListener('input', function() {
    updateTotal();
});

document.getElementById('total').addEventListener('input', function() {
    updateTotal();
});

function updateTotal() {
    const fraisLivraison = parseFloat(document.getElementById('frais_livraison').value) || 0;
    const sousTotal = parseFloat(document.getElementById('total').value) || 0;
    
    // You could add more sophisticated calculation here
    // For example, if you have line items that need to be recalculated
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const total = parseFloat(document.getElementById('total').value);
    
    if (total <= 0) {
        e.preventDefault();
        alert('Le total doit être supérieur à 0');
        return false;
    }
    
    const adresse = document.getElementById('adresse_livraison').value.trim();
    if (adresse.length < 10) {
        e.preventDefault();
        alert('L\'adresse de livraison doit contenir au moins 10 caractères');
        return false;
    }
});
</script>

<?php require_once '../../public/inc/footer.php'; ?>