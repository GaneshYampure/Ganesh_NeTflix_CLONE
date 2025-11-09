<?php
include 'dtata.php';

if (!isset($_GET['id'])) {
    die("No movie ID provided.");
}

$id = mysqli_real_escape_string($con, $_GET['id']);
$query = "SELECT * FROM content WHERE id = '$id'";
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
    <title><?php echo htmlspecialchars($movie['name']); ?> - Teflix</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            overflow: hidden; /* Prevent scroll */
            font-family: 'Arial', sans-serif;
            background-color: #141414;
            color: #fff;
        }

        .container {
            display: flex;
            flex-direction: row;
            padding: 40px;
            gap: 40px;
            align-items: center;
            height: 100vh; /* Full screen height */
        }

        .poster {
            flex: 1;
        }

        .poster img {
            width: 100%;
            max-width: 450px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255,255,255,0.1);
        }

        .details {
            flex: 2;
            overflow: hidden;
        }

        .details h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .details .category,
        .details .runtime {
            font-size: 16px;
            color: #b3b3b3;
        }

        .description {
            margin-top: 20px;
            font-size: 18px;
            line-height: 1.5;
            color: #e5e5e5;
            max-height: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .play-button {
            margin-top: 30px;
            width: 70px;
            height: 70px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
        }

        .play-button:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .play-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-40%, -50%);
            width: 0;
            height: 0;
            border-top: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-left: 25px solid white;
        }

        @media (max-width: 768px) {
            html, body {
                overflow: auto; /* Allow scroll on small screens */
            }

            .container {
                flex-direction: column;
                padding: 20px;
                height: auto;
                gap: 20px;
            }

            .details h1 {
                font-size: 32px;
            }

            .description {
                max-height: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="poster">
        <img src="uploads/<?php echo htmlspecialchars($movie['movie']); ?>" alt="<?php echo htmlspecialchars($movie['name']); ?>">
    </div>
    <div class="details">
        <h1><?php echo htmlspecialchars($movie['name']); ?></h1>
        <div class="category"><?php echo htmlspecialchars($movie['category']); ?></div>
        <div class="runtime"><?php echo htmlspecialchars($movie['runtime']); ?></div>

        <div class="description">
            <?php echo nl2br(htmlspecialchars($movie['description'])); ?>
        </div>

        <a href="video.php?id=<?php echo $movie['postername'] ?>"><div class="play-button" onclick="playMovie()"></div></a>
    </div>
</div>


</body>
</html>
