<?php
// hanya boleh dibuka melalui pengiriman form (POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: form_anggota.php");
    exit;
}

// mengambil data dengan metode POST
$no_anggota    = isset($_POST['no_anggota'])    ? trim($_POST["no_anggota"]) : "";
$nama_lengkap  = isset($_POST['nama_lengkap'])  ? trim($_POST["nama_lengkap"]) : "";
$email         = isset($_POST['email'])         ? trim($_POST["email"]) : "";
$no_hp         = isset($_POST['no_hp'])         ? trim($_POST["no_hp"]) : "";
$jenis_anggota = isset($_POST['jenis_anggota']) ? trim($_POST["jenis_anggota"]) : "";
$alamat        = isset($_POST['alamat'])        ? trim($_POST["alamat"]) : "";

// validasi di sisi server
$daftar_jenis = array("Mahasiswa", "Dosen", "Tenaga Kependidikan");
$errors       = array();

// validasi Nomor Anggota
if (empty($no_anggota)) {
    $errors[] = "Nomor Anggota tidak boleh kosong.";
} elseif (!ctype_digit($no_anggota)) {
    $errors[] = "Nomor Anggota hanya boleh berisi angka.";
} elseif (strlen($no_anggota) > 15) {
    $errors[] = "Nomor Anggota maksimal 15 digit.";
}

// validasi Nama Lengkap
if (empty($nama_lengkap)) {
    $errors[] = "Nama Lengkap tidak boleh kosong.";
} elseif (strlen($nama_lengkap) > 100) {
    $errors[] = "Nama Lengkap maksimal 100 karakter.";
}

// validasi Email
if (empty($email)) {
    $errors[] = "Email tidak boleh kosong.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
} elseif (strlen($email) > 100) {
    $errors[] = "Email maksimal 100 karakter.";
}

// validasi Nomor HP
if (empty($no_hp)) {
    $errors[] = "Nomor HP tidak boleh kosong.";
} elseif (!ctype_digit($no_hp)) {
    $errors[] = "Nomor HP hanya boleh berisi angka.";
} elseif (strlen($no_hp) < 10 || strlen($no_hp) > 13) {
    $errors[] = "Nomor HP harus berjumlah 10 hingga 13 digit.";
}

// validasi Jenis Anggota
if (empty($jenis_anggota)) {
    $errors[] = "Jenis Anggota wajib dipilih.";
} elseif (!in_array($jenis_anggota, $daftar_jenis, true)) {
    $errors[] = "Jenis Anggota tidak valid.";
}

// validasi Alamat
if (empty($alamat)) {
    $errors[] = "Alamat tidak boleh kosong.";
}

$berhasil = false;
$pesan_db = "";

if (empty($errors)) {
    require_once "koneksi.php";

    $sql = "INSERT INTO anggota (no_anggota, nama_lengkap, email, no_hp, 
            jenis_anggota, alamat) " . "VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt, "ssssss", $no_anggota, $nama_lengkap, $email, $no_hp,
            $jenis_anggota, $alamat
        );

        if (mysqli_stmt_execute($stmt)) {
            $berhasil = true;
        } else {
            $pesan_db = mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $pesan_db = mysqli_error($koneksi);
    }
    mysqli_close($koneksi);

    if ($berhasil) {
        header("Location: form_anggota.php?status=sukses");
        exit;
    }
}
?> 

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Penyimpanan Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: purple; }
        .gagal { background: #fdecea; border-left: 5px solid #c0392b;
                 padding: 10px; }
    </style>
</head>
<body>
    <?php if (!empty($errors)) { ?>
        <h1>Data Tidak Valid</h1>
        <p class="gagal">Data belum dapat disimpan. Perbaiki kesalahan:</p>
        <ul>
            <?php foreach ($errors as $pesan) { ?>
                <li><?php echo htmlspecialchars($pesan); ?></li>
            <?php } ?>
        </ul>

    <?php } else { ?>
        <h1>Data Gagal Disimpan</h1>
        <p class="gagal">Terjadi kesalahan saat menyimpan data ke database.</p>
        <p>Pesan dari MySQL: <?php echo htmlspecialchars($pesan_db); ?></p>
    <?php } ?>

    <p><a href="javascript:history.back()">Kembali dan perbaiki data</a></p>
</body>
</html>