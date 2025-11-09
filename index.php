

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Netflix Home Clone</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #141414;
            color: #fff;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            z-index: 1000;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: #E50914;
        }

        nav a {
            margin: 0 20px;
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #E50914;
        }

        .hero {
            position: relative;
            height: 80vh;
            background: url('uploads/MV5BMjg2NmM0MTEtYWY2Yy00NmFlLTllNTMtMjVkZjEwMGVlNzdjXkEyXkFqcGc@._V1_.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 60px;
            margin-top: 80px;
        }

        .hero::after {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(to top, #141414 10%, transparent 90%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 500px;
        }

        .hero-content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        .btn {
            background-color: #E50914;
            color: #fff;
            padding: 10px 25px;
            border: none;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #b20710;
        }

        .section {
            padding: 40px 60px;
        }

        .section h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .row {
            display: flex;
            overflow-x: auto;
            padding-bottom: 15px;
        }

        .row::-webkit-scrollbar {
            display: none;
        }

        .card {
            flex: 0 0 auto;
            width: 200px;
            margin-right: 15px;
            transition: transform 0.3s;
        }

        .card img {
            width: 100%;
            border-radius: 5px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.1);
        }

        .card h3 {
            margin-top: 10px;
            font-size: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 20px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .section {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">NETFLIX</div>
        <nav>
            <a href="#">Home</a>
            <a href="#">TV Shows</a>
            <a href="#">Movies</a>
            <a href="#">My List</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Stranger Things</h1>
            <p>Experience the upside-down. A thrilling Netflix original with supernatural adventure and mystery.</p>
            <button class="btn">Play</button>
        </div>
    </section>

    <section class="section">
        <h2>New Releases</h2>
        <div class="row">
            <?php
                include 'dtata.php';
                $moviename = null;
                $psotername = null;
                $result = mysqli_query($con, "SELECT * FROM content ORDER BY RAND()");
                while ($row = mysqli_fetch_assoc($result)) {
                    $moviename = $row['name'];
                    $postername = $row['movie'];
                ?>
            <div class="card">
                <a href="info.php?id=<?php echo $row['id'];  ?>"><img src="uploads/<?php echo $postername ?>" alt="YODHA"></a>
                <h3><?php echo $moviename ?></h3>
            </div>
            <?php
                }
            ?>
        </div>
    </section>

    <section class="section">
        <h2>Popular Movies</h2>
        <div class="row">
            <?php
                include 'dtata.php';
                $moviename = null;
                $psotername = null;
                $result = mysqli_query($con, "SELECT * FROM content ORDER BY RAND()");
                while ($row = mysqli_fetch_assoc($result)) {
                    $moviename = $row['name'];
                    $postername = $row['movie'];
                ?>
             <div class="card">
                <a href="info.php"><img src="uploads/<?php echo $postername ?>" alt="YODHA"></a>
                <h3><?php echo $moviename ?></h3>
            </div>
            <?php
                }
            ?>
        </div>
    </section>

</body>
</html>
