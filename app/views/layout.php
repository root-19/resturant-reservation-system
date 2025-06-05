<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Restaurant Reservation'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-red: #dc3545;
            --light-red: #ff6b6b;
            --dark-red: #c82333;
        }
        .navbar {
            background-color: var(--primary-red) !important;
        }
        .card {
            border-color: var(--primary-red);
        }
        .card-title {
            color: var(--primary-red);
        }
        .list-group-item:hover {
            background-color: var(--light-red);
            color: white;
        }
        .list-group-item {
            border-color: var(--primary-red);
        }
        .btn-primary {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
        }
        .btn-primary:hover {
            background-color: var(--dark-red);
            border-color: var(--dark-red);
        }
        .table thead th {
            background-color: var(--primary-red);
            color: white;
        }
        .nav-link {
            font-weight: 500;
        }
        .nav-link:hover {
            color: var(--light-red) !important;
        }
        .brand-logo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        .brand-name {
            font-size: 1.25rem;
            font-weight: bold;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="/resources/image/logo.jpg" alt="Logo" class="brand-logo">
                <span class="brand-name text-white">MISAKI BISTRO</span>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isset($navItems)): ?>
                <ul class="navbar-nav me-auto">
                    <?php foreach ($navItems as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $item['url']; ?>"><?php echo $item['text']; ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($content)) echo $content; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 