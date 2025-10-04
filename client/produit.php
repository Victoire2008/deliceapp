<?php
session_start();
require_once("../includes/config.php");
include '../includes/layout.php';
// Connexion MySQLi (si $conn non défini)
if (!isset($conn)) {
    $conn = mysqli_connect('localhost', 'root', '', 'délicesapp');
    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }
}

// Initialiser le panier si besoin
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajout au panier
$message = "";
if (isset($_POST['ajouter'])) {
    $id_produit = intval($_POST['produit_id']);
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit]++;
    } else {
        $_SESSION['panier'][$id_produit] = 1;
    }
    $message = "Produit ajouté au panier !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue DélicesApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #fff0f6 0%, #f783ac 100%);
            min-height: 100vh;
        }
        .container-catalogue {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 8px 32px #e83e8c33;
            padding: 2.5rem 2rem;
        }
        h1 {
            text-align: center;
            color: #e83e8c;
            margin-bottom: 30px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .message {
            background: #dff0d8;
            padding: 10px;
            margin: 20px auto;
            width: 60%;
            color: #3c763d;
            text-align: center;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .produits {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 32px;
            margin-top: 30px;
        }
        .produit {
            background: #fff;
            padding: 18px 12px 16px 12px;
            border-radius: 18px;
            box-shadow: 0 2px 12px #e83e8c22;
            text-align: center;
            transition: transform 0.2s;
            position: relative;
        }
        .produit:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 6px 24px #e83e8c33;
        }
        .produit img {
            max-width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #f783ac;
            margin-bottom: 12px;
            background: #fff0f6;
        }
        .produit h3 {
            color: #e83e8c;
            font-size: 1.25rem;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .produit p {
            margin-bottom: 8px;
            color: #444;
        }
        .btn-pink {
            background: linear-gradient(135deg, #f783ac 0%, #e83e8c 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 8px 24px;
            transition: 0.2s;
            box-shadow: 0 2px 8px #e83e8c22;
        }
        .btn-pink:hover {
            background: linear-gradient(135deg, #e83e8c 0%, #f783ac 100%);
            color: #fff;
            transform: scale(1.05);
        }
        .panier-link {
            text-align: right;
            margin-bottom: 20px;
        }
        .panier-link a {
            color: #e83e8c;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .panier-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container-catalogue">
        <h1><i class="bi bi-cupcake"></i> Catalogue de Pâtisseries</h1>

        <div class="panier-link">
            <a href="panier.php"><i class="bi bi-cart"></i> Voir mon panier</a>
        </div>

        <?php if (!empty($message)) : ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="produits">
            <?php
            $resultats = mysqli_query($conn, "SELECT * FROM produits");
            while ($produit = mysqli_fetch_assoc($resultats)) :
            ?>
                <div class="produit">
                    <img src="../uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                    <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                    <p><?= htmlspecialchars($produit['description']) ?></p>
                    <p><strong><?= number_format($produit['prix'], 0, ',', ' ') ?> FCFA</strong></p>
                    <form method="post">
                        <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                        <button class="btn btn-pink" type="submit" name="ajouter">
                            <i class="bi bi-cart-plus"></i> Ajouter au panier
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
