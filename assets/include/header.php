<!-- header.php -->
<?php 
    session_start();
    include 'database/conns.php';
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php");
        exit;
    }

    
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



<header>
    <div class="header">
        <div class="logo">
            <img src="../assets/img/logo.png" alt="logo">
        </div>

        <div class="header-text">
            <span class="name">CYBER VISITOR'S LOG</span>
        </div>
        
        <div class="profile">
            <div class="frame">
                <span class="profile-holder">
                    <img src="img/profile.png" alt="placeholder">
                </span>
                <div class="box">
                    <?php
                    if($roleType == "Admin"){
                        echo '<span class="box-text">Admin</span>';
                    }else if($roleType == "User"){
                        echo '<span class="box-text">User</span>';
                    }
                    ?>
                    <img src="img/downward-arrow.png" alt="arrow" class="box-arrow">
                </div>
                <div class="dropdown-logout">
                    <ul>
                        <li><a href="../assets/database/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const box = document.querySelector('.box');
        const dropdown = document.querySelector('.dropdown-logout');
        box.addEventListener('click', () => {
            dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
        });

        // Optionally, you can close the dropdown when clicking outside of it
        document.addEventListener('click', (event) => {
            if (!box.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    });
</script>