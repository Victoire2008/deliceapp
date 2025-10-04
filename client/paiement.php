<?php
session_start();
require_once '../includes/config.php';
$pageTitle = "Paiement";
include '../includes/layout.php';
// Définir les numéros pour les paiements mobiles
$num_wave = "77 123 45 67"; // Remplace par le vrai numéro Wave
$num_orange = "78 987 65 43"; // Remplace par le vrai numéro Orange Money


// Vérifier si le panier existe et n'est pas vide
if (empty($_SESSION['panier'])) {
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

$message = "";
if (isset($_POST['payer'])) {
    $mode = $_POST['mode_paiement'];
    if ($mode === "reception") {
        // Paiement à la réception : enregistrer la commande avec statut "à payer"
        // Exemple d'insertion (à adapter selon ta structure)
        // $pdo->prepare("INSERT INTO commandes (...) VALUES (...)")->execute([...]);
        $_SESSION['panier'] = [];
        $message = "Votre commande a été enregistrée. Vous paierez à la réception.";
    } elseif ($mode === "mobile") {
        // Paiement mobile : demander une référence et notifier l'admin
        $reference = trim($_POST['reference']);
        if ($reference == "") {
            $message = "Veuillez renseigner la référence du paiement mobile.";
        } else {
            // Enregistrer la commande avec la référence
            // $pdo->prepare("INSERT INTO commandes (...) VALUES (...)")->execute([...]);
            // Notifier l'admin (exemple : mail)
            // mail($admin_email, "Paiement mobile reçu", "Référence : $reference ...");
            $_SESSION['panier'] = [];
            $message = "Merci ! Votre paiement mobile a été pris en compte. L'admin va vérifier la confirmation.";
        }
    }
}
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
        .img-produit { object-fit: cover; border-radius: 12px; border:2px solid #f783ac; box-shadow:0 2px 8px #e83e8c22;}
        .table thead { background: #f783ac; color: #fff; }
        .table td, .table th { vertical-align: middle; }
        .mobile-info {
            background: #fff0f6;
            border: 1px solid #f783ac;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
    <script>
        function showMobileFields() {
            document.getElementById('mobile-fields').style.display = 'block';
        }
        function hideMobileFields() {
            document.getElementById('mobile-fields').style.display = 'none';
        }
        function toggleFields() {
            var mode = document.querySelector('input[name="mode_paiement"]:checked').value;
            if (mode === "mobile") {
                showMobileFields();
            } else {
                hideMobileFields();
            }
        }
    </script>
</head>
<body>
<div class="container py-4">
    <h2 class="text-center text-pink mb-4"><i class="bi bi-credit-card"></i> Paiement</h2>

    <?php if ($message): ?>
        <div class="alert alert-success fadeInUp text-center"><?= htmlspecialchars($message) ?></div>
        <div class="text-center mt-4">
            <a href="produit.php" class="btn btn-pink">Retour à la boutique</a>
        </div>
    <?php else: ?>
        <div class="table-responsive fadeInUp mb-4">
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
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
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total à payer</th>
                        <th class="text-pink"><?= number_format($total, 2, ',', ' ') ?> fcfa</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
     <form method="post" class="text-center fadeInUp">
    <div class="mb-4">
        <label class="fw-bold mb-2 text-pink">Choisissez votre mode de paiement :</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="mode_paiement" id="reception" value="reception" checked onclick="toggleFields()">
            <label class="form-check-label" for="reception">Paiement à la réception</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="mode_paiement" id="mobile" value="mobile" onclick="toggleFields()">
            <label class="form-check-label" for="mobile">Paiement Mobile</label>
        </div>
    </div>
    <div id="mobile-fields" style="display:none;">
        <div class="mb-3">
            <label class="fw-bold text-pink mb-2">Choisissez le moyen :</label><br>
            <select name="type_mobile" class="form-select w-auto d-inline" onchange="showMobileNumber()">
                <option value="">-- Sélectionner --</option>
                <option value="wave">Wave</option>
                <option value="orange">Orange Money</option>
            </select>
        </div>
        <div id="num-wave" class="mobile-info mb-2" style="display:none;">
            <span class="fw-bold text-pink">Numéro Wave :</span> <?= $num_wave ?>
        </div>
        <div id="num-orange" class="mobile-info mb-2" style="display:none;">
            <span class="fw-bold text-pink">Numéro Orange Money :</span> <?= $num_orange ?>
        </div>
        <input type="text" name="reference" class="form-control mb-3" placeholder="Référence du paiement mobile">
    </div>
    <button type="submit" name="payer" class="btn btn-pink px-5 py-2 fs-5 mt-3">
        <i class="bi bi-credit-card"></i> Valider le paiement
    </button>
</form>
<script>
function toggleFields() {
    var mode = document.querySelector('input[name="mode_paiement"]:checked').value;
    document.getElementById('mobile-fields').style.display = (mode === "mobile") ? "block" : "none";
    showMobileNumber();
}
function showMobileNumber() {
    var type = document.querySelector('select[name="type_mobile"]').value;
    document.getElementById('num-wave').style.display = (type === "wave") ? "block" : "none";
    document.getElementById('num-orange').style.display = (type === "orange") ? "block" : "none";
}
document.addEventListener("DOMContentLoaded", function() {
    toggleFields();
});
</script>
<?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>