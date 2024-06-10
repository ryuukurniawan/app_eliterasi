<?php
include 'koneksi.php';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $cover = $_FILES['cover']['name'];
    $display = $_FILES['display']['name'];
    $user_id = $_POST['user_id'];
    $genre = $_POST['genre'];
    $kategori = $_POST['kategori'];
    $keterangan = $_POST['keterangan'];
    $ringkasan = $_POST['ringkasan'];
    $sinopsis = $_POST['sinopsis'];

    if ($cover) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($cover);
        move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file);
    } else {
        $cover = null; // Set default cover if not uploaded
    }
    if ($display) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($display);
        move_uploaded_file($_FILES["display"]["tmp_name"], $target_file);
    } else {
        $display = null; // Set default cover if not uploaded
    }

    $sql = "INSERT INTO buku (Judul, Cover, Display, User_ID, Genre, Kategori, Keterangan, Ringkasan, Sinopsis) VALUES ('$judul', '$cover', '$display', '$user_id', '$genre', '$kategori', '$keterangan', '$ringkasan', '$sinopsis')";

    if ($conn->query($sql) === TRUE) {
        header('Location: manajemen.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];
    $sql = "DELETE FROM buku WHERE Book_ID='$book_id'";
    if ($conn->query($sql) === TRUE) {
        header('Location: manajemen.php');
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch Books with Usernames
$sql = "SELECT buku.*, pengguna.Username FROM buku INNER JOIN pengguna ON buku.User_ID = pengguna.User_ID";
$result = $conn->query($sql);
$books = $result->fetch_all(MYSQLI_ASSOC);

// Fetch Users for Dropdown
$sql_users = "SELECT * FROM pengguna";
$result_users = $conn->query($sql_users);
$users = $result_users->fetch_all(MYSQLI_ASSOC);

// Convert to JSON
$json_books = json_encode($books);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Buku</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">E-literasi Dasboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="manajemen.php">Karya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ulasan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav><br>

    <div class="container justify-content-center align-items-center">
        <h3>Manajemen Karya</h3><br>
        <div class="card border-dark" style="max-width: 50rem;">
            <div class="card-header bg-transparent border-dark">Input Data Karya</div>
            <div class="card-body text-dark">
                <form action="manajemen.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul:</label>
                        <input type="text" class="form-control" name="judul" id="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover :</label>
                        <input class="form-control" type="file" id="cover" name="cover">
                    </div>
                    <div class="mb-3">
                        <label for="display" class="form-label">Display :</label>
                        <input class="form-control" type="file" id="display" name="display">
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User ID:</label><br>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['User_ID']; ?>"><?php echo $user['Username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre:</label><br>
                        <select name="genre" id="genre" class="form-select" required>
                                <option value="horror">horror</option>
                                <option value="aksi">aksi</option>
                                <option value="komedi">komedi</option>
                                <option value="romantis">romantis</option>
                                <option value="drama">drama</option>
                                <option value="fantasi">fantasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori:</label>
                        <select name="kategori" id="kategori" class="form-select" required>
                                <option value="komik">komik</option>
                                <option value="novel">novel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan:</label><br>
                        <select name="keterangan" id="keterangan" class="form-select" required>
                                <option value="normal">normal</option>
                                <option value="hits">hits</option>
                                <option value="populer">populer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ringkasan" class="form-label">Ringkasan</label>
                        <textarea class="form-control" id="ringkasan" name="ringkasan" required rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sinopsis" class="form-label">Sinopsis</label>
                        <textarea class="form-control" id="sinopsis" name="sinopsis" required rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-info">Simpan</button>
                </form>
            </div>
        </div><br>

        <h3>Daftar Karya</h3>
        <table class="table table-striped" style="max-width: 70rem;table-layout: fixed;word-wrap: break-word;">
            <tr>
                <th>Book ID</th>
                <th>Judul</th>
                <th>Cover</th>
                <th>Display</th>
                <th>User</th>
                <th>Genre</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Ringkasan</th>
                <th>Sinopsis</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?php echo $book['Book_ID']; ?></td>
                <td><?php echo $book['Judul']; ?></td>
                <td><img src="uploads/<?php echo $book['Cover']; ?>" width="70"></td>
                <td><img src="uploads/<?php echo $book['Display']; ?>" width="70"></td>
                <td><?php echo $book['Username']; ?></td>
                <td><?php echo $book['Genre']; ?></td>
                <td><?php echo $book['Kategori']; ?></td>
                <td><?php echo $book['Keterangan']; ?></td>
                <td><?php echo $book['Ringkasan']; ?></td>
                <td><?php echo $book['Sinopsis']; ?></td>
                <td>
                    <a class="btn btn-outline-success" href="update.php?edit=<?php echo $book['Book_ID']; ?>">Edit</a><br><br>
                    <a class="btn btn-outline-danger" href="manajemen.php?delete=<?php echo $book['Book_ID']; ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Data Karya dalam Format JSON</h3>
        <pre><?php echo $json_books; ?></pre>
    </div>
</body>
</html>
