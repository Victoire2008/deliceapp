<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = "Nom d'utilisateur ou email déjà utilisé.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);
            $success = "Inscription réussie ! Tu peux maintenant te connecter.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - DélicesApp</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff0f6 0%, #fce4ec 100%);
            min-height: 100vh;
        }
        .form-register {
            max-width: 420px;
            margin: 60px auto;
            padding: 32px 28px 24px 28px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(232,62,140,0.08), 0 1.5px 6px rgba(232,62,140,0.10);
            animation: fadeInUp 1s cubic-bezier(.39,.575,.565,1.000);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .form-register h2 {
            margin-bottom: 18px;
            color: #e83e8c;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .btn-pink {
            background: #e83e8c;
            color: #fff;
            border: none;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-pink:hover, .btn-pink:focus {
            background: #c2185b;
            color: #fff;
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 4px 16px rgba(232,62,140,0.12);
        }
        .form-label {
            color: #e83e8c;
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #e83e8c;
            box-shadow: 0 0 0 0.2rem rgba(232,62,140,0.15);
        }
        .logo-register {
            display: block;
            margin: 0 auto 18px auto;
            width: 80px;
            filter: drop-shadow(0 2px 8px #e83e8c22);
            animation: logoPop 1.2s cubic-bezier(.39,.575,.565,1.000);
        }
        @keyframes logoPop {
            0% { transform: scale(0.7) rotate(-10deg); opacity: 0;}
            60% { transform: scale(1.1) rotate(4deg);}
            100% { transform: scale(1) rotate(0); opacity: 1;}
        }
        .link-pink {
            color: #e83e8c;
            text-decoration: underline;
        }
        .link-pink:hover {
            color: #c2185b;
        }
    </style>
</head>
<body>

<div class="form-register shadow-sm">
    <img src="assets/img/logo.png" alt="Logo DélicesApp" class="logo-register" onerror="this.style.display='none'">
    <h2 class="text-center">Inscription</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php" novalidate>
        <div class="mb-3">
            <label for="username" class="form-label"><i class="bi bi-person-circle me-1"></i>Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" required value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label"><i class="bi bi-envelope-at me-1"></i>Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label"><i class="bi bi-lock me-1"></i>Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirm" class="form-label"><i class="bi bi-lock-fill me-1"></i>Confirme mot de passe</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
        <button type="submit" class="btn btn-pink w-100 mt-2">S'inscrire</button>
    </form>

    <p class="text-center mt-3 mb-0">Déjà un compte ? <a href="login.php" class="link-pink">Se connecter</a></p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
