<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Pemesanan Kue</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center font-sans">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-6xl mx-4 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <!-- Bagian Kiri: Teks Selamat Datang -->
            <div class="bg-purple-600 text-white p-12 flex flex-col justify-center items-center lg:items-start text-center lg:text-left">
                <h1 class="text-4xl lg:text-5xl font-bold mb-6">Selamat Datang Kembali!</h1>
                <p class="text-lg lg:text-xl mb-8 leading-relaxed">
                    Nikmati kemudahan memesan kue favorit Anda di Toko Kue Manis. Masuk ke akun Anda untuk melanjutkan perjalanan kuliner yang lezat.
                </p>
                <div class="flex items-center text-2xl">
                    <i class="fas fa-birthday-cake mr-3"></i>
                    <span class="font-semibold">Rasakan Kenikmatannya</span>
                </div>
            </div>
            <!-- Bagian Kanan: Form Login -->
            <div class="p-12">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800"><i class="fas fa-sign-in-alt text-purple-500"></i> Login</h2>
                <?php
                session_start();
                include 'includes/db.php';

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    // Gunakan prepared statement untuk keamanan
                    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 1) {
                        $user = $result->fetch_assoc();
                        // Asumsikan password sudah di-hash dengan password_hash()
                        if (password_verify($password, $user['password'])) {
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $user['role'];

                            if ($user['role'] == 'admin') {
                                header("Location: admin/index.php");
                            } else {
                                header("Location: index.php");
                            }
                            exit();
                        } else {
                            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>Password salah!</div>";
                        }
                    } else {
                        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>Username tidak ditemukan!</div>";
                    }

                    $stmt->close();
                }

                $conn->close();
                ?>
                <form method="POST" action="" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-user text-purple-500"></i> Username</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" id="username" name="username" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2"><i class="fas fa-lock text-purple-500"></i> Password</label>
                        <input type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" id="password" name="password" required>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-4 rounded-lg hover:from-purple-600 hover:to-pink-600 transition duration-300 font-semibold text-lg">Login</button>
                </form>
                <div class="text-center mt-8">
                    <p class="text-gray-600">Belum punya akun? <a href="register.php" class="text-purple-500 hover:text-purple-700 font-medium"><i class="fas fa-user-plus"></i> Daftar di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>