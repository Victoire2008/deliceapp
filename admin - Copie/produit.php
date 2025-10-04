<?php
session_start();
require_once '../includes/config.php';
$pageTitle = "Gestion des produits";
include '../includes/layout.php';

// Gestion des messages
$message = "";
$error = "";

// Ajouter un produit
if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $imgName = null;

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        if (!in_array($_FILES['image']['type'], $allowedTypes) || $_FILES['image']['size'] > $maxSize) {
            $error = "Image invalide (formats autorisés : JPG, PNG, GIF, max 2 Mo).";
        } else {
            $imgName = uniqid() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $imgName);
        }
    } else {
        $imgName = "default.png";
    }

    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO produits (nom, prix, stock, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prix, $stock, $imgName]);
        $message = "Produit ajouté avec succès.";
    }
}

// Modifier un produit
if (isset($_POST['modifier'])) {
    $id = intval($_POST['id']);
    $nom = trim($_POST['nom']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $imgName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0 && $_FILES['image']['name'] != "") {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024;
        if (!in_array($_FILES['image']['type'], $allowedTypes) || $_FILES['image']['size'] > $maxSize) {
            $error = "Image invalide (formats autorisés : JPG, PNG, GIF, max 2 Mo).";
        } else {
            $imgName = uniqid() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $imgName);
            $stmt = $pdo->prepare("UPDATE produits SET nom=?, prix=?, stock=?, image=? WHERE id=?");
            $stmt->execute([$nom, $prix, $stock, $imgName, $id]);
            $message = "Produit modifié.";
        }
    } else {
        $stmt = $pdo->prepare("UPDATE produits SET nom=?, prix=?, stock=? WHERE id=?");
        $stmt->execute([$nom, $prix, $stock, $id]);
        $message = "Produit modifié.";
    }
}

// Supprimer un produit
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id=?");
    $stmt->execute([$id]);
    $message = "Produit supprimé.";
}

// Récupérer tous les produits
$produits = $pdo->query("SELECT * FROM produits ORDER BY date_ajout DESC")->fetchAll();

// Produit de la semaine (le plus récent)
$produitSemaine = $pdo->query("SELECT * FROM produits ORDER BY date_ajout DESC LIMIT 1")->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff0f6 0%, #f783ac 100%);
            min-height: 100vh;
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
        .text-pink { color: #e83e8c !important; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .fadeInUp {
            animation: fadeInUp 0.7s;
        }
        .form-produit {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px #e83e8c33;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 2rem;
        }
        .table thead { background: #f783ac; color: #fff; }
        .img-produit { object-fit: cover; border-radius: 12px; border:2px solid #f783ac; box-shadow:0 2px 8px #e83e8c22;}
        .produit-semaine {
            background: linear-gradient(135deg, #f783ac 0%, #fff0f6 100%);
            border-radius: 2rem;
            box-shadow: 0 8px 32px #e83e8c33;
            padding: 2rem 1.5rem;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            animation: fadeInUp 1s;
        }
        .produit-semaine-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 1.5rem;
            border: 3px solid #e83e8c;
            background: #fff;
        }
        .produit-semaine-info h4 {
            margin-bottom: 0.5rem;
        }
        .produit-semaine-info span {
            font-size: 1.1rem;
            color: #e83e8c;
        }
        .table td, .table th { vertical-align: middle; }
        .collapse:not(.show) { display: none; }
    </style>
</head>
<body>
<div class="container py-4">
    <h2 class="text-center text-pink mb-4">Gestion des produits</h2>

    <!-- Produit de la semaine -->
    <?php if ($produitSemaine): ?>
    <div class="produit-semaine mb-4">
        <img src="../uploads/<?= htmlspecialchars(isset($produitSemaine['image']) ? $produitSemaine['image'] : 'default.png') ?>" class="produit-semaine-img" alt="Produit de la semaine">
        <div class="produit-semaine-info">
            <h4 class="fw-bold text-pink mb-1"><?= htmlspecialchars($produitSemaine['nom']) ?></h4>
            <span><?= number_format($produitSemaine['prix'], 2, ',', ' ') ?> fcfa</span>
            <div class="mt-2"><span class="badge bg-pink text-white">Produit de la semaine</span></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="alert alert-success fadeInUp text-center"><?= htmlspecialchars($message) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger fadeInUp text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Bouton Ajouter -->
    <div class="text-end mb-3">
        <button class="btn btn-pink px-4 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#formAjout" aria-expanded="false" aria-controls="formAjout">
            <i class="bi bi-plus-circle"></i> Ajouter un produit
        </button>
    </div>

    <!-- Formulaire Ajout -->
    <div class="collapse mb-4 fadeInUp" id="formAjout">
        <form method="POST" enctype="multipart/form-data" class="form-produit">
            <h5 class="text-pink mb-3"><i class="bi bi-plus-circle"></i> Ajouter un produit</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="nom" class="form-control" placeholder="Nom du produit" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="prix" class="form-control" placeholder="Prix (€)" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="stock" class="form-control" placeholder="Stock" required>
                </div>
                <div class="col-md-3">
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" name="ajouter" class="btn btn-pink">Ajouter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tableau Produits -->
    <div class="table-responsive fadeInUp">
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Date</th>
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
        <td><?= $p['stock'] ?></td>
        <td><?= date('d/m/Y H:i', strtotime($p['date_ajout'])) ?></td>
        <td>
            <div class="d-flex flex-column gap-1">
                <button class="btn btn-outline-primary btn-sm rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#editForm<?= $p['id'] ?>" aria-expanded="false" aria-controls="editForm<?= $p['id'] ?>">
                    <i class="bi bi-pencil"></i> Modifier
                </button>
                <a href="?supprimer=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Supprimer ce produit ?')">
                    <i class="bi bi-trash"></i> Supprimer
                </a>
                <form method="post" action="panier.php" class="d-flex align-items-center gap-1">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <input type="number" name="quantite" value="1" min="1" max="<?= $p['stock'] ?>" class="form-control form-control-sm" style="width:70px;" required>
                    <button type="submit" name="ajouter_panier" class="btn btn-success btn-sm rounded-pill">
                        <i class="bi bi-cart-plus"></i> Ajouter au panier
                    </button>
                </form>
            </div>
        </td>
    </tr>

    <!-- Formulaire de modification -->
    <tr class="collapse bg-light" id="editForm<?= $p['id'] ?>">
        <td colspan="6">
            <form method="POST" enctype="multipart/form-data" class="form-produit fadeInUp">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <input type="text" name="nom" value="<?= htmlspecialchars($p['nom']) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="prix" step="0.01" value="<?= $p['prix'] ?>" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="stock" value="<?= $p['stock'] ?>" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" name="modifier" class="btn btn-pink">Enregistrer</button>
                    </div>
                </div>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>

        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>