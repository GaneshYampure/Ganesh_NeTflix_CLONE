<?php
include 'dtata.php';

$uploadMessage = '';

if (isset($_FILES['movie'])) {
    $target = 'uploads/' . basename($_FILES['movie']['name']);
    $target2 = 'uploads/' . basename($_FILES['poster']['name']);

    if (move_uploaded_file($_FILES['movie']['tmp_name'], $target) && move_uploaded_file($_FILES['poster']['tmp_name'], $target2)) {
        $movie_title = mysqli_real_escape_string($con, $_POST['name']);
        $movie_description = mysqli_real_escape_string($con, $_POST['description']);
        $run_time = mysqli_real_escape_string($con, $_POST['runtime']);
        $category = mysqli_real_escape_string($con, $_POST['category']);
        $movie_image = mysqli_real_escape_string($con, $_FILES['poster']['name']);
        $Movie = mysqli_real_escape_string($con, $_FILES['movie']['name']);

        $insertq = "INSERT INTO `content`(`name`, `description`, `category`, `runtime`, `postername`, `movie`) 
                    VALUES ('$movie_title', '$movie_description', '$category', '$run_time', '$Movie', '$movie_image')";
        mysqli_query($con, $insertq);
        $uploadMessage = "Movie uploaded successfully!";
    } else {
        $uploadMessage = "Failed to upload files.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Upload</title>
    <style>
        body {
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .upload-container {
            background-color: #ffffffdd;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            color: #1f4037;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-top: 5px;
            font-size: 1em;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        input[type="submit"] {
            background-color: #1f4037;
            color: #fff;
            border: none;
            padding: 12px;
            margin-top: 20px;
            border-radius: 15px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #145a32;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h1>Upload Movie</h1>
        <form action="newupload.php" method="post" enctype="multipart/form-data">
            <label for="name">Movie Name:</label>
            <input type="text" name="name" placeholder="Enter Movie Name" required>

            <label for="description">Description:</label>
            <textarea name="description" placeholder="Enter Description" required></textarea>

            <label for="category">Category:</label>
            <input type="text" name="category" placeholder="e.g. Action, Drama" required>

            <label for="runtime">Runtime:</label>
            <input type="text" name="runtime" placeholder="e.g. 2h 15min" required>

            <label for="poster">Upload Poster:</label>
            <input type="file" name="poster" accept="image/*" required>

            <label for="movie">Upload Movie:</label>
            <input type="file" name="movie" accept="video/*" required>

            <input type="submit" name="submit" value="Upload">
        </form>
        <?php if ($uploadMessage): ?>
            <div class="message"><?= $uploadMessage ?></div>
        <?php endif; ?>
    </div>
</body>
</html>