<?php
session_start();
unset($_SESSION['user_id']); // Ajoute cette ligne pour tester
 include '../includes/layout.php'; 

?>
<main class="container mt-4">
  <!-- Logo et Accroche -->
  <section class="text-center mb-5">
    <img src="DélicesAPP.jpg" alt="Logo DélicesApp" class="mb-3" style="width: 120px;">
    <h1 class="display-5 fw-bold text-pink">Bienvenue chez DélicesApp </h1>
    <p class="lead text-secondary">Une application de gestion moderne pour votre pâtisserie artisanale.</p>
  </section>

  <?php if (!isset($_SESSION['user_id'])): ?>
    <!-- Appel à l'action si non connecté -->
    <section class="text-center mb-5">
      <h4 class="mb-3">Vous êtes pâtissier ?</h4>
      <a href="login.php" class="btn btn-pink me-2">Se connecter</a>
      <a href="register.php" class="btn btn-outline-pink">Créer un compte</a>
    </section>
  <?php endif; ?>

  <!-- À propos -->
  <section class="row align-items-center my-5">
    <div class="col-md-6">
      <img src="https://th.bing.com/th/id/OIP.NuWAIRuu7eQePxDBOfhS_QHaE8?cb=iwc2&rs=1&pid=ImgDetMain" alt="Vitrine de pâtisserie" class="img-fluid rounded shadow">
    </div>
    <div class="col-md-6">
      <h2 class="fw-bold text-pink">Gérez votre boutique avec douceur</h2>
      <p>Ajoutez vos produits, suivez vos ventes, consultez les avis clients. DélicesApp vous simplifie la gestion au quotidien avec une interface agréable et intuitive.</p>
    </div>
  </section>

  <!-- Avis Clients -->
  <section class="bg-light p-5 rounded shadow mt-5">
    <h3 class="text-center text-pink mb-4">Ils adorent DélicesApp 💬</h3>
    <div class="row">
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <p class="card-text">"L’interface est douce et facile. Je peux mettre mes photos de gâteaux en quelques clics."</p>
            <h6 class="card-subtitle mt-3 text-muted">– Marie C.</h6>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <p class="card-text">"Je reçois les commandes de mes clients directement sur le site. C’est top."</p>
            <h6 class="card-subtitle mt-3 text-muted">– Karim T.</h6>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <p class="card-text">"Un gain de temps énorme. Très beau visuellement en plus."</p>
            <h6 class="card-subtitle mt-3 text-muted">– Aline R.</h6>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php include '../includes/footer.php'; ?>
