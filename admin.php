<?php
require_once 'config.php';
session_start();

// Check admin login
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Handle form submissions
if ($_POST) {
    if (isset($_POST['add_user'])) {
        $nama = trim($_POST['nama']);
        $status_1 = isset($_POST['status_1']) ? 1 : 0;
        $status_2 = isset($_POST['status_2']) ? 1 : 0;
        $status_3 = isset($_POST['status_3']) ? 1 : 0;
        $status_4 = isset($_POST['status_4']) ? 1 : 0;
        $status_5 = isset($_POST['status_5']) ? 1 : 0;
        $status_6 = isset($_POST['status_6']) ? 1 : 0;
        
        if (!empty($nama)) {
            $stmt = $pdo->prepare("INSERT INTO status_data (nama, status_1, status_2, status_3, status_4, status_5, status_6) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $status_1, $status_2, $status_3, $status_4, $status_5, $status_6]);
            $success = "Data berhasil ditambahkan!";
        }
    }
    
    if (isset($_POST['delete_user'])) {
        $id = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM status_data WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Data berhasil dihapus!";
    }
    
    if (isset($_POST['update_user'])) {
        $id = $_POST['user_id'];
        $status_1 = isset($_POST['status_1']) ? 1 : 0;
        $status_2 = isset($_POST['status_2']) ? 1 : 0;
        $status_3 = isset($_POST['status_3']) ? 1 : 0;
        $status_4 = isset($_POST['status_4']) ? 1 : 0;
        $status_5 = isset($_POST['status_5']) ? 1 : 0;
        $status_6 = isset($_POST['status_6']) ? 1 : 0;
        
        $stmt = $pdo->prepare("UPDATE status_data SET status_1=?, status_2=?, status_3=?, status_4=?, status_5=?, status_6=? WHERE id=?");
        $stmt->execute([$status_1, $status_2, $status_3, $status_4, $status_5, $status_6, $id]);
        $success = "Status berhasil diupdate!";
    }
}

// Get all users
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT * FROM status_data";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE nama LIKE ?";
    $params[] = '%' . $search . '%';
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-success {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .search-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            border-color: #667eea;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 15px 10px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .data-table th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        .status-dots {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .status-dot {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-dot.active {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            transform: scale(1.1);
        }

        .status-dot.inactive {
            background: #bdc3c7;
        }

        .status-dot:hover {
            transform: scale(1.2);
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .success-message {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal h3 {
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            outline: none;
        }

        .form-group input:focus {
            border-color: #667eea;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item input[type="checkbox"] {
            width: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .logout-btn {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }

        /* Icons using CSS */
        .icon-search::before { content: '🔍'; }
        .icon-add::before { content: '➕'; }
        .icon-edit::before { content: '✏️'; }
        .icon-delete::before { content: '❌'; }
        .icon-logout::before { content: '🚪'; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .search-form {
                flex-direction: column;
            }

            .search-input {
                min-width: 100%;
            }

            .data-table {
                font-size: 0.9rem;
            }

            .data-table th,
            .data-table td {
                padding: 10px 5px;
            }

            .status-dots {
                flex-wrap: wrap;
            }

            .actions {
                flex-direction: column;
            }

            .modal-content {
                margin: 10% auto;
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }

            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="batik-pattern"></div>
    
    <div class="container">
        <div class="header">
            <h1>SELAMAT DATANG, ADMIN</h1>
            <div class="header-buttons">
                <button class="btn btn-primary" onclick="openModal('addModal')">
                    <span class="icon-add"></span> TAMBAH
                </button>
                <a href="logout.php" class="btn logout-btn">
                    <span class="icon-logout"></span> LOGOUT
                </a>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="search-container">
            <form class="search-form" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Cari nama..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">
                    <span class="icon-search"></span> Cari
                </button>
            </form>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><span class="icon-search"></span> Nama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($user['nama']); ?></strong>
                                <br>
                                <small style="color: #666;">ID: <?php echo $user['id']; ?></small>
                            </td>
                            <td>
                                <div class="status-dots">
                                    <?php for ($i = 1; $i <= 6; $i++): ?>
                                        <div class="status-dot <?php echo $user['status_' . $i] ? 'active' : 'inactive'; ?>" 
                                             title="Status <?php echo $i; ?>">
                                            <?php echo $user['status_' . $i] ? '✓' : $i; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-primary" onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                        <span class="icon-edit"></span>
                                    </button>
                                    <button class="btn btn-danger" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['nama']); ?>')">
                                        <span class="icon-delete"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 30px; color: #666;">
                                <?php echo empty($search) ? 'Belum ada data' : 'Data tidak ditemukan'; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h3>Tambah Data Baru</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Nama:</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="add_status_1" name="status_1">
                            <label for="add_status_1">Status 1</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="add_status_2" name="status_2">
                            <label for="add_status_2">Status 2</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="add_status_3" name="status_3">
                            <label for="add_status_3">Status 3</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="add_status_4" name="status_4">
                            <label for="add_status_4">Status 4</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="add_status_5" name="status_5">
                            <label for="add_status_5">Status 5</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="add_status_6" name="status_6">
                            <label for="add_status_6">Status 6</label>
                        </div>
                    </div>
                </div>
                <button type="submit" name="add_user" class="btn btn-success">Tambah Data</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <h3>Edit Status</h3>
            <form method="POST">
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="form-group">
                    <label>Nama: <span id="edit_user_name"></span></label>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_status_1" name="status_1">
                            <label for="edit_status_1">Status 1</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_status_2" name="status_2">
                            <label for="edit_status_2">Status 2</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_status_3" name="status_3">
                            <label for="edit_status_3">Status 3</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_status_4" name="status_4">
                            <label for="edit_status_4">Status 4</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_status_5" name="status_5">
                            <label for="edit_status_5">Status 5</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_status_6" name="status_6">
                            <label for="edit_status_6">Status 6</label>
                        </div>
                    </div>
                </div>
                <button type="submit" name="update_user" class="btn btn-success">Update Status</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editUser(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_user_name').textContent = user.nama;
            
            for (let i = 1; i <= 6; i++) {
                document.getElementById('edit_status_' + i).checked = user['status_' + i] == 1;
            }
            
            openModal('editModal');
        }

        function deleteUser(id, nama) {
            if (confirm('Apakah Anda yakin ingin menghapus data "' + nama + '"?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="user_id" value="${id}">
                    <input type="hidden" name="delete_user" value="1">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Animation on load
        window.addEventListener('load', function() {
            const rows = document.querySelectorAll('.data-table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                row.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>