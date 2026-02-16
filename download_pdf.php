<?php
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: download_pdf.php
    DATE FINISHED: 04-20-2025
    PURPOSE: Generates a detailed PDF receipt for a specific order 
    when the order ID is provided via a `POST` request. The receipt includes customer details, 
    order information, and a table of ordered products with their quantities, unit prices, 
    and total cost, followed by the overall total. The PDF is dynamically created and offered 
    for download as `Receipt_Order_[order_id].pdf`.
*/

require_once 'connect.php';
require('FPDF/fpdf.php'); // Ensure the correct path to FPDF

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Fetch order data from database
    $stmt = $pdo->prepare("SELECT * FROM customer_order WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Order not found.");
    }

    // Setup PDF
    try {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Courier', 'B', 16);
        $pdf->Cell(0, 10, 'Hardware Store Receipt', 0, 1, 'C');
        $pdf->SetFont('Courier', '', 12);
        $pdf->Ln(5);

        // Order Info
        $pdf->Cell(0, 10, "Order ID: #" . $order_id, 0, 1);
        $pdf->Cell(0, 8, "Customer: " . $order['fullname'], 0, 1);
        $pdf->Cell(0, 8, "Address: " . $order['address'], 0, 1);
        $pdf->Cell(0, 8, "Phone: " . $order['phone'], 0, 1);
        $pdf->Cell(0, 8, "Payment: " . $order['payment_method'], 0, 1);
        $pdf->Cell(0, 8, "Date: " . $order['order_date'], 0, 1);
        $pdf->Ln(5);

        // Table Header
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->Cell(60, 8, 'Product', 1);
        $pdf->Cell(30, 8, 'Price', 1);
        $pdf->Cell(30, 8, 'Qty', 1);
        $pdf->Cell(40, 8, 'Total', 1);
        $pdf->Ln();

        // Table Body
        $pdf->SetFont('Courier', '', 12);
        $total = 0;
        $items = explode(", ", $order['order_items']);

        foreach ($items as $item) {
            $parts = explode(" (x", $item);
            $name = $parts[0];
            $qty = rtrim($parts[1], ")");
            $price = 100; // Example static price
            $item_total = $price * $qty;
            $total += $item_total;

            $pdf->Cell(60, 8, $name, 1);
            $pdf->Cell(30, 8, '₱' . $price, 1);
            $pdf->Cell(30, 8, $qty, 1);
            $pdf->Cell(40, 8, '₱' . $item_total, 1);
            $pdf->Ln();
        }

        // Total Price
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->Cell(120, 10, 'Total', 1);
        $pdf->Cell(40, 10, '₱' . $total, 1);
        $pdf->Ln(10);

        // Output PDF
        $pdf->Output('D', "Receipt_Order_{$order_id}.pdf"); // 'D' triggers download
        exit;
    } catch (Exception $e) {
        die("Error generating PDF: " . $e->getMessage());
    }
} else {
    echo "Invalid request.";
}
?>




