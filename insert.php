
<?php

include("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $productId = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];
    $buyer = trim($_POST["buyer"]);
    $contact = trim($_POST["contact"]);

    // Validate inputs
    if (
        $productId <= 0 ||
        $quantity <= 0 ||
        $buyer === "" ||
        $contact === ""
    ) {
        die("Please complete all required fields.");
    }

    // Get product price from database
    $stmt = $conn->prepare(
        "SELECT Price FROM fruitsandveggies_tbl WHERE Product = ?"
    );

    if (!$stmt) {
        die("Database error: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("i", $productId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Product not found.");
    }

    $product = $result->fetch_assoc();

    $price = (float)$product["Price"];

    // Calculate total
    $total = $price * $quantity;

    $stmt->close();

    // Insert order into orders_tbl
    $insert = $conn->prepare(
        "INSERT INTO orders_tbl
        (Productid, Qty, Total, User, Contact)
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$insert) {
        die("Database error: " . htmlspecialchars($conn->error));
    }

    $insert->bind_param(
        "iidss",
        $productId,
        $quantity,
        $total,
        $buyer,
        $contact
    );

    if ($insert->execute()) {

        $insert->close();
        $conn->close();

        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order Submitted</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>

        <body style='background-color:#fff5f2;'>

            <div class='container mt-5 text-center'>

                <div class='card p-5 shadow-sm'>

                    <h2 class='text-success'>
                        Order Submitted Successfully!
                    </h2>

                    <p>Your order has been saved to the database.</p>

                    <h5>Order Details</h5>

                    <p>Quantity: " . htmlspecialchars((string)$quantity) . "</p>

                    <p>Buyer's Name: " . htmlspecialchars($buyer) . "</p>

                    <p>Mobile Number: " . htmlspecialchars($contact) . "</p>

                    <p>Total: ₱" . number_format($total, 2) . "</p>

                    <a href='index.php' class='btn btn-danger mt-3'>
                        Back to Home
                    </a>

                </div>

            </div>

        </body>
        </html>
        ";

    } else {

        echo "Error saving order: " . htmlspecialchars($insert->error);

    }

    $insert->close();
    $conn->close();

} else {

    header("Location: index.php");
    exit;

}

?>
