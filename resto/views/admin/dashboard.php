<?php require_once '../public/inc/header.php'; ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">📊 Tableau de Bord - Restaurant</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <h4>Erreur</h4>
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Commandes</h5>
                            <h2><?php echo number_format($stats['total_commandes']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Chiffre d'Affaires</h5>
                            <h2><?php echo number_format($stats['chiffre_affaires_total'], 0, ',', ' '); ?> FCFA</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-dollar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">En Attente</h5>
                            <h2><?php echo number_format($stats['chiffre_affaires_en_attente'], 0, ',', ' '); ?> FCFA</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Clients</h5>
                            <h2><?php echo $clients_count; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Orders by Status -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Commandes par Statut</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['commandes_par_statut'])): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Statut</th>
                                    <th>Nombre</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['commandes_par_statut'] as $statut): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            $badge_class = 'secondary';
                                            switch ($statut['statut']) {
                                                case 'reçue': $badge_class = 'info'; break;
                                                case 'en préparation': $badge_class = 'warning'; break;
                                                case 'en route': $badge_class = 'primary'; break;
                                                case 'livrée': $badge_class = 'success'; break;
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $badge_class; ?>"><?php echo htmlspecialchars($statut['statut']); ?></span>
                                        </td>
                                        <td><?php echo $statut['count']; ?></td>
                                        <td><?php echo number_format($statut['total'], 0, ',', ' '); ?> FCFA</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Aucune donnée de statut disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Top Dishes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Plats Populaires</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['plats_populaires'])): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Plat</th>
                                    <th>Vendu</th>
                                    <th>CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['plats_populaires'] as $plat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($plat['nom']); ?></td>
                                        <td><?php echo $plat['total_vendu']; ?></td>
                                        <td><?php echo number_format($plat['chiffre_affaires'], 0, ',', ' '); ?> FCFA</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Aucun plat vendu pour le moment</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Commandes Récentes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($commandes_recentes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($commandes_recentes as $commande): ?>
                                        <tr>
                                            <td><strong>#<?php echo $commande['id_commande']; ?></strong></td>
                                            <td><?php echo htmlspecialchars($commande['client_nom'] ?? 'N/A'); ?></td>
                                            <td><?php echo number_format($commande['total'], 0, ',', ' '); ?> FCFA</td>
                                            <td>
                                                <?php 
                                                $badge_class = 'secondary';
                                                switch ($commande['statut']) {
                                                    case 'reçue': $badge_class = 'info'; break;
                                                    case 'en préparation': $badge_class = 'warning'; break;
                                                    case 'en route': $badge_class = 'primary'; break;
                                                    case 'livrée': $badge_class = 'success'; break;
                                                }
                                                ?>
                                                <span class="badge bg-<?php echo $badge_class; ?>"><?php echo htmlspecialchars($commande['statut']); ?></span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($commande['created_at'])); ?></td>
                                            <td>
                                                <a href="<?php echo RACINE; ?>commandes/show/<?php echo $commande['id_commande']; ?>" class="btn btn-sm btn-outline-primary">
                                                    Voir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucune commande récente</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <a href="<?php echo RACINE; ?>menu" class="btn btn-outline-primary btn-lg">
                                <i class="fa fa-utensils"></i><br>
                                Voir le Menu
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo RACINE; ?>commandes" class="btn btn-outline-success btn-lg">
                                <i class="fa fa-list"></i><br>
                                Toutes les Commandes
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo RACINE; ?>clients/login" class="btn btn-outline-info btn-lg">
                                <i class="fa fa-user"></i><br>
                                Connexion Client
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo RACINE; ?>menu/panier" class="btn btn-outline-warning btn-lg">
                                <i class="fa fa-shopping-cart"></i><br>
                                Panier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.badge {
    font-size: 0.8em;
}

.table-sm td, .table-sm th {
    padding: 0.3rem;
}

.btn-outline-primary.btn-lg,
.btn-outline-success.btn-lg,
.btn-outline-info.btn-lg,
.btn-outline-warning.btn-lg {
    display: block;
    margin-bottom: 10px;
    text-decoration: none;
}

.btn-outline-primary.btn-lg i,
.btn-outline-success.btn-lg i,
.btn-outline-info.btn-lg i,
.btn-outline-warning.btn-lg i {
    font-size: 1.5rem;
    margin-bottom: 5px;
}
</style>

<?php require_once '../public/inc/footer.php'; ?>