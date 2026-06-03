<?php require_once '../public/inc/header.php'; ?>

<style>
    .order-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }

    .order-detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1.5" fill="white" opacity="0.1"/></svg>');
        animation: float 6s ease-in-out infinite;
        pointer-events: none;
        /* 👈 AJOUT MAGIQUE */
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .order-detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        background: white;
    }

    .order-detail-card .card-header {
        background: linear-gradient(90deg, #ff6b6b, #ffa500);
        color: white;
        padding: 1.5rem;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-recue {
        background: linear-gradient(45deg, #17a2b8, #6c757d);
    }

    .status-preparation {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
    }

    .status-prete {
        background: linear-gradient(45deg, #28a745, #20c997);
    }

    .status-livree {
        background: linear-gradient(45deg, #007bff, #6610f2);
    }

    .status-annulee {
        background: linear-gradient(45deg, #dc3545, #e83e8c);
    }

    .order-items-table {
        margin: 0;
    }

    .order-items-table th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .order-items-table td {
        border: none;
        padding: 1.5rem 1rem;
        vertical-align: middle;
    }

    .order-items-table tr:not(:last-child) td {
        border-bottom: 1px solid #dee2e6;
    }

    .food-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .food-image {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .food-info h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
    }

    .food-info small {
        color: #6c757d;
    }

    .price-info {
        font-weight: 600;
        color: #28a745;
        font-size: 1.1rem;
    }

    .summary-card {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .summary-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .summary-item:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.2rem;
        color: #212529;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #ff6b6b;
    }

    .customer-info {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .info-section h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.8rem;
        color: #6c757d;
    }

    .info-item i {
        width: 20px;
        color: #495057;
    }

    .btn-group-custom {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-custom {
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .btn-primary-custom {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
    }

    .btn-secondary-custom {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
    }

    .btn-success-custom {
        background: linear-gradient(45deg, #28a745, #1e7e34);
        color: white;
    }

    .btn-warning-custom {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
        color: white;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        color: white;
    }

    .payment-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .payment-en-attente {
        background: #fff3cd;
        color: #856404;
    }

    .payment-paye {
        background: #d4edda;
        color: #155724;
    }

    .payment-annule {
        background: #f8d7da;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .order-detail-card .card-header {
            flex-direction: column;
            text-align: center;
        }

        .btn-group-custom {
            justify-content: center;
        }

        .food-item {
            flex-direction: column;
            text-align: center;
        }

        .food-info {
            margin-top: 0.5rem;
        }
    }
</style>

<div class="container">
    <!-- Header Section -->
    <div class="order-detail-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mb-2">Détail de la Commande</h1>
                    <p class="mb-0 opacity-75">Commande
                        #<?= htmlspecialchars($commande['code_commande'] ?? $commande['id_commande']); ?></p>
                </div>
                <div>
                    <span class="btn btn-light" style="cursor: pointer;" onclick="return history.back()">
                        <i class="fa fa-arrow-left"></i> Retour aux Commandes
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-shopping-bag"></i>
                        Articles de la Commande
                    </h5>
                    <div class="order-status-badge status-<?= htmlspecialchars($commande['statut']); ?>">
                        <?= ucfirst(htmlspecialchars($commande['statut'])); ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($lignes)): ?>
                        <table class="table order-items-table mb-0">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-right">Prix Unitaire</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sous_total = 0; 
                                foreach ($lignes as $ligne):
                                    $total_ligne = $ligne['quantite'] * $ligne['prix_unitaire'];
                                    $sous_total += $total_ligne;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="food-item">
                                                <img src="<?= RACINE_EXTERNE; ?><?= !empty($ligne['plat_image']) ? $ligne['plat_image'] : 'f1.png'; ?>"
                                                    alt="<?= htmlspecialchars($ligne['plat_nom']); ?>" class="food-image">
                                                <div class="food-info">
                                                    <h6><?= htmlspecialchars($ligne['plat_nom']); ?></h6>
                                                    <small>Plat recommandé</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary"><?= $ligne['quantite']; ?></span>
                                        </td>
                                        <td class="text-right price-info">
                                            <?= number_format($ligne['prix_unitaire'], 0, ',', ' '); ?> F
                                        </td>
                                        <td class="text-right price-info">
                                            <?= number_format($total_ligne, 0, ',', ' '); ?> F
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fa fa-exclamation-circle fa-3x text-muted mb-3"></i>
                            <h5>Aucun article trouvé</h5>
                            <p class="text-muted">Cette commande ne contient aucun article.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer & Delivery Info -->
            <div class="customer-info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6><i class="fa fa-user"></i> Informations Client</h6>
                            <div class="info-item">
                                <i class="fa fa-user"></i>
                                <span><?= htmlspecialchars($commande['client_nom'] ?? 'Client inconnu'); ?></span>
                            </div>
                            <?php if (!empty($commande['telephone'])): ?>
                                <div class="info-item">
                                    <i class="fa fa-phone"></i>
                                    <span><?= htmlspecialchars($commande['telephone']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6><i class="fa fa-truck"></i> Livraison</h6>
                            <div class="info-item">
                                <i class="fa fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($commande['adresse_livraison']); ?></span>
                            </div>
                            <?php if (!empty($commande['instructions'])): ?>
                                <div class="info-item">
                                    <i class="fa fa-sticky-note"></i>
                                    <span><?= htmlspecialchars($commande['instructions']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($paiements)): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="info-section">
                                <h6><i class="fa fa-credit-card"></i> Paiements</h6>
                                <?php foreach ($paiements as $paiement): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="info-item mb-0">
                                            <i class="fa fa-money-bill"></i>
                                            <span><?= ucfirst(htmlspecialchars($paiement['methode'])); ?></span>
                                        </div>
                                        <div>
                                            <span
                                                class="payment-status payment-<?= str_replace(' ', '-', $paiement['statut']); ?>">
                                                <?= ucfirst(htmlspecialchars($paiement['statut'])); ?>
                                            </span>
                                            <span
                                                class="ml-2 font-weight-bold"><?= number_format($paiement['montant'], 0, ',', ' '); ?>
                                                F</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="summary-card">
                <h5 class="summary-title">
                    <i class="fa fa-calculator"></i>
                    Résumé de la Commande
                </h5>

                <div class="summary-item">
                    <span>Sous-total:</span>
                    <span><?= number_format($sous_total, 0, ',', ' '); ?> F</span>
                </div>

                <?php if (!empty($commande['promotion_id']) && $commande['montant_reduction'] > 0): ?>
                    <div class="summary-item text-success">
                        <span>
                            <i class="fa fa-tag"></i> Réduction
                            <?php if (!empty($commande['promotion_titre'])): ?>
                                (<?= htmlspecialchars($commande['promotion_titre']) ?>
                                -<?= $commande['promotion_pourcentage'] ?>%)
                            <?php endif; ?>
                        </span>
                        <span>-<?= number_format($commande['montant_reduction'], 0, ',', ' ') ?> F</span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($commande['frais_livraison']) && $commande['frais_livraison'] > 0): ?>
                    <div class="summary-item">
                        <span>Frais de livraison:</span>
                        <span><?= number_format($commande['frais_livraison'], 0, ',', ' '); ?> F</span>
                    </div>
                <?php endif; ?>

                <div class="summary-item">
                    <span>Total:</span>
                    <span><?= number_format($commande['total'], 0, ',', ' '); ?> F</span>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-cogs"></i>
                        Actions
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="btn-group-custom">
                        <a href="<?= RACINE; ?>commandes" class="btn-custom btn-secondary-custom">
                            <i class="fa fa-list"></i>
                            Toutes les Commandes
                        </a>

                        <a href="<?= RACINE; ?>menu" class="btn-custom btn-primary-custom">
                            <i class="fa fa-utensils"></i>
                            Nouveau Menu
                        </a>

                        <?php if (in_array($commande['statut'], ['recue', 'preparation'])): ?>
                            <button class="btn-custom btn-warning-custom" onclick="trackOrder()">
                                <i class="fa fa-truck"></i>
                                Suivre Livraison
                            </button>
                        <?php endif; ?>

                        <?php if ($commande['statut'] === 'prete' && $commande['paiement'] === 'à la livraison'): ?>
                            <button class="btn-custom btn-success-custom"
                                onclick="confirmDelivery(<?= $commande['id_commande']; ?>)">
                                <i class="fa fa-check"></i>
                                Confirmer Réception
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="order-detail-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-history"></i>
                        Historique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Commande passée</h6>
                                <small
                                    class="text-muted"><?= date('d/m/Y H:i', strtotime($commande['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php if (!empty($commande['updated_at']) && $commande['updated_at'] !== $commande['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Dernière mise à jour</h6>
                                    <small
                                        class="text-muted"><?= date('d/m/Y H:i', strtotime($commande['updated_at'])); ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline CSS -->
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ff6b6b;
        border: 2px solid white;
        box-shadow: 0 0 0 3px #ff6b6b;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-content h6 {
        margin: 0;
        font-weight: 600;
        color: #495057;
    }

    .timeline-content small {
        font-size: 0.8rem;
    }
</style>

<script>
    function trackOrder() {
        // Show tracking modal or page
        window.open('<?= RACINE; ?>tracking/<?= $commande['id_commande']; ?>', '_blank');
    }

    function confirmDelivery(orderId) {
        if (confirm('Confirmer la réception de cette commande ?')) {
            // Make AJAX call to confirm delivery
            fetch('<?= RACINE; ?>public/api/admin/commandes/index.php?action=confirm-delivery', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=' + orderId
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 1) {
                        location.reload();
                    } else {
                        alert('Erreur: ' + data.msg);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur de connexion');
                });
        }
    }
</script>

<?php require_once '../public/inc/footer.php'; ?>