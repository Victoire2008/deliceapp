<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Bienvenue - Délices App</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        background: linear-gradient(135deg, #fff0f6 0%, #fceabb 100%);
        min-height: 100vh;
        width: 100vw;
        overflow: hidden;
        position: relative;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .floating-bg {
        position: fixed;
        inset: 0;
        z-index: 1; /* Derrière le texte mais devant le fond */
        pointer-events: none;
        width: 100vw;
        height: 100vh;
    }
    .float-img {
        position: absolute;
        width: 140px;
        opacity: 0.85;
        filter: drop-shadow(0 2px 16px #e83e8c33);
        user-select: none;
        transition: opacity 0.3s;
        z-index: 1;
    }
    /* Animations personnalisées pour chaque image */
    .img1 { animation: float1 18s linear infinite alternate; }
    .img2 { animation: float2 22s linear infinite alternate; }
    .img3 { animation: float3 20s linear infinite alternate; }
    .img4 { animation: float4 24s linear infinite alternate; }
    .img5 { animation: float5 19s linear infinite alternate; }

    @keyframes float1 {
        0%   { left: 5vw;  top: 10vh; }
        25%  { left: 20vw; top: 20vh; }
        50%  { left: 10vw; top: 60vh; }
        75%  { left: 25vw; top: 40vh; }
        100% { left: 5vw;  top: 10vh; }
    }
    @keyframes float2 {
        0%   { left: 70vw; top: 18vh; }
        20%  { left: 80vw; top: 30vh; }
        40%  { left: 60vw; top: 60vh; }
        60%  { left: 75vw; top: 70vh; }
        100% { left: 70vw; top: 18vh; }
    }
    @keyframes float3 {
        0%   { left: 18vw; top: 65vh; }
        30%  { left: 35vw; top: 70vh; }
        60%  { left: 30vw; top: 30vh; }
        100% { left: 18vw; top: 65vh; }
    }
    @keyframes float4 {
        0%   { left: 60vw; top: 70vh; }
        25%  { left: 80vw; top: 80vh; }
        50%  { left: 55vw; top: 40vh; }
        75%  { left: 65vw; top: 20vh; }
        100% { left: 60vw; top: 70vh; }
    }
    @keyframes float5 {
        0%   { left: 40vw; top: 40vh; }
        20%  { left: 50vw; top: 10vh; }
        50%  { left: 60vw; top: 50vh; }
        80%  { left: 30vw; top: 60vh; }
        100% { left: 40vw; top: 40vh; }
    }

    .welcome-center {
        position: relative;
        z-index: 10; /* Toujours au-dessus des images */
        width: 100vw;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .welcome-container {
        background: #fff;
        padding: 40px 48px;
        border-radius: 18px;
        box-shadow: 0 15px 40px rgba(232,62,140,0.10), 0 1.5px 6px rgba(232,62,140,0.10);
        text-align: center;
        min-width: 340px;
        max-width: 90vw;
        animation: fadeInUp 1.2s 0.3s forwards;
        opacity: 0;
        transform: translateY(30px);
        z-index: 20;
    }
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    h1 {
        font-weight: 700;
        font-size: 2.3rem;
        color: #e83e8c;
        margin-bottom: 18px;
        text-shadow: 1px 1px 3px rgba(232,62,140,0.08);
    }
    p {
        font-size: 1.15rem;
        color: #555;
        margin-bottom: 30px;
    }
    .btn-visit {
        background-color: #e83e8c;
        color: white;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 50px;
        border: none;
        transition: background 0.2s, transform 0.2s;
        box-shadow: 0 5px 15px rgba(232,62,140,0.10);
        font-size: 1.1rem;
    }
    .btn-visit:hover {
        background-color: #c2185b;
        box-shadow: 0 7px 20px rgba(232,62,140,0.18);
        text-decoration: none;
        color: white;
        transform: translateY(-2px) scale(1.04);
    }
    @media (max-width: 700px) {
        .welcome-container { padding: 28px 4vw; min-width: unset;}
        .float-img { width: 80px; }
    }
</style>
</head>
<body>

<!-- Images flottantes en arrière-plan -->
<div class="floating-bg">
    <img src="https://th.bing.com/th/id/R.0add3323f50866b34bf7d8b4b0a99b17?rik=lVLuWJeBnnrcjA&pid=ImgRaw&r=0" class="float-img img1">
    <img src="https://th.bing.com/th/id/OIP.FT99TLbLOiFVls-NdcurXgHaFE?cb=iwc2&rs=1&pid=ImgDetMain" class="float-img img2">
    <img src="https://www.rosemary-patisserie.fr/wp-content/uploads/slider/cache/ece4956b134ad90240d3d71cd54e2fca/63-rosemary-patisserie-novembre-2022.jpg" class="float-img img3">
    <img src="https://www.royalchill.com/wp-content/uploads/2016/10/pierre-her.jpg" class="float-img img4">
    <img src="https://img.freepik.com/photos-premium/confiserie-photo-patisseries-francaises-arrangement-indulgent-gros-plan-macro-standard-dessert-concept-art_655090-1521507.jpg" class="float-img img5">
</div>

<div class="welcome-center">
    <div class="welcome-container">
        <h1>Bienvenue sur DélicesApp, <?= $username ?> !</h1>
        <p>Votre espace gourmand vous attend.<br>
        Gérez vos douceurs, vos commandes et vos clients en toute simplicité.</p>
        <a href="dashboard.php" class="btn btn-visit mt-2"><i class="bi bi-cupcake me-2"></i>Visiter mon espace</a>
    </div>
</div>

<!-- Bootstrap JS et icônes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>
