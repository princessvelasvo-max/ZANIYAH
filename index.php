
<?php
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palengke Kiosk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fff5f2;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #ffffff;
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .navbar-brand,
        .page-title {
            color: #b52b35;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 3px 10px rgba(0,0,0,0.10);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .product-img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            padding: 15px;
            background-color: white;
        }

        .btn-order {
            background-color: #b52b35;
            color: white;
        }

        .btn-order:hover {
            background-color: #8e2029;
            color: white;
        }

        footer {
            background-color: #b52b35;
            color: white;
            padding: 20px;
            margin-top: 60px;
        }
    </style>
</head>

<body>

<!-- NAVIGATION BAR -->
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="images/logo.png" alt="Palengke Kiosk Logo">
            <span class="ms-2">Palengke Kiosk</span>
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link active" href="#">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">About Us</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact Us</a>
                </li>

            </ul>
        </div>

    </div>
</nav>


<!-- MAIN CONTENT -->
<div class="container mt-5">

    <div class="text-center mb-4">
        <h1 class="page-title">Welcome to Palengke Kiosk!</h1>
        <p class="text-muted">Fresh products at affordable prices.</p>
    </div>

    <h3 class="mb-4">Our Products</h3>

    <div class="row">

        <?php
        $sql = "SELECT * FROM fruitsandveggies_tbl";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                // Preserve database records; skip CSV header row
                if (strtolower(trim($row['Fruit'])) === 'fruit') {
                    continue;
                }

                $imageFile = trim($row['Image']);

                if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $imageFile)) {
                    $imageFile .= ".png";
                }

                $imagePath = "images/" . $imageFile;

                $productId = (int)$row['Product'];
                $fruit = $row['Fruit'];
                $price = (float)$row['Price'];

                $modalId = "productModal" . $productId;
        ?>

        <!-- PRODUCT CARD -->
        <div class="col-12 col-sm-6 col-lg-3 mb-4">

            <div class="card h-100">

                <img
                    src="<?= htmlspecialchars($imagePath) ?>"
                    class="product-img"
                    alt="<?= htmlspecialchars($fruit) ?>"
                    onerror="this.onerror=null;this.src='images/no-image.png';"
                >

                <div class="card-body text-center">

                    <h5 class="card-title">
                        <?= htmlspecialchars($fruit) ?>
                    </h5>

                    <h5 class="text-danger">
                        ₱<?= number_format($price, 2) ?>
                    </h5>

                    <button
                        type="button"
                        class="btn btn-order w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#<?= $modalId ?>">

                        Add to Cart

                    </button>

                </div>
            </div>

        </div>


        <!-- PRODUCT ORDER FORM MODAL -->
        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">

                        <h5 class="modal-title">Order Product</h5>

                        <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="text-center mb-3">

                            <img
                                src="<?= htmlspecialchars($imagePath) ?>"
                                alt="<?= htmlspecialchars($fruit) ?>"
                                style="width:150px;height:130px;object-fit:contain;"
                                onerror="this.onerror=null;this.src='images/no-image.png';"
                            >

                            <h5>
                                <?= htmlspecialchars($fruit) ?>
                            </h5>

                            <h5 class="text-danger">
                                ₱<?= number_format($price, 2) ?>
                            </h5>

                        </div>

                        <form action="insert.php" method="POST">

                            <input type="hidden"
                                name="product_id"
                                value="<?= $productId ?>">

                            <div class="mb-3">
                                <label class="form-label">Quantity</label>

                                <input
                                    type="number"
                                    name="quantity"
                                    class="form-control"
                                    min="1"
                                    value="1"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Buyer's Name</label>

                                <input
                                    type="text"
                                    name="buyer"
                                    class="form-control"
                                    placeholder="Enter your name"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>

                                <input
                                    type="text"
                                    name="contact"
                                    class="form-control"
                                    placeholder="Enter your mobile number"
                                    required
                                >
                            </div>

                            <button type="submit"
                                class="btn btn-order w-100">

                                Submit Order

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <?php
            }

        } else {

            echo "<p class='text-center'>No products available.</p>";

        }
        ?>

    </div>

</div>


<!-- ABOUT US -->
<section id="about" class="container mt-5 text-center">

    <h2 class="page-title">About Us</h2>

    <p>
        Palengke Kiosk is your local marketplace where
        convenience meets community. We bring fresh and
        affordable goods straight from the market to your fingertips.
    </p>

</section>


<!-- CONTACT US -->
<section id="contact" class="container mt-5 text-center">

    <h2 class="page-title">Contact Us</h2>

    <p>Have questions or feedback? We'd love to hear from you!</p>

    <p>Email: contact@palengkekiosk.com</p>

    <p>Phone: +63 912 345 6789</p>

</section>


<!-- FOOTER -->
<footer class="text-center">

    <p class="mb-1">
        &copy; <?= date("Y") ?> Palengke Kiosk.
        All Rights Reserved.
    </p>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
