<?php
//memulai session php
session_start();

//menginisialisasi session
if(!isset($_SESSION['todos'])){
    $_SESSION['todos'] = [];
}

//fungsi untuk menambahkan data
if(isset($_POST['tambah'])){
    $newData = [
        'nama' => $_POST['nama'],
        'status' => 'belum selesai',
        'prioritas' => $_POST['prioritas'],
        'tanggal' => $_POST['tanggal'],
    ];
    array_push($_SESSION['todos'], $newData);
}

//fungsi untuk mengubah status
if(isset($_POST['Status'])){
    $index = $_POST['index'];
    $_SESSION['todos'][$index]['status'] =
    $_SESSION['todos'][$index]['status'] == 'selesai' ? 'belum selesai' : 'selesai';
}

//fungsi untuk menghapus data
if(isset($_POST['hapus'])){
    $index = $_POST['index'];
    array_splice($_SESSION['todos'], $index, 1);
}

//fungsi untuk mengupdate data
if(isset($_POST['update'])){
    $index = $_POST['edit-index'];
    $_SESSION['todos'][$index] = [
        'nama' => $_POST['edit-nama'],
        'prioritas' => $_POST['edit-prioritas'],
        'tanggal' => $_POST['edit-tanggal'],
        'status' => $_SESSION['todos'][$index]['status']
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo-List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>ToDo List</h1>
    <!-- Form untuk menambahkan data -->
    <form method="POST" class="tambah-form">
        <input type="text" name="nama" placeholder="Nama Tugas" required>
        <select name="prioritas" required>
            <option value="">Tingkat Prioritas</option>
            <option value="tinggi">Tinggi</option>
            <option value="sedang">Sedang</option>
            <option value="rendah">Rendah</option>
        </select>
        <input type="date" name="tanggal" min="<?php echo date('Y-m-d'); ?>" required>
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <!-- Untuk memulai Foreach -->
    <?php foreach($_SESSION['todos'] as $index => $data): ?>
    <!-- Untuk class style CSS -->
    <div class="todo-item <?php echo $data['status'] == 'selesai' ? 'selesai' : ''; ?> <?php echo $data['prioritas']; ?>">
        <div>
            <!-- Untuk menampilkan data dari array session todos -->
            <strong><?php echo htmlspecialchars($data['nama']); ?></strong>
            <br>
            Prioritas: <?php echo htmlspecialchars($data['prioritas']); ?>
            <br>
            <!-- date dan strtotime berfungsi untuk membuat tanggal yang awalnya tanggal dalam bahasa inggris menjadi custom -->
            Tanggal: <?php echo htmlspecialchars(date('d/m/y', strtotime($data['tanggal']))); ?>
            <br>
            Status: <?php echo htmlspecialchars($data['status']); ?>
            <br>

        <!-- Ini Adalah FORM Untuk Mengedit Data -->
        <div class="edit-form" id="editForm-<?php echo $index; ?>" style="display: none;">
        <form method="POST">
            <input type="hidden" name="edit-index" value="<?php echo $index; ?>">
            <input type="text" name="edit-nama" value="<?php echo htmlspecialchars($data['nama']) ?>" required>
            <select name="edit-prioritas" required>
                <option value="">Tingkat Prioritas</option>
                <option value="tinggi" <?php echo $data['prioritas'] == 'tinggi' ? 'selected' : '' ?>>Tinggi</option>
                <option value="sedang" <?php echo $data['prioritas'] == 'sedang' ? 'selected' : '' ?>>Sedang</option>
                <option value="rendah" <?php echo $data['prioritas'] == 'rendah' ? 'selected' : '' ?>>Rendah</option>
            </select>
            <input type="date" name="edit-tanggal" min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($data['tanggal']) ?>" required>
            <button type="submit" name="update">Simpan</button>
            <button type="button" onclick="untukEditForm(<?php echo $index; ?>)">Batal</button>
        </form>
        </div>

        <!-- Ini Adalah Aksi JavaScript -->
        <script>
            // Ini berfungsi sebagai alert ketika ingin menghapus data
            function konfirmasiHapus(namaTugas){
                return confirm(`Apakah anda yakin untuk menghapus "${namaTugas}" ?`);
            }
            // Ini berfungsi sebagai menampilkan dan menyembunyikan form edit 
            function untukEditForm(index){
                const editForm = document.getElementById(`editForm-${index}`);
                if(editForm){
                    editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
                }
            }
        </script>

        <!-- Ini Button Edit -->
        <?php if($data['status'] != 'selesai'): ?>
        <button type="button" name="edit" onclick="untukEditForm(<?php echo $index; ?>)">Edit</button>
        <?php endif; ?>
        <!-- Ini Button Status -->
        <form method="POST">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <button type="submit" name="Status">
                <?php echo $data['status'] == 'selesai' ? 'belum selesai' : 'selesai'; ?>
            </button>
        </form>
        <!-- Ini Button Hapus -->
        <form method="POST" onsubmit="return konfirmasiHapus('<?php echo htmlspecialchars($data['nama']); ?>')">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <button type="submit" name="hapus">Hapus</button>
        </form>
        </div>
    </div>

    <!-- untuk mengakhiri perulangan foreach -->
    <?php endforeach; ?>

</body>
</html>