<header class="container-fuild">
    <div class="row">
        <div class="col-lg-2">
            <a href="home.php">
                <img src="../Images/logo.png" alt="logo" class="logo">
            </a>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg ml-3">
        <div class="row">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav p-2 mr-2">
                    <a class="nav-link <?php echo ($activePage === 'home') ? 'active' : ''; ?>" href="home.php">Home</a>
                    <a class="nav-link <?php echo ($activePage === 'appointment') ? 'active' : ''; ?>" href="appointment.php">Appointment</a>
                    <a class="nav-link <?php echo ($activePage === 'facility') ? 'active' : ''; ?>" href="view_facility.php">Facility information</a>
                    <a class="nav-link <?php echo ($activePage === 'record') ? 'active' : ''; ?>" href="record.php">Record</a>
                    <a class="nav-link <?php echo ($activePage === 'blood') ? 'active' : ''; ?>" href="blood_info.php">Blood Infomation</a>
                    <a class="nav-link" href="../logout_action.php">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
</header>
