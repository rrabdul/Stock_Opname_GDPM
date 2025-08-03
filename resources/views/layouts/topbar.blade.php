<nav class="navbar navbar-top navbar-expand navbar-dashboard >
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">

            <!-- Bagian kiri: Salam -->
            <div>
                <h5 class="text-dark mb-0">
                    Hi, {{ trim(Auth::user()?->first_name . ' ' . Auth::user()?->last_name) ?? 'User' }}
                </h5>
            </div>

            <!-- Bagian kanan: Profil dropdown -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fa-lg"></i>
                    <span class="ms-2">{{ Auth::user()?->first_name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                    <li><a class="dropdown-item" href="/settings">Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout">Logout</a></li>
                </ul>
            </div>

        </div>
    </div>
</nav>
