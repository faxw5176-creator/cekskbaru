<?php
require_once 'config.php';
session_start();

// Redirect jika tidak ada data user
if (!isset($_SESSION['user_data'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user_data'];

// Tentukan status terakhir yang aktif
$current_status = 0;
for ($i = 6; $i >= 1; $i--) {
    if ($user['status_' . $i]) {
        $current_status = $i;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Anda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            min-height: 100vh;
            position: relative;
        }

        .batik-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><pattern id="batik" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="3" fill="%23ffffff"/><path d="M10,10 Q20,5 30,10 Q35,20 30,30 Q20,35 10,30 Q5,20 10,10" fill="none" stroke="%23ffffff" stroke-width="1"/></pattern></defs><rect width="200" height="200" fill="url(%23batik)"/></svg>');
            opacity: 0.03;
            z-index: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .user-name {
            color: #667eea;
            font-size: 1.3rem;
            font-weight: 500;
        }

        .status-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .status-timeline {
            position: relative;
        }

        .status-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            position: relative;
            padding-left: 60px;
        }

        .status-item:last-child {
            margin-bottom: 0;
        }

        .status-bullet {
            position: absolute;
            left: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 0.9rem;
            z-index: 2;
        }

        .status-bullet.active {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            animation: pulse 2s infinite;
        }

        .status-bullet.completed {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
        }

        .status-bullet.inactive {
            background: #bdc3c7;
        }

        .status-line {
            position: absolute;
            left: 19px;
            top: 40px;
            width: 2px;
            height: 25px;
            background: #bdc3c7;
        }

        .status-line.completed {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
        }

        .status-item:last-child .status-line {
            display: none;
        }

        .status-text {
            flex: 1;
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .status-text.active {
            color: #27ae60;
            font-weight: 600;
        }

        .status-text.inactive {
            color: #95a5a6;
        }

        .current-status {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 15px;
            color: white;
            text-align: center;
        }

        .current-status h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .current-status p {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .back-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(39, 174, 96, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(39, 174, 96, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(39, 174, 96, 0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .user-name {
                font-size: 1.1rem;
            }

            .status-item {
                padding-left: 50px;
            }

            .status-bullet {
                width: 35px;
                height: 35px;
                font-size: 0.8rem;
            }

            .status-line {
                left: 16px;
            }

            .back-button {
                bottom: 20px;
                right: 20px;
                padding: 12px 25px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 20px;
            }

            .status-container {
                padding: 20px;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .status-text {
                font-size: 1rem;
            }

            .current-status h3 {
                font-size: 1.1rem;
            }

            .current-status p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="batik-pattern"></div>
    
    <div class="container">
        <div class="header">
            <h1>STATUS ANDA</h1>
            <div class="user-name">Halo, <?php echo htmlspecialchars($user['nama']); ?>!</div>
        </div>

        <div class="status-container">
            <div class="status-timeline">
                <?php
                $statuses = [
                    1 => 'SK-mu sedang diproses Staf Bagian Hukum',
                    2 => 'SK-mu sedang ditandatangani Kepala Bagian Hukum', 
                    3 => 'SK-mu sedang ditandatangani Sekretaris Daerah',
                    4 => 'SK-mu sedang ditandatangani Wali Kota',
                    5 => 'SK-mu sudah selesai',
                    6 => 'Yay! SK-mu sudah bisa diambil. Silakan datang ke Bagian Hukum untuk mengambil SK-mu. ^_^'
                ];

                foreach ($statuses as $num => $desc) {
                    $isCompleted = $user['status_' . $num];
                    $isCurrent = ($num == $current_status);
                    
                    $bulletClass = 'inactive';
                    $textClass = 'inactive';
                    $lineClass = '';
                    
                    if ($isCompleted && $isCurrent) {
                        $bulletClass = 'active';
                        $textClass = 'active';
                        $lineClass = 'completed';
                    } elseif ($isCompleted) {
                        $bulletClass = 'completed';
                        $textClass = 'completed';
                        $lineClass = 'completed';
                    }
                    ?>
                    <div class="status-item">
                        <div class="status-bullet <?php echo $bulletClass; ?>">
                            <?php echo $isCompleted ? '✓' : $num; ?>
                        </div>
                        <div class="status-line <?php echo $lineClass; ?>"></div>
                        <div class="status-text <?php echo $textClass; ?>">
                            <?php echo htmlspecialchars($desc); ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <?php if ($current_status > 0): ?>
                <div class="current-status">
                    <h1>Status Terkini</h1>
                    <p><?php echo htmlspecialchars($statuses[$current_status]); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <a href="index.php" class="back-button">← Kembali</a>

    <script>
        // Animation on load
        window.addEventListener('load', function() {
            const items = document.querySelectorAll('.status-item');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-30px)';
                item.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>