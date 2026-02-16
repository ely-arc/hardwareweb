<?php
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: reportdownload_pdf.php
    DATE FINISHED: 04-20-2025
    PURPOSE: This code generates a PDF report of all customer orders, 
    displaying details such as order ID, customer name, address, phone number, 
    items, payment method, total price, and order date.
*/

require('fpdf/fpdf.php');
require_once 'connect.php'; // your PDO database connection

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get all orders
$stmt = $pdo->query("SELECT * FROM customer_order ORDER BY order_date ASC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Order History Report', 0, 1, 'C');
$pdf->Ln(5);

// Column headers
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(0, 123, 255);
$pdf->SetTextColor(255);
$headers = ['Order ID', 'Full Name', 'Address', 'Phone', 'Items', 'Payment', 'Total', 'Date'];

foreach ($headers as $col) {
    $pdf->Cell(24, 10, $col, 1, 0, 'C', true);
}
$pdf->Ln();

// Data rows
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0);

foreach ($orders as $order) {
    $pdf->Cell(24, 10, $order['id'], 1);
    $pdf->Cell(24, 10, $order['fullname'], 1);
    $pdf->Cell(24, 10, substr($order['address'], 0, 10) . '...', 1); // Trim long text
    $pdf->Cell(24, 10, $order['phone'], 1);
    $pdf->Cell(24, 10, substr($order['order_items'], 0, 10) . '...', 1);
    $pdf->Cell(24, 10, $order['payment_method'], 1);
    $pdf->Cell(24, 10, '₱' . number_format($order['total_price'], 2), 1);
    $pdf->Cell(24, 10, $order['order_date'], 1);
    $pdf->Ln();
}

// Output
$pdf->Output('D', 'order_report.pdf'); // D = force download
exit;
?>