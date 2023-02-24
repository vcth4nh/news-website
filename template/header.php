<header class="bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <a class="mb-0 h1 text-white text-decoration-none" href="/">News Website</a>
            </div>
            <div class="col-sm-6">
                <form action="/" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-3">
                <nav class="navbar navbar-expand-sm navbar-dark">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Categories</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Story</a>
                            </li>
                            <?php if ($_SERVER['REQUEST_URI'] != '/login.php'): ?>
                                <?php if (isset($_SESSION['cred'])): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/author.php">Author</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/api/logout.php">Logout</a>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/login.php">Login</a>
                                    </li>
                                <?php endif; ?>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/register.php">Register</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>