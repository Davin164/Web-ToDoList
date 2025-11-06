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
    <style>
        body {
    background-color: #f4f6f9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 25px;
}

/* Box form tambah tugas */
.tambah-form {
    background-color: #ffffff;
    width: 580px;
    max-width: 95%;
    padding: 20px;
    margin: 0 auto 30px auto;
    border-radius: 10px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
}

.tambah-form input, 
.tambah-form select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    font-size: 14px;
}

.tambah-form input:focus, 
.tambah-form select:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 5px rgba(74,144,226,0.5);
}

button[name="tambah"] {
    padding: 10px 18px;
    border: none;
    background-color: #28a745;
    color: white;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

button[name="tambah"]:hover {
    background-color: #218838;
}

/* Card ToDo */
.todo-item {
    background-color: white;
    width: 550px;
    max-width: 95%;
    padding: 20px;
    margin: 20px auto;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-left: 6px solid #ccc;
    transition: transform 0.2s ease-in-out;
}

.todo-item:hover {
    transform: translateY(-3px);
}

/* Efek selesai */
.todo-item.selesai {
    text-decoration: line-through;
    opacity: 0.5;
}

/* Warna prioritas */
.tinggi { border-left-color: #e74c3c; }   /* merah */
.sedang { border-left-color: #f1c40f; }  /* kuning */
.rendah { border-left-color: #2ecc71; }  /* hijau */

/* Tombol */
.todo-item button {
    padding: 7px 14px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: 0.3s;
    margin-right: 6px;
}

/* Hapus */
button[name="hapus"] {
    background-color: #e74c3c;
    color: white;
}
button[name="hapus"]:hover { background-color: #c0392b; }

/* Status (selesai) */
button[name="Status"] {
    background-color: #3498db;
    color: white;
}
button[name="Status"]:hover { background-color: #2980b9; }

/* Edit */
button[name="edit"] {
    background-color: #2ecc71;
    color: white;
}
button[name="edit"]:hover { background-color: #27ae60; }

    </style>
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