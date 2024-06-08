<!-- sidebar -->
<?php 
    include 'database/conns.php';
    $roleType="";

    echo '<script>console.log("Username: ' . $_SESSION['username'] . '");</script>';

    $sql = "SELECT * FROM tb_users WHERE username = '" . $_SESSION['username'] . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $roleType = $row['user_role'];
            echo '<script>console.log("Role: ' . $roleType . '");</script>';
        }

?>
<nav class="sidebar">
    <div class="menu-bar">
        <div class="menu">
            <ul class="menu-links">
                <a href="dashboard.php" class="nav-link">
                    <i class="dashboard-alt"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="visitors.php" class="nav-link">
                    <i class="dashboard-alt"></i>
                    <span class="nav-text">Visitors</span>
                </a>
                <?php
                  if($roleType == "Admin"){
                    echo ' <a href="users.php" class="nav-link">
                        <i class="dashboard-alt"></i>
                        <span class="nav-text">Users</span>
                    </a>';
                  }  
                ?>
               
            </ul>
        </div>
    </div>
</nav>