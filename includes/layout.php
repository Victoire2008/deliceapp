<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = isset($pageTitle) ? $pageTitle : "DélicesApp";
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$isHome = $currentPage === "index.php";
$isDashboard = $currentPage === "dashboard.php" || $currentPage === "tableau.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .text-pink { 
      color: #d63384; 
    }
    .btn-pink {
       background-color: #f783ac; color: #fff; border: none; 
      }
    .btn-pink:hover {
       background-color: #d63384;
       }
    .btn-outline-pink {
       border: 2px solid #f783ac; color: #f783ac; background: transparent; 
      }
    .btn-outline-pink:hover { 
      background-color: #d63384; color: white; 
    }
    .navbar .dropdown-menu { 
      min-width: 200px; 
    }
    .dropdown-item:hover {
       background-color: #d63384; 
      }
  </style>
</head>
<body class="bg-white text-dark">

<?php if (!$isHome): ?>
  <!-- Navigation principale (hors accueil) -->
  <header class="bg-light border-bottom mb-4">
    <div class="container d-flex flex-wrap justify-content-between align-items-center py-3">
      <a href="index.php" class="d-flex align-items-center mb-2 text-decoration-none">
        <img src="DélicesAPP.png" alt="Logo" style="width: 50px;" class="me-2">
        <span class="fs-4 fw-bold text-pink">DélicesApp</span>
      </a>
      <nav class="d-flex align-items-center w-100">
        <ul class="nav me-auto">
          <li class="nav-item"><a href="produit.php" class="nav-link text-dark">Produits</a></li>
          <li class="nav-item"><a href="panier.php" class="nav-link text-dark">Commandes</a></li>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <li class="nav-item"><a href="dashboard.php" class="nav-link text-dark">Tableau de bord</a></li>
          <?php endif; ?>
        </ul>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?= (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false ? 'logout.php' : 'admin/logout.php') ?>" class="btn btn-pink ms-2">Déconnexion</a>
        <?php else: ?>
          <a href="/login.php" class="btn btn-pink ms-2">Connexion</a>
          <a href="/register.php" class="btn btn-outline-pink ms-2">Inscription</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
<?php endif; ?>