<?php
include 'koneksi.php';

if (!isset($_GET['edit'])) {
    header('Location: manajemen.php');
    exit();
}

$book_id = $_GET['edit'];

// Fetch the existing data for the selected book
$sql = "SELECT * FROM buku WHERE Book_ID='$book_id'";
$result = $conn->query($sql);
$book = $result->fetch_assoc();

// Fetch Users for Dropdown
$sql_users = "SELECT * FROM pengguna";
$result_users = $conn->query($sql_users);
$users = $result_users->fetch_all(MYSQLI_ASSOC);

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $cover = $_FILES['cover']['name'];
    $user_id = $_POST['user_id'];
    $genre = $_POST['genre'];
    $kategori = $_POST['kategori'];
    $sinopsis = $_POST['sinopsis'];

    if ($cover) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($cover);
        move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file);
    } else {
        // If no new cover is uploaded, keep the existing cover
        $cover = $book['Cover'];
    }

    $sql = "UPDATE buku SET Judul='$judul', Cover='$cover', User_ID='$user_id', Genre='$genre', Kategori='$kategori', Sinopsis='$sinopsis' WHERE Book_ID='$book_id'";

    if ($conn->query($sql) === TRUE) {
        header('Location: manajemen.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Karya</title>
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
        <h3>Update Karya</h3><br>
        <div class="card border-dark" style="max-width: 50rem;">
            <div class="card-header bg-transparent border-dark">Update Data Karya</div>
            <div class="card-body text-dark">
                <form action="update.php?edit=<?php echo $book['Book_ID']; ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul:</label>
                        <input type="text" class="form-control" name="judul" id="judul" value="<?php echo $book['Judul']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover:</label>
                        <input class="form-control" type="file" id="cover" name="cover">
                        <img src="uploads/<?php echo $book['Cover']; ?>" width="100">
                    </div>
                    <div class="mb-3">
                        <label for="user_id">User ID:</label><br>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['User_ID']; ?>" <?php if ($user['User_ID'] == $book['User_ID']) echo 'selected'; ?>>
                                    <?php echo $user['Username']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre:</label>
                        <input type="text" class="form-control" name="genre" id="genre" value="<?php echo $book['Genre']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori:</label>
                        <input type="text" class="form-control" name="kategori" id="kategori" value="<?php echo $book['Kategori']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="sinopsis" class="form-label">Sinopsis:</label>
                        <textarea class="form-control" id="sinopsis" name="sinopsis" required rows="3"><?php echo $book['Sinopsis']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-info">Update</button>
                    <a href="manajemen.php" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
