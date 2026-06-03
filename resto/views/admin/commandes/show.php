<?php require_once '../../public/inc/header.php'; ?>

<style>
    .admin-detail-header {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }
    
    .admin-detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect x="10" y="10" width="5" height="5" fill="white" opacity="0.1"/><rect x="50" y="30" width="3" height="3" fill="white" opacity="0.1"/><rect x="80" y="70" width="4" height="4" fill="white" opacity="0.1"/></svg>');
        animation: float 8s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }
    
    .detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        background: white;
    }
    
    .detail-card .card-header {
        background: linear-gradient(90deg, #3498db, #2980b9);
        color: white;
        padding: 1.5rem;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .detail-card .card-body {
        padding: 2rem;
    }
    
    .order-status-management {
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #dee2e6;
    }
    
    .status-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .status-timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    
    .timeline-step {
        position: relative;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .timeline-step:last-child {
        margin-bottom: 0;
    }
    
    .timeline-step.completed::before {
        content: '';
        position: absolute;
        left: -1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #28a745;
    }
    
    .timeline-step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dee2e6;
        color: white;
        position: relative;
        z-index: 1;
        border: 3px solid white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .timeline-step.completed .timeline-step-icon {
        background: #28a745;
    }
    
    .timeline-step.current .timeline-step-icon {
        background: #3498db;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
        100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
    }
    
    .timeline-step-content h6 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .timeline-step-content small {
        color: #7f8c8d;
        font-size: 0.8rem;
    }
    
    .status-controls {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-status-update {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    
    .btn-status-received { background: linear-gradient(45deg, #17a2b8, #6c757d); color: white; }
    .btn-status-preparing { background: linear-gradient(45deg, #ffc107, #fd7e14); color: white; }
    .btn-status-ready { background: linear-gradient(45deg, #28a745, #20c997); color: white; }
    .btn-status-delivered { background: linear-gradient(45deg, #007bff, #6610f2); color: white; }
    .btn-status-cancelled { background: linear-gradient(45deg, #dc3545, #e83e8c); color: white; }
    
    .btn-status-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .order-items-table {
        margin: 0;
    }
    
    .order-items-table th {
        background: #f8f9fa;
        border: none;
        padding: 1.2rem 1rem;
        font-weight: 600;
        color: #2c3e50;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .order-items-table td {
        border: none;
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f2f6;
    }
    
    .order-items-table tr:hover {
        background: #f8f9fa;
    }
    
    .food-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .food-image {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        border: 2px solid #e9ecef;
    }
    
    .food-info h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
        font-size: 1.1rem;
    }
    
    .food-info small {
        color: #6c757d;
        display: block;
        margin-top: 0.25rem;
    }
    
    .price-info {
        font-weight: 600;
        color: #28a745;
        font-size: 1.2rem;
    }
    
    .summary-card {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }
    
    .summary-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .summary-item:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.3rem;
        color: #2c3e50;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #3498db;
    }
    
    .customer-details {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
    }
    
    .info-section {
        margin-bottom: 2rem;
    }
    
    .info-section:last-child {
        margin-bottom: 0;
    }
    
    .info-section h6 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1rem;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 1rem;
        padding: 0.8rem;
        background: rgba(52, 152, 219, 0.05);
        border-radius: 8px;
        border-left: 3px solid #3498db;
    }
    
    .info-item i {
        width: 20px;
        color: #3498db;
        font-size: 1.1rem;
    }
    
    .info-item span {
        color: #495057;
        font-weight: 500;
    }
    
    .admin-actions {
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid #dee2e6;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
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
        background: linear-gradient(45deg, #3498db, #2980b9);
        color: white;
    }
    
    .btn-secondary-custom {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
    }
    
    .btn-warning-custom {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
        color: white;
    }
    
    .btn-danger-custom {
        background: linear-gradient(45deg, #dc3545, #c0392b);
        color: white;
    }
    
    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    
    .payment-method {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .payment-livraison { background: #e3f2fd; color: #1976d2; }
    .payment-carte { background: #f3e5f5; color: #7b1fa2; }
    .payment-mobile { background: #e8f5e8; color: #388e3c; }
    
    @media (max-width: 768px) {
        .admin-detail-header {
            text-align: center;
        }
        
        .detail-card .card-header {
            flex-direction: column;
            text-align: center;
        }
        
        .action-buttons {
            justify-content: center;
        }
        
        .status-controls {
            justify-content: center;
        }
        
        .timeline-step {
            flex-direction: column;
            text-align: center;
        }
        
        .food-item {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="admin-detail-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mb-2">Détail de Commande</h1>
                    <p class="mb-0 opacity-75">Commande #<?php echo htmlspecialchars($commande['code_commande'] ?? $commande['id_commande']); ?></p>
                </div>
                <div>
                    <a href="<?php echo RACINE; ?>admin/commandes" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Retour aux Commandes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Status Management -->
            <div class="order-status-management">
                <h5 class="mb-3">
                    <i class="fas fa-tasks"></i>
                    Gestion du Statut
                </h5>
                
                <div class="status-timeline">
                    <?php
                    $statuses = ['recue', 'preparation', 'prete', 'livree'];
                    $current_status = $commande['statut'];
                    $status_labels = [
                        'recue' => 'Reçue',
                        'preparation' => 'En préparation',
                        'prete' => 'Prête',
                        'livree' => 'Livrée'
                    ];
                    
                    foreach ($statuses as $index => $status) {
                        $status_position = array_search($current_status, $statuses);
                        $is_completed = $status_position !== false && $index <= $status_position;
                        $is_current = $status === $current_status;
                        
                        echo '<div class="timeline-step ' . ($is_current ? 'current' : '') . ' ' . ($is_completed ? 'completed' : '') . '">';
                        echo '<div class="timeline-step-icon">';
                        echo '<i class="fas ' . ($is_completed ? 'fa-check' : 'fa-clock') . '"></i>';
                        echo '</div>';
                        echo '<div class="timeline-step-content">';
                        echo '<h6>' . $status_labels[$status] . '</h6>';
                        if ($is_current) {
                            echo '<small class="text-primary">Statut actuel</small>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
                
                <div class="status-controls">
                    <?php if ($current_status !== 'recue'): ?>
                        <button class="btn-status-update btn-status-received" onclick="updateStatus('recue')">
                            <i class="fas fa-inbox"></i> Marquer comme Reçue
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($current_status !== 'preparation'): ?>
                        <button class="btn-status-update btn-status-preparing" onclick="updateStatus('preparation')">
                            <i class="fas fa-fire"></i> En Préparation
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($current_status !== 'prete'): ?>
                        <button class="btn-status-update btn-status-ready" onclick="updateStatus('prete')">
                            <i class="fas fa-check-circle"></i> Prête
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($current_status !== 'livree'): ?>
                        <button class="btn-status-update btn-status-delivered" onclick="updateStatus('livree')">
                            <i class="fas fa-truck"></i> Livrée
                        </button>
                    <?php endif; ?>
                    
                    <?php if (!in_array($current_status, ['livree', 'annulee'])): ?>
                        <button class="btn-status-update btn-status-cancelled" onclick="cancelOrder()">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Items -->
            <div class="detail-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-bag"></i>
                        Articles de la Commande
                    </h5>
                    <span class="badge badge-light"><?php echo count($lignes); ?> article(s)</span>
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
                                                <img src="<?php echo RACINE; ?>public/assets/images/<?php echo !empty($ligne['plat_image']) ? $ligne['plat_image'] : 'f1.png'; ?>" 
                                                     alt="<?php echo htmlspecialchars($ligne['plat_nom']); ?>" 
                                                     class="food-image">
                                                <div class="food-info">
                                                    <h6><?php echo htmlspecialchars($ligne['plat_nom']); ?></h6>
                                                    <small>Plat recommandé</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary" style="font-size: 1rem; padding: 0.5rem 1rem;"><?php echo $ligne['quantite']; ?></span>
                                        </td>
                                        <td class="text-right price-info">
                                            <?php echo number_format($ligne['prix_unitaire'], 0, ',', ' '); ?> F
                                        </td>
                                        <td class="text-right price-info">
                                            <?php echo number_format($total_ligne, 0, ',', ' '); ?> F
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                            <h5>Aucun article trouvé</h5>
                            <p class="text-muted">Cette commande ne contient aucun article.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="summary-card">
                <h5 class="summary-title">
                    <i class="fas fa-calculator"></i>
                    Résumé Financier
                </h5>
                
                <div class="summary-item">
                    <span>Sous-total:</span>
                    <span><?php echo number_format($sous_total, 0, ',', ' '); ?> F</span>
                </div>
                
                <?php if (!empty($commande['frais_livraison']) && $commande['frais_livraison'] > 0): ?>
                    <div class="summary-item">
                        <span>Frais de livraison:</span>
                        <span><?php echo number_format($commande['frais_livraison'], 0, ',', ' '); ?> F</span>
                    </div>
                <?php endif; ?>
                
                <div class="summary-item">
                    <span>Total:</span>
                    <span><?php echo number_format($commande['total'], 0, ',', ' '); ?> F</span>
                </div>
            </div>

            <!-- Customer & Delivery Details -->
            <div class="customer-details">
                <div class="info-section">
                    <h6><i class="fas fa-user"></i> Informations Client</h6>
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars($commande['client_nom'] ?? 'Client inconnu'); ?></span>
                    </div>
                    <?php if (!empty($commande['telephone'])): ?>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span><?php echo htmlspecialchars($commande['telephone']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="info-section">
                    <h6><i class="fas fa-truck"></i> Livraison</h6>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($commande['adresse_livraison']); ?></span>
                    </div>
                    <?php if (!empty($commande['instructions'])): ?>
                        <div class="info-item">
                            <i class="fas fa-sticky-note"></i>
                            <span><?php echo htmlspecialchars($commande['instructions']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="info-section">
                    <h6><i class="fas fa-credit-card"></i> Paiement</h6>
                    <div class="info-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span><?php echo ucfirst(htmlspecialchars($commande['paiement'])); ?></span>
                    </div>
                    <div class="payment-method payment-<?php echo str_replace(' ', '', $commande['paiement']); ?>">
                        <?php echo ucfirst(htmlspecialchars($commande['paiement'])); ?>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="admin-actions">
                <h5 class="mb-3 text-center">
                    <i class="fas fa-tools"></i>
                    Actions Admin
                </h5>
                <div class="action-buttons">
                    <a href="<?php echo RACINE; ?>admin/commandes/edit/<?php echo $commande['id_commande']; ?>" 
                       class="btn-custom btn-warning-custom">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    <a href="<?php echo RACINE; ?>admin/commandes" 
                       class="btn-custom btn-secondary-custom">
                        <i class="fas fa-list"></i>
                        Toutes les Commandes
                    </a>
                    <button onclick="printOrder()" class="btn-custom btn-primary-custom">
                        <i class="fas fa-print"></i>
                        Imprimer
                    </button>
                    <button onclick="confirmDelete()" class="btn-custom btn-danger-custom">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(newStatus) {
    const statusLabels = {
        'recue': 'reçue',
        'preparation': 'en préparation', 
        'prete': 'prête',
        'livree': 'livrée'
    };
    
    if (confirm(`Confirmer le changement de statut vers "${statusLabels[newStatus]}" ?`)) {
        const formData = new FormData();
        formData.append('order_id', <?php echo $commande['id_commande']; ?>);
        formData.append('new_status', newStatus);
        formData.append('notes', 'Statut mis à jour par l\'administrateur');
        
        fetch('<?php echo RACINE; ?>public/api/admin/commandes/index.php?action=update-status', {
            method: 'POST',
            body: formData
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

function cancelOrder() {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        updateStatus('annulee');
    }
}

function printOrder() {
    window.print();
}

function confirmDelete() {
    if (confirm('ATTENTION: Cette action est irréversible. Êtes-vous sûr de vouloir supprimer cette commande ?')) {
        if (confirm('Dernière confirmation: Supprimer définitivement cette commande ?')) {
            // Make AJAX call to delete
        fetch('<?php echo RACINE; ?>public/api/admin/commandes/index.php?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'order_id=<?php echo $commande['id_commande']; ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 1) {
                window.location.href = '<?php echo RACINE; ?>admin/commandes';
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
}
</script>

<?php require_once '../../public/inc/footer.php'; ?>