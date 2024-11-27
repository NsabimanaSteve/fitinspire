<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');  // Redirect if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$membership_type = $_SESSION['membership_type'];

// Include database connection (Assuming you have this)
include "../db_connect.php";  // Replace with your actual DB connection code

// Fetch membership types from the database
$query = "SELECT * FROM membershiptype";
$result = mysqli_query($conn, $query);

// Fetch the current user's membership type from the database
$current_membership_query = "SELECT membershiptype_id FROM users WHERE user_id = ?";
$stmt = $conn->prepare($current_membership_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$current_membership_result = $stmt->get_result();
$current_membership_row = $current_membership_result->fetch_assoc();
$current_membership_id = $current_membership_row['membershiptype_id'];

// Handle membership update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['membership_id'])) {
    $new_membership_id = $_POST['membership_id'];

    // If the user selects a different membership, update the user's membership
    if ($new_membership_id != $current_membership_id) {
        // Update the user's membership type
        $update_query = "UPDATE users SET membershiptype_id = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('ii', $new_membership_id, $user_id);
        $stmt->execute();

        // Record the membership change in the memberships table
        $insert_membership_query = "INSERT INTO memberships (user_id, membershiptype_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insert_membership_query);
        $stmt->bind_param('ii', $user_id, $new_membership_id);
        $stmt->execute();

        // Update the session
        $_SESSION['membership_type'] = $new_membership_id;

        // Refresh the page to reflect changes
        header("Location: user_memberships.php");
        exit();
    }
}

include "user_navbar.php";
?>

<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <title>Pricing Page | Fit Inspire Hub</title>
</head>

<body>
    <div class="container">
        <h1>Choose a Membership Plan</h1>

        <div class="cards">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="card">
                    <h2><?php echo $row['name']; ?></h2>
                    <h1>
                        <i class="fas fa-dollar-sign"></i>
                        <?php echo $row['membershiptype_price']; ?>
                        <?php if ($row['membershiptype_duration'] != "In perpetuity") { ?>
                            <span>/month</span>
                        <?php } ?>
                    </h1>
                    <ul>
                        <li><?php echo $row['description']; ?></li>
                    </ul>

                    <?php if ($row['membershiptype_id'] == $current_membership_id) { ?>
                        <button class="btn disabled">Current Plan</button>
                    <?php } else { ?>
                        <form method="POST" action="user_memberships.php">
                            <input type="hidden" name="membership_id" value="<?php echo $row['membershiptype_id']; ?>">
                            <button class="btn">Choose Plan</button>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="testimonials">
            <h2>What Our Members Say</h2>
            <p>"Joining Fit Inspire Hub changed my life! I lost 10kg in 3 months with the Premium plan." - Jane Doe</p>
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

        .card .btn.disabled {
            background-color: #ddd;
            color: #777;
            cursor: not-allowed;
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
