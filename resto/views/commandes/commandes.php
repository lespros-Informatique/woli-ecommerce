<?php require_once '../public/inc/header.php'; ?>

<style>
    .orders-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 15px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .orders-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1.5" fill="white" opacity="0.1"/></svg>');
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .order-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .order-header {
        background: linear-gradient(90deg, #ff6b6b, #ffa500);
        color: white;
        padding: 1rem 1.5rem;
        margin: -1px -1px 1.5rem -1px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .order-number {
        font-size: 1.2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .order-status {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-recue { background: linear-gradient(45deg, #17a2b8, #6c757d); }
    .status-preparation { background: linear-gradient(45deg, #ffc107, #fd7e14); }
    .status-prete { background: linear-gradient(45deg, #28a745, #20c997); }
    .status-livree { background: linear-gradient(45deg, #007bff, #6610f2); }
    .status-annulee { background: linear-gradient(45deg, #dc3545, #e83e8c); }
    
    .order-body {
        padding: 1.5rem;
    }
    
    .order-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem;
        background: rgba(108, 117, 125, 0.1);
        border-radius: 8px;
        border-left: 3px solid #ff6b6b;
    }
    
    .info-item i {
        color: #6c757d;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
    
    .order-total {
        text-align: right;
        padding: 1rem;
        background: linear-gradient(90deg, #ff6b6b, #ffa500);
        color: white;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .order-total h4 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .order-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .btn-order {
        padding: 0.5rem 1.2rem;
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
    
    .btn-primary-order {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
    }
    
    .btn-secondary-order {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
    }
    
    .btn-success-order {
        background: linear-gradient(45deg, #28a745, #1e7e34);
        color: white;
    }
    
    .btn-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    
    .empty-orders {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        border: 2px dashed #dee2e6;
    }
    
    .empty-orders i {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    
    .empty-orders h3 {
        color: #495057;
        margin-bottom: 1rem;
    }
    
    .empty-orders p {
        color: #6c757d;
        margin-bottom: 2rem;
    }
    
    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        padding: 1.5rem;
        border-radius: 15px;
        border-left: 4px solid #ff6b6b;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .stat-card h3 {
        margin: 0;
        color: #ff6b6b;
        font-size: 2rem;
        font-weight: 700;
    }
    
    .stat-card p {
        margin: 0.5rem 0 0 0;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            text-align: center;
        }
        
        .order-info {
            grid-template-columns: 1fr;
        }
        
        .order-actions {
            justify-content: center;
        }
        
        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Client-side Pagination Styles */
    .pagination-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .pagination-footer .page-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        background: linear-gradient(45deg, #ff6b6b, #ffa500);
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .pagination-footer .page-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
    }
    
    .pagination-footer .page-btn:disabled {
        background: #dee2e6;
        color: #adb5bd;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .pagination-footer .page-btn.active {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }
    
    .pagination-footer .pagination-info {
        color: #6c757d;
        font-weight: 500;
    }
    
    .orders-container {
        min-height: 200px;
    }
</style>

<div class="container">
    <!-- Header Section -->
    <div class="orders-header">
        <div class="container">
            <h1 class="mb-2">Mes Commandes</h1>
            <p class="mb-0 opacity-75">Suivez vos commandes en temps réel</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <h3><?= count($commandes ?? []); ?></h3>
            <p>Total Commandes</p>
        </div>
        <div class="stat-card">
            <h3><?php 
                $pending = 0;
                if (!empty($commandes)) {
                    foreach ($commandes as $cmd) {
                        if (in_array($cmd['statut'], ['recue', 'preparation'])) {
                            $pending++;
                        }
                    }
                }
                echo $pending;
            ?></h3>
            <p>En Cours</p>
        </div>
        <div class="stat-card">
            <h3><?php 
                $completed = 0;
                if (!empty($commandes)) {
                    foreach ($commandes as $cmd) {
                        if (in_array($cmd['statut'], ['prete', 'livree'])) {
                            $completed++;
                        }
                    }
                }
                echo $completed;
            ?></h3>
            <p>Terminées</p>
        </div>
        <div class="stat-card">
            <h3><?php 
                $total = 0;
                if (!empty($commandes)) {
                    foreach ($commandes as $cmd) {
                        $total += $cmd['total'];
                    }
                }
                echo number_format($total, 0, ',', ' ');
            ?> F</h3>
            <p>Montant Total</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Filtrer par statut:</h5>
            </div>
            <div class="col-md-6">
                <select class="form-control" id="statusFilter" onchange="filterOrders()">
                    <option value="all">Toutes les commandes</option>
                    <option value="recue">Reçues</option>
                    <option value="preparation">En préparation</option>
                    <option value="prete">Prêtes</option>
                    <option value="livree">Livrées</option>
                    <option value="annulee">Annulées</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders List Container -->
    <div class="orders-container" id="ordersContainer">
        <div class="row" id="ordersList">
            <?php if (!empty($commandes)): ?>
                <?php foreach ($commandes as $commande): ?>
                    <div class="col-lg-6 col-md-12" data-status="<?= htmlspecialchars($commande['statut']??''); ?>" data-order>
                        <div class="order-card">
                            <!-- Order Header -->
                            <div class="order-header">
                                <div class="order-number">
                                    <i class="fa fa-receipt"></i>
                                    Commande #<?= htmlspecialchars($commande['code_commande'] ?? $commande['id_commande']??''); ?>
                                </div>
                                <div class="order-status status-<?= htmlspecialchars($commande['statut']??''); ?>">
                                    <?= ucfirst(htmlspecialchars($commande['statut']??'')); ?>
                                </div>
                            </div>

                            <!-- Order Body -->
                            <div class="order-body">
                                <!-- Order Info -->
                                <div class="order-info">
                                    <div class="info-item">
                                        <i class="fa fa-user"></i>
                                        <div>
                                            <strong>Client:</strong><br>
                                            <span><?= htmlspecialchars($commande['client_nom'] ?? 'Client inconnu'); ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa fa-calendar"></i>
                                        <div>
                                            <strong>Date:</strong><br>
                                            <span><?= date('d/m/Y H:i', strtotime($commande['created_at'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa fa-credit-card"></i>
                                        <div>
                                            <strong>Paiement:</strong><br>
                                            <span><?= ucfirst(htmlspecialchars($commande['paiement'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa fa-map-marker-alt"></i>
                                        <div>
                                            <strong>Livraison:</strong><br>
                                            <span><?= htmlspecialchars($commande['adresse_livraison']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Total -->
                                <div class="order-total">
                                    <h4>Total: <?= number_format($commande['total'], 0, ',', ' '); ?> FCFA</h4>
                                    <?php if (!empty($commande['frais_livraison']) && $commande['frais_livraison'] > 0): ?>
                                        <small>Livraison: <?= number_format($commande['frais_livraison'], 0, ',', ' '); ?> FCFA</small>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                $cryptedParams = $this->validator->crypter($commande['id_commande']);
                                ?>
                                <!-- Order Actions -->
                                <div class="order-actions">
                                    <a href="<?= RACINE; ?>commandes/details/<?= $cryptedParams; ?>" 
                                       class="btn-order btn-primary-order">
                                        <i class="fa fa-eye"></i>
                                        Voir Détails
                                    </a>
                                    <a href="<?= RACINE; ?>menu" 
                                       class="btn-order btn-secondary-order">
                                        <i class="fa fa-utensils"></i>
                                        Commander
                                    </a>
                                    <?php if (in_array($commande['statut'], ['recue', 'preparation'])): ?>
                                        <button class="btn-order btn-success-order" onclick="trackOrder(<?= $commande['id_commande']; ?>)">
                                            <i class="fa fa-truck"></i>
                                            Suivre
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-orders">
                        <i class="fa fa-shopping-cart"></i>
                        <h3>Aucune commande trouvée</h3>
                        <p>Vos commandes apparaîtront ici une fois que vous en aurez passé.</p>
                        <a href="<?= RACINE; ?>menu" class="btn-order btn-primary-order">
                            <i class="fa fa-utensils"></i>
                            Voir le Menu
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination Footer -->
        <div class="pagination-footer" id="paginationFooter" style="display: none;">
            <button class="page-btn" id="prevBtn" onclick="changePage(-1)">
                <i class="fa fa-chevron-left"></i> Précédent
            </button>
            <span class="pagination-info" id="paginationInfo">Page 1 sur 1</span>
            <button class="page-btn" id="nextBtn" onclick="changePage(1)">
                Suivant <i class="fa fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- Order Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suivi de Commande</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="trackingContent">
                <!-- Tracking content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
    // Pagination variables
    const ITEMS_PER_PAGE = 4;
    let currentPage = 1;
    let filteredOrders = [];
    let totalPages = 1;

    // Initialize pagination when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initPagination();
    });

    function initPagination() {
        const allOrders = document.querySelectorAll('[data-order]');
        filteredOrders = Array.from(allOrders);
        totalPages = Math.ceil(filteredOrders.length / ITEMS_PER_PAGE);
        
        updatePaginationDisplay();
        showPage(1);
    }

    function showPage(page) {
        const startIndex = (page - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        
        // Hide all orders first
        filteredOrders.forEach(order => {
            order.style.display = 'none';
        });
        
        // Show orders for current page
        for (let i = startIndex; i < endIndex && i < filteredOrders.length; i++) {
            if (filteredOrders[i].style.display !== 'none' || 
                document.getElementById('statusFilter').value === 'all') {
                filteredOrders[i].style.display = 'block';
            }
        }
        
        currentPage = page;
        updatePaginationDisplay();
    }

    function changePage(direction) {
        const newPage = currentPage + direction;
        if (newPage >= 1 && newPage <= totalPages) {
            showPage(newPage);
        }
    }

    function updatePaginationDisplay() {
        const paginationFooter = document.getElementById('paginationFooter');
        const paginationInfo = document.getElementById('paginationInfo');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        // Show/hide pagination based on number of items
        if (filteredOrders.length > ITEMS_PER_PAGE) {
            paginationFooter.style.display = 'flex';
            paginationInfo.textContent = `Page ${currentPage} sur ${totalPages}`;
            
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
        } else {
            paginationFooter.style.display = 'none';
        }
    }

    function filterOrders() {
        const status = document.getElementById('statusFilter').value;
        const allOrders = document.querySelectorAll('[data-order]');
        
        filteredOrders = [];
        
        allOrders.forEach(order => {
            if (status === 'all' || order.getAttribute('data-status') === status) {
                order.style.display = 'block';
                filteredOrders.push(order);
            } else {
                order.style.display = 'none';
            }
        });
        
        // Recalculate pagination
        totalPages = Math.ceil(filteredOrders.length / ITEMS_PER_PAGE);
        currentPage = 1;
        
        updatePaginationDisplay();
        
        if (filteredOrders.length > 0) {
            showPage(1);
        } else {
            // Show empty state if no orders match filter
            showNoResultsMessage();
        }
    }

    function showNoResultsMessage() {
        const ordersContainer = document.getElementById('ordersList');
        ordersContainer.innerHTML = `
            <div class="col-12">
                <div class="empty-orders">
                    <i class="fa fa-filter"></i>
                    <h3>Aucune commande trouvée</h3>
                    <p>Aucune commande ne correspond au filtre sélectionné.</p>
                    <button class="btn-order btn-primary-order" onclick="resetFilter()">
                        <i class="fa fa-refresh"></i>
                        Réinitialiser le filtre
                    </button>
                </div>
            </div>
        `;
        document.getElementById('paginationFooter').style.display = 'none';
    }

    function resetFilter() {
        document.getElementById('statusFilter').value = 'all';
        filterOrders();
    }

    function trackOrder(orderId) {
        // Simulate order tracking
        const trackingSteps = [
            { status: 'Commande reçue', completed: true, time: '14:30' },
            { status: 'En préparation', completed: true, time: '14:45' },
            { status: 'Prête', completed: false, time: null },
            { status: 'En livraison', completed: false, time: null },
            { status: 'Livrée', completed: false, time: null }
        ];
        
        let html = '<div class="tracking-timeline">';
        trackingSteps.forEach((step, index) => {
            html += `
                <div class="tracking-step ${step.completed ? 'completed' : 'pending'}">
                    <div class="tracking-step-icon">
                        <i class="fa ${step.completed ? 'fa-check' : 'fa-clock'}"></i>
                    </div>
                    <div class="tracking-step-content">
                        <h6>${step.status}</h6>
                        ${step.time ? `<small class="text-muted">${step.time}</small>` : ''}
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        document.getElementById('trackingContent').innerHTML = html;
        $('#trackingModal').modal('show');
    }

    // Add CSS for tracking timeline
    const trackingCSS = `
        .tracking-timeline {
            padding: 1rem 0;
        }
        
        .tracking-step {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .tracking-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 19px;
            top: 40px;
            width: 2px;
            height: 30px;
            background: #dee2e6;
        }
        
        .tracking-step.completed:not(:last-child)::after {
            background: #28a745;
        }
        
        .tracking-step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background: #dee2e6;
            color: white;
            position: relative;
            z-index: 1;
        }
        
        .tracking-step.completed .tracking-step-icon {
            background: #28a745;
        }
        
        .tracking-step-content h6 {
            margin: 0;
            font-weight: 600;
        }
    `;

    // Add tracking CSS to document
    const style = document.createElement('style');
    style.textContent = trackingCSS;
    document.head.appendChild(style);
</script>

<?php require_once '../public/inc/footer.php'; ?>