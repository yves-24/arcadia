<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Zoo Arcadia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">Zoo Arcadia</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="habitats.php">Habitats</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Section Contact -->
  <section class="py-5">
    <div class="container">
      <h1>Contactez-nous</h1>
      <form action="php/contact_process.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Votre Email</label>
          <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Votre Message</label>
          <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Envoyer</button>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-center text-white py-3">
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
