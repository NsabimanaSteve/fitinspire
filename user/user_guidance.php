<?php
session_start();
require_once '../db_connect.php'; // Adjusted path for project structure

$user_id = $_SESSION['user_id'];
$membership_type = $_SESSION['membership_type'];

if ($membership_type == 1 || $membership_type == 2) {
    header('location: user_memberships.php'); // Redirect to upgrade membership page if membership is not high enough for feedback
    exit();
} else {
    include "user_navbar.php";
}
// Fetch the guidance entries for this user
$sql_guidance = "SELECT g.nutrition_advice, g.workouts, g.techniques 
                 FROM guidance g
                 WHERE g.user_id = ?";

$stmt_guidance = $conn->prepare($sql_guidance);
$stmt_guidance->bind_param("i", $user_id);
$stmt_guidance->execute();
$guidance_result = $stmt_guidance->get_result();

?>

<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <title>Fitness Guidance</title>
</head>

<body>
    <div class="container">

        <h1>Your Trainer Left You Some Tips</h1>

        <div class="cards">
            <?php
                // Fetch guidance entries if available
                if ($guidance_result->num_rows > 0) {
                    // Loop through all the entries and display them dynamically
                    while ($row = $guidance_result->fetch_assoc()) {
            ?>
            <div class="card">
                <h2>Nutrition Advice</h2>
                <h1><i class="fas fa-utensils"></i></h1>
                <ul>
                    <?php
                        // Split the nutrition advice by commas if they are provided as a list
                        $nutrition_advice = explode(",", $row['nutrition_advice']);
                        foreach ($nutrition_advice as $advice) {
                            echo "<li>" . htmlspecialchars(trim($advice)) . "</li>";
                        }
                    ?>
                </ul>
            </div>

            <div class="card">
                <h2>Workouts</h2>
                <h1><i class="fas fa-dumbbell"></i></h1>
                <ul>
                    <?php
                        // Split the workouts by commas if they are provided as a list
                        $workouts = explode(",", $row['workouts']);
                        foreach ($workouts as $workout) {
                            echo "<li>" . htmlspecialchars(trim($workout)) . "</li>";
                        }
                    ?>
                </ul>
            </div>

            <div class="card">
                <h2>Techniques</h2>
                <h1><i class="fas fa-cogs"></i></h1>
                <ul>
                    <?php
                        // Split the techniques by commas if they are provided as a list
                        $techniques = explode(",", $row['techniques']);
                        foreach ($techniques as $technique) {
                            echo "<li>" . htmlspecialchars(trim($technique)) . "</li>";
                        }
                    ?>
                </ul>
            </div>

            <?php
                    }
                } else {
                    echo "<p>No guidance available from your trainer.</p>";
                }
            ?>

        </div>

    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Lato', sans-serif;
            background-color: #ffffff;
            color: #302929;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 3.5rem;
            font-size: 2.4rem;
            word-spacing: 0.3rem;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 1rem;
        }

        .card {
            background-color: #dadee4;
            padding: 1rem 2rem;
            text-align: center;
            position: relative;
            border-radius: 10px;
        }

        .card span {
            font-size: 1.1rem;
        }

        .card h2,
        .card h1 {
            text-align: center;
        }

        .card ul {
            margin-top: 3rem;
            list-style: none;
            text-align: left;
            margin-bottom: 7rem;
        }

        .card ul li {
            line-height: 2.5;
        }

        .card ul li::before {
            content: "\2022";
            color: #BC1E4A;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .card .btn {
            font-size: 1rem;
            border: none;
            background-color: transparent;
            color: #fff;
            border: 1px solid #BC1E4A;
            padding: 0.8rem 2rem;
            position: absolute;
            bottom: 0;
            left: 6rem;
            margin-bottom: 2rem;
            outline: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .card .btn:hover {
            background-color: #BC1E4A;
            transition: all 0.3s ease;
        }

        @media(max-width: 1000px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
                grid-gap: 1rem;
            }
        }

        @media(max-width: 675px) {
            .cards {
                grid-template-columns: 1fr;
                grid-gap: 1rem;
            }
        }

        .testimonials {
        margin-top: 3rem;
        text-align: center;
        font-style: italic;
        color: #555;
    }
    </style>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

</body>
</html>
