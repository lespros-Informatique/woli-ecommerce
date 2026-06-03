<?php require_once '../../public/inc/header.php'; ?>

<style>
    .admin-header {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }
    
    .admin-header::before {
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
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #3498db, #2c3e50);
    }
    
    .stat-card.total::before { background: linear-gradient(to bottom, #3498db, #2980b9); }
    .stat-card.pending::before { background: linear-gradient(to bottom, #f39c12, #e67e22); }
    .stat-card.completed::before { background: linear-gradient(to bottom, #27ae60, #229954); }
    .stat-card.revenue::before { background: linear-gradient(to bottom, #e74c3c, #c0392b); }
    
    .stat-card h3 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .stat-card p {
        margin: 0.5rem 0 0 0;
        color: #7f8c8d;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .stat-card .icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 3rem;
        color: #ecf0f1;
        opacity: 0.5;
    }
    
    .orders-table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .orders-table-header {
        background: linear-gradient(90deg, #3498db, #2980b9);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .orders-table-header h5 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
    }
    
    .filter-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-controls select,
    .filter-controls input {
        border: none;
        border-radius: 25px;
        padding: 0.5rem 1rem;
        background: rgba(255,255,255,0.2);
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .filter-controls select option {
        background: #2c3e50;
        color: white;
    }
    
    .filter-controls input::placeholder {
        color: rgba(255,255,255,0.7);
    }
    
    .orders-table {
        margin: 0;
        border: none;
    }
    
    .orders-table th {
        background: #f8f9fa;
        border: none;
        padding: 1.2rem 1rem;
        font-weight: 600;
        color: #2c3e50;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .orders-table td {
        border: none;
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f2f6;
    }
    
    .orders-table tr:hover {
        background: #f8f9fa;
    }
    
    .order-id {
        font-weight: 600;
        color: #3498db;
        font-size: 1.1rem;
    }
    
    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }
    
    .status-recue { background: linear-gradient(45deg, #17a2b8, #6c757d); color: white; }
    .status-preparation { background: linear-gradient(45deg, #ffc107, #fd7e14); color: white; }
    .status-prete { background: linear-gradient(45deg, #28a745, #20c997); color: white; }
    .status-livree { background: linear-gradient(45deg, #007bff, #6610f2); color: white; }
    .status-annulee { background: linear-gradient(45deg, #dc3545, #e83e8c); color: white; }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3498db, #2980b9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .amount {
        font-weight: 600;
        color: #27ae60;
        font-size: 1.1rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-sm-custom {
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .btn-view {
        background: linear-gradient(45deg, #3498db, #2980b9);
        color: white;
    }
    
    .btn-edit {
        background: linear-gradient(45deg, #f39c12, #e67e22);
        color: white;
    }
    
    .btn-status {
        background: linear-gradient(45deg, #27ae60, #229954);
        color: white;
    }
    
    .btn-sm-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    
    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .search-container input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        font-size: 1rem;
        outline: none;
        transition: all 0.3s ease;
    }
    
    .search-container input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    
    .search-container i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
    }
    
    .pagination-container {
        display: flex;
        justify-content: center;
        padding: 2rem;
        background: #f8f9fa;
    }
    
    @media (max-width: 768px) {
        .admin-header {
            text-align: center;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .orders-table-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-controls {
            justify-content: center;
        }
        
        .orders-table {
            font-size: 0.9rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mb-2">Gestion des Commandes</h1>
                    <p class="mb-0 opacity-75">Administrez toutes les commandes du restaurant</p>
                </div>
                <div>
                    <a href="<?= RACINE; ?>admin/dashboard" class="btn btn-light">
                        <i class="fa fa-tachometer-alt"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <h3><?= $stats['total_commandes']; ?></h3>
            <p>Total Commandes</p>
        </div>
        <div class="stat-card pending">
            <div class="icon">
                <i class="fa fa-clock"></i>
            </div>
            <h3><?= ($stats['commandes_par_statut']['recue'] ?? 0) + ($stats['commandes_par_statut']['preparation'] ?? 0); ?></h3>
            <p>En Attente</p>
        </div>
        <div class="stat-card completed">
            <div class="icon">
                <i class="fa fa-check-circle"></i>
            </div>
            <h3><?= ($stats['commandes_par_statut']['prete'] ?? 0) + ($stats['commandes_par_statut']['livree'] ?? 0); ?></h3>
            <p>Terminées</p>
        </div>
        <div class="stat-card revenue">
            <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
            </div>
            <h3><?= number_format($stats['chiffre_affaires'], 0, ',', ' '); ?></h3>
            <p>Chiffre d'Affaires (F CFA)</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-container">
        <i class="fa fa-search"></i>
        <input type="text" id="searchOrders" placeholder="Rechercher par numéro de commande, nom de client, ou adresse...">
    </div>

    <div class="orders-table-container">
        <!-- Table Header -->
        <div class="orders-table-header">
            <h5><i class="fa fa-list"></i> Liste des Commandes</h5>
            <div class="filter-controls">
                <select id="statusFilter" onchange="filterOrders()">
                    <option value="all">Tous les statuts</option>
                    <option value="recue">Reçues</option>
                    <option value="preparation">En préparation</option>
                    <option value="prete">Prêtes</option>
                    <option value="livree">Livrées</option>
                    <option value="annulee">Annulées</option>
                </select>
                <select id="paymentFilter" onchange="filterOrders()">
                    <option value="all">Tous les paiements</option>
                    <option value="à la livraison">À la livraison</option>
                    <option value="carte bancaire">Carte bancaire</option>
                    <option value="mobile money">Mobile Money</option>
                </select>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-responsive">
            <table class="table orders-table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php if (!empty($commandes)): ?>
                        <?php foreach ($commandes as $commande): ?>
                            <tr data-status="<?= htmlspecialchars($commande['statut']); ?>" 
                                data-payment="<?= htmlspecialchars($commande['paiement']); ?>"
                                data-search="<?= strtolower(htmlspecialchars($commande['code_commande'] . ' ' . ($commande['client_nom'] ?? '') . ' ' . $commande['adresse_livraison'])); ?>">
                                <td>
                                    <div class="order-id">
                                        #<?= htmlspecialchars($commande['code_commande']); ?>
                                    </div>
                                    <small class="text-muted">ID: <?= $commande['id_commande']; ?></small>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar">
                                            <?= strtoupper(substr($commande['client_nom'] ?? 'C', 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold"><?= htmlspecialchars($commande['client_nom'] ?? 'Client inconnu'); ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($commande['adresse_livraison']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= htmlspecialchars($commande['statut']); ?>">
                                        <?= ucfirst(htmlspecialchars($commande['statut'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-light">
                                        <?= ucfirst(htmlspecialchars($commande['paiement'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <div><?= date('d/m/Y', strtotime($commande['created_at'])); ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($commande['created_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="amount"><?= number_format($commande['total'], 0, ',', ' '); ?> F</div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= RACINE; ?>commandes/show/<?= $commande['id_commande']; ?>" 
                                           class="btn-sm-custom btn-view" title="Voir détails">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button onclick="updateOrderStatus(<?= $commande['id_commande']; ?>)" 
                                                class="btn-sm-custom btn-status" title="Mettre à jour le statut">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="<?= RACINE; ?>admin/commandes/edit/<?= $commande['id_commande']; ?>" 
                                           class="btn-sm-custom btn-edit" title="Modifier">
                                            <i class="fa fa-cog"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>Aucune commande trouvée</h5>
                                <p class="text-muted">Il n'y a aucune commande à afficher.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Précédent</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mettre à jour le statut</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="statusUpdateForm">
                    <input type="hidden" id="orderId" name="order_id">
                    <div class="form-group">
                        <label for="newStatus">Nouveau statut:</label>
                        <select class="form-control" id="newStatus" name="new_status" required>
                            <option value="recue">Reçue</option>
                            <option value="preparation">En préparation</option>
                            <option value="prete">Prête</option>
                            <option value="livree">Livrée</option>
                            <option value="annulee">Annulée</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statusNotes">Notes (optionnel):</label>
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3" placeholder="Ajoutez des notes sur ce changement de statut..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitStatusUpdate()">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<script>
function filterOrders() {
    const statusFilter = document.getElementById('statusFilter').value;
    const paymentFilter = document.getElementById('paymentFilter').value;
    const searchTerm = document.getElementById('searchOrders').value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTableBody tr');
    
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        const rowPayment = row.getAttribute('data-payment');
        const rowSearch = row.getAttribute('data-search');
        
        const statusMatch = statusFilter === 'all' || rowStatus === statusFilter;
        const paymentMatch = paymentFilter === 'all' || rowPayment === paymentFilter;
        const searchMatch = !searchTerm || rowSearch.includes(searchTerm);
        
        if (statusMatch && paymentMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function updateOrderStatus(orderId) {
    document.getElementById('orderId').value = orderId;
    $('#statusModal').modal('show');
}

function submitStatusUpdate() {
    const formData = new FormData(document.getElementById('statusUpdateForm'));
    
    fetch('<?= RACINE; ?>public/api/admin/commandes/index.php?action=update-status', {
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

// Real-time search
document.getElementById('searchOrders').addEventListener('input', filterOrders);
</script>

<?php require_once '../../public/inc/footer.php'; ?>