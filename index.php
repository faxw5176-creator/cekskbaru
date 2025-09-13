<?php
require_once 'config.php';
session_start();

// Handle form submissions
if ($_POST) {
    if (isset($_POST['cari_nama'])) {
        $nama = trim($_POST['nama']);
        if (!empty($nama)) {

            if ($nama === "cekSK0323") {
            $_SESSION['admin'] = true;
            header("Location: admin.php");
            exit;
        }

            // Cek apakah nama ada di database
            $stmt = $pdo->prepare("SELECT * FROM status_data WHERE LOWER(nama) = LOWER(?)");
            $stmt->execute([$nama]);
            $user = $stmt->fetch();
            
            if ($user) {
                $_SESSION['user_data'] = $user;
                header('Location: status.php');
                exit;
            } else {
                $error = "Nama tidak ditemukan dalam database.";
            }
        } else {
            $error = "Silakan masukkan nama Anda.";
        }
    }
    
    if (isset($_POST['admin_login'])) {
        $password = $_POST['admin_password'];
        if ($password === ADMIN_PASSWORD) {
            $_SESSION['admin'] = true;
            header('Location: admin.php');
            exit;
        } else {
            $admin_error = "Password admin salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status SK</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        .batik-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><pattern id="batik" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="3" fill="%23ffffff"/><path d="M10,10 Q20,5 30,10 Q35,20 30,30 Q20,35 10,30 Q5,20 10,10" fill="none" stroke="%23ffffff" stroke-width="1"/><circle cx="8" cy="8" r="1" fill="%23ffffff"/><circle cx="32" cy="8" r="1" fill="%23ffffff"/><circle cx="8" cy="32" r="1" fill="%23ffffff"/><circle cx="32" cy="32" r="1" fill="%23ffffff"/></pattern></defs><rect width="200" height="200" fill="url(%23batik)"/></svg>');
            opacity: 0.05;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            margin: 20px;
            z-index: 1;
        }

        .header-text {
            text-align: center;
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 40px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .main-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            min-height: 400px;
        }

        .character {
            flex: 0 0 200px;
            text-align: center;
            z-index: 3;
        }

        .character-img {
            width: 180px;
            height: 300px;
            background: linear-gradient(45deg, #4a90e2, #357abd);
            border-radius: 50px 50px 10px 10px;
            position: relative;
            margin: 0 auto;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .character:hover .character-img {
            transform: scale(1.05);
        }

        .character-img::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            background: #f4c2a1;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .perempuan1::after {
            content: '👋';
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2rem;
        }

        .perempuan2::after {
            content: '🙏';
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2rem;
        }

        .search-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 3;
            margin: 0 40px;
        }

        .search-form {
            width: 100%;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 15px 25px;
            font-size: 1.1rem;
            border: 3px solid #e0e0e0;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            font-family: 'Poppins', sans-serif;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
            background: white;
        }

        .button-group {
            text-align: center;
            margin-top: 20px;
        }

        .search-button {
            padding: 12px 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Poppins', sans-serif;
            margin: 5px;
        }
        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .search-button:active {
            transform: translateY(0);
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-top: 10px;
            font-weight: 500;
        }

        .admin-panel {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            display: none;
        }

        .admin-panel.show {
            display: block;
        }

        .admin-input {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-family: 'Poppins', sans-serif;
            margin: 10px;
            outline: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }

            .header-text {
                font-size: 1.8rem;
                margin-bottom: 30px;
            }

            .main-content {
                flex-direction: column;
                gap: 30px;
                min-height: auto;
            }

            .character {
                flex: none;
            }

            .character-img {
                width: 120px;
                height: 200px;
            }

            .search-area {
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .header-text {
                font-size: 1.5rem;
            }

            .search-input {
                padding: 12px 20px;
                font-size: 1rem;
            }

            .search-button, .admin-button {
                padding: 10px 30px;
                font-size: 1rem;
            }

            .character-img {
                width: 100px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="batik-pattern"></div>
    
    <div class="container">
        <div class="header-text">
            Selamat Datang! Silahkan Masukan Nama Anda
        </div>

        <div class="main-content">
            <!-- Karakter Perempuan 1 -->
            <div class="character">
                <div class="character-img perempuan1"></div>
            </div>

            <!-- Area Pencarian -->
            <div class="search-area">
                <form method="POST" class="search-form">
                    <input type="text" class="search-input" name="nama" placeholder="Masukkan nama Anda..." required>
                    
                    <div class="button-group">
                        <button type="submit" name="cari_nama" class="search-button">CARI</button>
                        <br>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                </form>

                <!-- Panel Admin -->
                <div class="admin-panel" id="adminPanel">
                    <form method="POST">
                        <h3 style="margin-bottom: 15px; color: #333;">Login Admin</h3>
                        <input type="password" name="admin_password" class="admin-input" placeholder="Password Admin" required>
                        <br>
                        <button type="submit" name="admin_login" class="admin-button">LOGIN</button>
                        <?php if (isset($admin_error)): ?>
                            <div class="error-message"><?php echo htmlspecialchars($admin_error); ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Karakter Perempuan 2 -->
            <div class="character">
                <div class="character-img perempuan2"></div>
            </div>
        </div>
    </div>

    <script>
        function toggleAdmin() {
            const panel = document.getElementById('adminPanel');
            panel.classList.toggle('show');
        }

        // Animation on load
        window.addEventListener('load', function() {
            const container = document.querySelector('.container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            container.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>