<?php

require_once "koneksi.php";

$sql = "SELECT * FROM anggota";
$hasil = mysqli_query($koneksi, $sql);
$jumlah = mysqli_num_rows($hasil);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Anggota</title>
</head>
<body>
    <h1>Data Anggota</h1>

    <table border = 1>
        <tr>
            <th>No</th>
            <th>No Anggota</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Jenis Anggota</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>

        <?php $no = 1; while ($baris = mysqli_fetch_assoc($hasil)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($baris["no_anggota"]); ?></td>
            <td><?php echo htmlspecialchars($baris["nama_lengkap"]); ?></td>
            <td><?php echo htmlspecialchars($baris["email"]); ?></td>
            <td><?php echo htmlspecialchars($baris["no_hp"]); ?></td>
            <td><?php echo htmlspecialchars($baris["jenis_anggota"]); ?></td>
            <td><?php echo htmlspecialchars($baris["alamat"]); ?></td>
            <td>
                <a href="edit_anggota.php?id=<?= $baris["id"]; ?>">Edit</a>
                <a href="detail_anggota.php?id=<?php echo (int) $baris["id"]; ?>">Detail</a>
                <form class="inline" action="hapus_anggota.php" mrthod="post"
                    onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                <input type="hidden" name="id" value="<?php echo (int) $baris["id"]; ?>">
                <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>