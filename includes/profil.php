<?php
session_start();
require_once 'config.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['id'];
$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $nouveau_mdp = !empty($_POST['mot_de_passe']) ? password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT) : null;

    // Gestion de la photo
    $photo_name = $old_photo = null;
    $upload_dir = '../uploads/';

    // Récupère l'ancienne photo pour la session
    if (isset($_SESSION['photo'])) {
        $old_photo = $_SESSION['photo'];
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo_name);
    }

    $sql = "UPDATE utilisateurs SET nom = ?, email = ?";
    $params = [$nom, $email];

    if ($photo_name) {
        $sql .= ", photo = ?";
        $params[] = $photo_name;
    }
    if ($nouveau_mdp) {
        $sql .= ", mot_de_passe = ?";
        $params[] = $nouveau_mdp;
    }
    $sql .= " WHERE id = ?";
    $params[] = $userId;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Mettre à jour les données de session
    $_SESSION['username'] = $nom;
    if ($photo_name) {
        $_SESSION['photo'] = $photo_name;
    }

    $message = "Profil mis à jour avec succès.";
}

// Récupérer les données de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Détermine la photo à afficher
$photo_to_show = !empty($user['photo']) ? $user['photo'] : 'default.png';

include '../includes/layout.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Mon Profil</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3 text-center">
            <img src="../uploads/<?= htmlspecialchars($photo_to_show) ?>" width="100" height="100" class="rounded-circle" style="object-fit: cover;">
        </div>

        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control">
            <small class="text-muted">Laisse vide si tu ne veux pas changer ton mot de passe.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Photo de profil</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
