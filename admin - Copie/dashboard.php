<?php
session_start();
require_once('../includes/layout.php');
require_once('config.php');

// Récupération des statistiques
$stmt = $pdo->prepare("SELECT COUNT(*) FROM commandes WHERE DATE(date_commande) = CURDATE()");
$stmt->execute();
$commandes_aujourdhui = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM commandes WHERE YEARWEEK(date_commande, 1) = YEARWEEK(CURDATE(), 1)");
$stmt->execute();
$commandes_semaine = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(total) FROM commandes WHERE YEARWEEK(date_commande, 1) = YEARWEEK(CURDATE(), 1)");
$stmt->execute();
$revenus_semaine = $stmt->fetchColumn();
$revenus_semaine = $revenus_semaine ?: 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM produits WHERE stock > 0");
$stmt->execute();
$produits_disponibles = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'client'");
$stmt->execute();
$clients_inscrits = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT p.nom, SUM(lc.quantite) AS total_vendus
    FROM ligne_commandes lc
    JOIN produits p ON lc.produit_id = p.id
    GROUP BY p.id
    ORDER BY total_vendus DESC
    LIMIT 5
");
$stmt->execute();
$produits_populaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM commandes WHERE statut = 'en attente' ORDER BY date_commande DESC LIMIT 5");
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* Palette personnalisée */
.bg-pink {
    background: linear-gradient(135deg, #f783ac 0%, #e83e8c 100%);
    color: #fff !important;
}
.text-pink {
    color: #e83e8c !important;
}
.card-anim {
    animation: fadeInUp 1s;
    transition: transform 0.2s, box-shadow 0.2s;
}
.card-anim:hover {
    transform: translateY(-6px) scale(1.03);
    box-shadow: 0 8px 32px #e83e8c33;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px);}
    to { opacity: 1; transform: translateY(0);}
}
.badge-pink {
    background: #e83e8c;
    color: #fff;
    font-size: 1rem;
    padding: 0.5em 1em;
    border-radius: 50px;
}
.table thead th {
    background: #f783ac;
    color: #fff;
    border: none;
}
.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: #fff0f6;
}
.list-group-item {
    border-left: 4px solid #e83e8c;
    background: #fff;
    transition: background 0.2s;
}
.list-group-item:hover {
    background: #fceabb;
}
@media (max-width: 900px) {
    .card-anim { margin-bottom: 1.5rem; }
}
</style>

<div class="container py-4">
    <h1 class="mb-4 fw-bold text-pink" style="letter-spacing:1px; animation: fadeInUp 1s;">Tableau de bord</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-anim bg-pink shadow">
                <div class="card-body text-center">
                    <div class="mb-2"><i class="bi bi-bag-check-fill" style="font-size:2.2rem;"></i></div>
                    <h5 class="card-title">Commandes aujourd'hui</h5>
                    <span class="display-6 badge-pink"><?= $commandes_aujourdhui ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-anim bg-white shadow border-pink">
                <div class="card-body text-center">
                    <div class="mb-2"><i class="bi bi-calendar-week" style="font-size:2.2rem; color:#e83e8c"></i></div>
                    <h5 class="card-title text-pink">Commandes cette semaine</h5>
                    <span class="display-6 badge-pink"><?= $commandes_semaine ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-anim bg-white shadow border-pink">
                <div class="card-body text-center">
                    <div class="mb-2"><i class="bi bi-currency-euro" style="font-size:2.2rem; color:#e83e8c"></i></div>
                    <h5 class="card-title text-pink">Revenus cette semaine</h5>
                    <span class="display-6 badge-pink"><?= number_format($revenus_semaine, 2, ',', ' ') ?> fcfa</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card card-anim bg-white shadow border-pink">
                <div class="card-body text-center">
                    <div class="mb-2"><i class="bi bi-box-seam" style="font-size:2.2rem; color:#e83e8c"></i></div>
                    <h5 class="card-title text-pink">Produits disponibles</h5>
                    <span class="display-6 badge-pink"><?= $produits_disponibles ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-anim bg-white shadow border-pink">
                <div class="card-body text-center">
                    <div class="mb-2"><i class="bi bi-people-fill" style="font-size:2.2rem; color:#e83e8c"></i></div>
                    <h5 class="card-title text-pink">Clients inscrits</h5>
                    <span class="display-6 badge-pink"><?= $clients_inscrits ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-anim bg-white shadow border-pink">
                <div class="card-body text-center">
                    <div class="mb-2"><i class="bi bi-person-badge-fill" style="font-size:2.2rem; color:#e83e8c"></i></div>
                    <h5 class="card-title text-pink">Profil admin</h5>
                    <span class="display-6 badge-pink"><?= htmlspecialchars($_SESSION['username']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-5 mb-3 text-pink fw-bold" style="animation: fadeInUp 1s;">Produits populaires</h2>
    <table class="table table-striped shadow">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité vendue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits_populaires as $produit): ?>
                <tr>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><?= $produit['total_vendus'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 class="mt-5 mb-3 text-pink fw-bold" style="animation: fadeInUp 1s;">Notifications récentes</h2>
    <ul class="list-group shadow">
        <?php foreach ($notifications as $notif): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-bell-fill text-pink me-2"></i>
                    Commande #<?= $notif['id'] ?> en attente depuis le <?= date('d/m/Y', strtotime($notif['date_commande'])) ?>
                </span>
                <span class="badge bg-pink"><?= htmlspecialchars($notif['statut']) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php 
require_once('../includes/footer.php'); ?>
