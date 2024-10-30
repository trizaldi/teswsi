<?php
require("database.php");
require("fungsi.php");
session_start();

$a = 0;

// Periksa apakah pengguna telah login
if (!isset($_SESSION['u_nama'])) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
}

$userFullName = $_SESSION['u_nama'];

// Proses Insert Data
if (isset($_POST['insert'])) {
    $email = $_POST['email'];
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $level = $_POST['level'];
    
    // Insert ke database
    $query = "INSERT INTO tm_user (u_email, u_nama, u_password, u_level) VALUES ('$email', '$nama', '$password', '$level')";
    mysqli_query($koneksi, $query);
    header("Location: home.php");
    exit();
}

// Ambil data semua pengguna
$result = getAllUsers();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #343a40;
        }
        .table-container {
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .btn-danger {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Selamat Datang <?php echo htmlspecialchars($userFullName); ?></h1>
        <div class="text-right mb-3">
            <a href="logout.php" class="btn btn-danger">Keluar</a>
        </div>
        
        <!-- Form Insert Data -->
        <div class="card mb-4">
            <div class="card-header">Tambah Pengguna Baru</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 py-2">
                            <label for="email">E-Mail</label>
                            <input class="form-control" type="text" name="email" id="email" required>
                        </div>
                        <div class="col-md-6 py-2">
                            <label for="nama">Nama</label>
                            <input class="form-control" type="text" name="nama" id="nama" required>
                        </div>
                        <div class="col-md-6 py-2">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" name="password" id="password" required>
                        </div>
                        <div class="col-md-6 py-2">
                            <label for="level">Level</label>
                            <select class="form-control" name="level" id="level" required>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" type="submit" name="insert">Tambah Pengguna</button>
                </form>
            </div>
        </div>

        <!-- Tabel Pengguna -->
        <div class="table-container">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Email</th>
                        <th>Nama</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_array($result)) {
                        $userMail = $row['u_email'];
                        $userName = $row['u_nama'];
                        $level = $row['u_level'] == 1 ? "Admin" : "User";
                    ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo htmlspecialchars($userMail); ?></td>
                        <td><?php echo htmlspecialchars($userName); ?></td>
                        <td><?php echo htmlspecialchars($level); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['u_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="hapus.php?id=<?php echo $row['u_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
