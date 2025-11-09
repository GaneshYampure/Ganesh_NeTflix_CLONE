<?php
include 'dtata.php';

if (!isset($_GET['id'])) {
    die("No movie ID provided.");
}

$id = mysqli_real_escape_string($con, $_GET['id']);
$query = "SELECT * FROM content WHERE postername = '$id'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) === 0) {
    die("Movie not found.");
}

$movie = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch <?php echo htmlspecialchars($movie['name']); ?> - Teflix</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            width: 100%;
            background-color: #000;
            color: #fff;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #e50914; /* Netflix red */
        }

        video {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            background-color: #000;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 30px;
            background-color: rgba(229, 9, 20, 0.8);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .back-button:hover {
            background-color: #e50914;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 22px;
            }

            .back-button {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <button class="back-button" onclick="window.history.back()">← Back</button>

    <div class="container">
        <h1>Now Playing: <?php echo htmlspecialchars($movie['name']); ?></h1>
        <video controls autoplay>
            <source src="uploads/<?php echo htmlspecialchars($movie['postername']); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

</body>
</html>
