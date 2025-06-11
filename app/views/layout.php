<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Restaurant Reservation'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <nav class="bg-red-600 p-4">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <img src="/resources/image/logo.jpg" alt="Logo" class="w-8 h-8 rounded-full object-cover">
                <span class="text-white text-xl font-bold ml-2">MISAKI BISTRO</span>
            </div>
            <div class="block lg:hidden">
                <button class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-4 6h4"></path>
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:items-center lg:w-auto" id="navbarNav">
                <?php if (isset($navItems)): ?>
                <ul class="flex items-center space-x-4">
                    <?php foreach ($navItems as $item): ?>
                    <li>
                        <a class="text-white hover:text-red-300 transition duration-300" href="<?php echo $item['url']; ?>"><?php echo $item['text']; ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <ul class="flex items-center space-x-4 ml-4">
                    <li>
                        <a class="text-white hover:text-red-300 transition duration-300" href="/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-4 px-4">
        <?php if (isset($content)) echo $content; ?>
    </div>

    
</body>
</html> 