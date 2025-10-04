<?php
session_start();
require_once '../includes/config.php';
$pageTitle = "Mon panier";
include '../includes/layout.php';
// Initialisation du panier si besoin
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Supprimer un produit du panier
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['panier'][$id]);
}

// Vider le panier
if (isset($_GET['vider'])) {
    $_SESSION['panier'] = [];
}

// Ajouter un produit au panier
if (isset($_POST['ajouter_panier'])) {
    $id = intval($_POST['id']);
    $quantite = intval($_POST['quantite']);
    if (!isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id] = $quantite;
    } else {
        $_SESSION['panier'][$id] += $quantite;
    }
    header('Location: panier.php');
    exit;
}

// Récupérer les produits du panier
$produits = [];
$total = 0;
if (!empty($_SESSION['panier'])) {
    $ids = implode(',', array_keys($_SESSION['panier']));
    $stmt = $pdo->query("SELECT * FROM produits WHERE id IN ($ids)");
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($produits as $p) {
        $total += $p['prix'] * $_SESSION['panier'][$p['id']];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff0f6 0%, #f783ac 100%);
            min-height: 100vh;
        }
        .container-panier {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 8px 32px #e83e8c33;
            padding: 2.5rem 2rem;
        }
        .btn-pink {
            background: linear-gradient(135deg, #f783ac 0%, #e83e8c 100%);
            color: #fff;
            border: none;
            border-radius: 2rem;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px #e83e8c22;
            transition: 0.2s;
        }
        .btn-pink:hover {
            background: linear-gradient(135deg, #e83e8c 0%, #f783ac 100%);
            color: #fff;
            transform: translateY(-2px) scale(1.04);
        }
        .btn-outline-danger {
            border-radius: 2rem;
        }
        .text-pink { color: #e83e8c !important; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .fadeInUp {
            animation: fadeInUp 0.7s;
        }
        .img-produit { object-fit: cover; border-radius: 12px; border:2px solid #f783ac; box-shadow:0 2px 8px #e83e8c22;}
        .table thead { background: #f783ac; color: #fff; }
        .table td, .table th { vertical-align: middle; }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-cart i {
            font-size: 4rem;
            color: #f783ac;
            margin-bottom: 20px;
        }
        .empty-cart .btn-pink {
            margin-top: 20px;
        }
        @media (max-width: 600px) {
            .container-panier {
                padding: 1rem 0.5rem;
            }
            .table-responsive {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
<div class="container-panier fadeInUp">
    <h2 class="text-center text-pink mb-4"><i class="bi bi-cart"></i> Mon panier</h2>

    <?php if (empty($produits)): ?>
        <div class="empty-cart">
            <i class="bi bi-cart-x"></i>
            <h4 class="text-pink mb-3">Votre panier est vide !</h4>
            <p class="mb-4">Ajoutez des délices à votre panier pour les retrouver ici.</p>
            <a href="produit.php" class="btn btn-pink px-4 py-2">
                <i class="bi bi-cupcake"></i> Découvrir les produits
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive fadeInUp">
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($produits as $p): ?>
                    <tr>
                        <td>
                            <?php $img = !empty($p['image']) ? $p['image'] : 'default.png'; ?>
                            <img src="../uploads/<?= htmlspecialchars($img) ?>" width="60" height="60" class="img-produit">
                        </td>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= number_format($p['prix'], 2, ',', ' ') ?> fcfa</td>
                        <td><?= $_SESSION['panier'][$p['id']] ?></td>
                        <td><?= number_format($p['prix'] * $_SESSION['panier'][$p['id']], 2, ',', ' ') ?> fcfa</td>
                        <td>
                            <a href="?remove=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Retirer ce produit du panier ?')">
                                <i class="bi bi-trash"></i> Retirer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th colspan="2" class="text-pink"><?= number_format($total, 2, ',', ' ') ?> fcfa</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between mt-3 gap-2">
            <a href="?vider=1" class="btn btn-outline-danger rounded-pill" onclick="return confirm('Vider le panier ?')">
                <i class="bi bi-x-circle"></i> Vider le panier
            </a>
            <a href="paiement.php" class="btn btn-pink rounded-pill">
                <i class="bi bi-credit-card"></i> Passer au paiement
            </a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>