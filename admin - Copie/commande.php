<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit;
}

include_once("config.php");

// Mise à jour du statut
if (isset($_POST['changer_statut'])) {
    $id = $_POST['commande_id'];
    $statut = $_POST['statut'];
    $sql = "UPDATE commandes SET statut='$statut' WHERE id=$id";
    mysqli_query($conn, $sql);
}

// Récupération des commandes
$commandes = mysqli_query($conn, "SELECT * FROM commandes ORDER BY date_commande DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Commandes</title>
    <style>
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background: #cc3366;
            color: white;
        }
        td form {
            margin: 0;
        }
        select, button {
            padding: 5px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">📦 Commandes clients</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Date</th>
        <th>Total</th>
         <th>Type de paiement</th>
        <th>Statut</th>
        <th>Modifier</th>
    </tr>
   <?php while ($cmd = mysqli_fetch_assoc($commandes)) : ?>
<tr>
    <td><?= $cmd['id'] ?></td>
    <td><?= htmlspecialchars($cmd['client_nom']) ?></td>
    <td><?= $cmd['date_commande'] ?></td>
    <td><?= number_format($cmd['total'], 0, ',', ' ') ?> FCFA</td>
    <td><?= htmlspecialchars($cmd['type_paiement']) ?></td> <!-- affichage -->
    <td><?= $cmd['statut'] ?></td>
    <td>
        <?php if ($cmd['statut'] != "Livré") : ?>
        <form method="post">
            <input type="hidden" name="commande_id" value="<?= $cmd['id'] ?>">
            <select name="statut">
                <option <?= $cmd['statut'] == "En attente" ? "selected" : "" ?>>En attente</option>
                <option <?= $cmd['statut'] == "En cours" ? "selected" : "" ?>>En cours</option>
                <option <?= $cmd['statut'] == "Livré" ? "selected" : "" ?>>Livré</option>
            </select>
            <button type="submit" name="changer_statut">Valider</button>
        </form>
        <?php else : ?> ✅
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
