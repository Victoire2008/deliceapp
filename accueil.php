<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>DélicesApp - Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #fcfcfc;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }
    .hero-img {
      width: 100%;
      height: 340px;
      object-fit: cover;
      border-bottom: 6px solid #cc3366;
      box-shadow: 0 8px 32px #e83e8c33;
      margin-bottom: 0;
      display: block;
    }
    .main-content {
      max-width: 900px;
      margin: 0 auto;
      padding: 40px 16px 0 16px;
      text-align: center;
    }
    h1 {
      font-size: 2.8em;
      color: #cc3366;
      font-weight: bold;
      margin-bottom: 12px;
    }
    .lead {
      font-size: 1.2em;
      color: #444;
      margin-bottom: 32px;
    }
    .btn-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      margin-top: 32px;
    }
    .btn {
      display: inline-block;
      width: 250px;
      padding: 20px;
      background-color: #cc3366;
      color: white;
      text-decoration: none;
      font-size: 1.1em;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s, background-color 0.3s;
    }
    .btn:hover {
      transform: scale(1.05);
      background-color: #a32c55;
    }
    @media (max-width: 600px) {
      .hero-img { height: 180px; }
      .btn { width: 100%; padding: 16px; font-size: 1em; }
      .btn-container { gap: 16px; flex-direction: column; }
      .main-content { padding: 24px 4px 0 4px; }
      h1 { font-size: 2em; }
    }
  </style>
</head>
<body>
  
  <div class="main-content">
    <img src="DélicesAPP.jpg" alt="Logo DélicesApp" class="mb-3" style="width: 120px;">
    <h1>Bienvenue chez DélicesApp</h1>
     <!-- Image pleine largeur -->
    <img src="https://th.bing.com/th/id/OIP.NuWAIRuu7eQePxDBOfhS_QHaE8?cb=iwc2&rs=1&pid=ImgDetMain" alt="Vitrine de pâtisserie" class="hero-img">

    <p class="lead">Une application de gestion moderne pour votre pâtisserie artisanale.</p>
    <h2 class="fw-bold text-pink" style="color:#cc3366;">Gérez votre boutique avec douceur</h2>
    <p>Ajoutez vos produits, suivez vos ventes, consultez les avis clients.<br>
      DélicesApp vous simplifie la gestion au quotidien avec une interface agréable et intuitive.</p>
    <p class="mt-4">Choisissez votre rôle pour continuer :</p>
    <div class="btn-container">
      <a href="client/produit.php" class="btn">Accéder aux Produits (client🛍️)</a>
      <a href="admin/index.php" class="btn">Connexion / Inscription (Admin 👩‍💼)</a>
    </div>
  </div>
  <?php include 'includes/footer.php'; ?>
</body>
</html>

