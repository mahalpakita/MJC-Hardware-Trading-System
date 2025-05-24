<?php

require_once '../vendor/autoload.php'; // Use Composer autoload
session_start();
include '../includes/connection.php'; 

$query = 'SELECT *, FIRST_NAME, LAST_NAME, PHONE_NUMBER, EMPLOYEE, ROLE, DISCOUNT
FROM transaction T
JOIN customer C ON T.`CUST_ID`=C.`CUST_ID`
JOIN transaction_details tt ON tt.`TRANS_NO`=T.`TRANS_NO`
WHERE TRANS_ID ='.$_GET['id'];

$result = $db->query($query) or die($db->error);
$row = $result->fetch_assoc();

$fname = $row['FIRST_NAME'];
$lname = $row['LAST_NAME'];
$pn = $row['PHONE_NUMBER'];
$date = date('F d, Y h:i A', strtotime($row['DATE']));
$tid = $row['TRANS_NO'];
$cash = $row['CASH'];
$disco = $row['DISCOUNT'];
$sub = $row['SUBTOTAL'];
$less = $row['LESSVAT'];
$net = $row['NETVAT'];
$add = $row['ADDVAT'];
$grand = $row['GRANDTOTAL'];
$role = $row['EMPLOYEE'];
$roles = $row['ROLE'];
$payment_clean = strtoupper($row['PAYMENT']);
$ref_no = $row['REF_NO'] ?? 'N/A';

// Fetch Branch Name
$query_branch = "SELECT b.BRANCH_NAME 
                FROM customer c 
                JOIN branches b ON c.BRANCH_ID = b.BRANCH_ID 
                WHERE c.CUST_ID = (SELECT CUST_ID FROM transaction WHERE TRANS_ID = " . $_GET['id'] . " LIMIT 1) 
                LIMIT 1";
$result_branch = $db->query($query_branch) or die($db->error);
$row_branch = $result_branch->fetch_assoc();
$branch_name = $row_branch['BRANCH_NAME'] ?? 'N/A';
mysqli_set_charset($db, "utf8mb4");

// Create PDF instance
$pdf = new TCPDF();
$pdf->SetTitle("Receipt #$tid");
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Business Header: Logo on Left, Store Name on Right
$pdf->Image('../img/clogo.png', 10, 10, 30); // Left-Aligned Logo
$pdf->SetXY(50, 10);
$pdf->SetFont('helvetica', 'B', 18);
$pdf->Cell(0, 10, "MJC Hardware Trading", 0, 1, 'R');
$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(50, 20);
$pdf->Cell(0, 6, "Branch: $branch_name", 0, 1, 'R');
$pdf->Cell(0, 6, "Transaction Receipt", 0, 1, 'R');
$pdf->Ln(10);

// Two-Column Transaction Details
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 8, " Transaction Details ", 0, 1, 'C', true);
$pdf->Ln(2);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(45, 6, "Date:", 0, 0);
$pdf->Cell(50, 6, "$date", 0, 0);
$pdf->Cell(45, 6, "Transaction #:", 0, 0);
$pdf->Cell(50, 6, "$tid", 0, 1);

$pdf->Cell(45, 6, "Customer:", 0, 0);
$pdf->Cell(50, 6, "$fname $lname", 0, 0);
$pdf->Cell(45, 6, "Phone:", 0, 0);
$pdf->Cell(50, 6, "$pn", 0, 1);

$pdf->Cell(45, 6, "Cashier:", 0, 0);
$pdf->Cell(50, 6, "$role", 0, 0);
$pdf->Cell(45, 6, "Payment Method:", 0, 0);
$pdf->Cell(50, 6, "$payment_clean", 0, 1);

$pdf->Ln(5);

// Define column widths
$colWidths = [50, 40, 20, 30, 30];
$tableWidth = array_sum($colWidths);
$pageWidth = $pdf->GetPageWidth();
$startX = ($pageWidth - $tableWidth) / 2; // Calculate center position

// Table Header
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(0, 102, 204);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetX($startX); // Move to the center

$pdf->Cell($colWidths[0], 8, "Product", 1, 0, 'C', true);
$pdf->Cell($colWidths[1], 8, "Description", 1, 0, 'C', true);
$pdf->Cell($colWidths[2], 8, "Qty", 1, 0, 'C', true);
$pdf->Cell($colWidths[3], 8, "Price", 1, 0, 'C', true);
$pdf->Cell($colWidths[4], 8, "Total", 1, 1, 'C', true);

// Transaction Details
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);

$query_products = "SELECT PRODUCTS, DESCRIPTION, QTY, PRICE FROM transaction_details WHERE TRANS_NO = $tid";
$result_products = $db->query($query_products) or die($db->error);

while ($row_product = $result_products->fetch_assoc()) {
    $subTotal = $row_product['QTY'] * $row_product['PRICE'];

    $pdf->SetX($startX); // Keep rows aligned with header
    $pdf->Cell($colWidths[0], 8, $row_product['PRODUCTS'], 1);
    $pdf->Cell($colWidths[1], 8, $row_product['DESCRIPTION'], 1);
    $pdf->Cell($colWidths[2], 8, $row_product['QTY'], 1, 0, 'C');
    $pdf->Cell($colWidths[3], 8, number_format($row_product['PRICE'], 2), 1, 0, 'R');
    $pdf->Cell($colWidths[4], 8, number_format($subTotal, 2), 1, 1, 'R');
}

// Payment Details Section (Right-Aligned)
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 8, " Payment Details ", 0, 1, 'R', true);
$pdf->Ln(2);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(120, 6, "Subtotal:", 0, 0, 'R');
$pdf->Cell(30, 6, number_format($sub, 2), 0, 1, 'R');

$pdf->Cell(120, 6, "Less VAT:", 0, 0, 'R');
$pdf->Cell(30, 6, number_format($less, 2), 0, 1, 'R');

$pdf->Cell(120, 6, "Net VAT:", 0, 0, 'R');
$pdf->Cell(30, 6, number_format($net, 2), 0, 1, 'R');

$pdf->Cell(120, 6, "Discount:", 0, 0, 'R');
$pdf->Cell(30, 6, number_format($disco, 2), 0, 1, 'R');

$pdf->Cell(120, 6, "Add VAT:", 0, 0, 'R');
$pdf->Cell(30, 6, number_format($add, 2), 0, 1, 'R');

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(120, 8, "Grand Total:", 0, 0, 'R');
$pdf->Cell(30, 8, number_format($grand, 2), 0, 1, 'R');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(120, 6, "Cash Amount:", 0, 0, 'R');
$pdf->Cell(30, 6, number_format($cash, 2), 0, 1, 'R');

$pdf->Cell(120, 6, "Change:", 0, 0, 'R');
$pdf->Cell(30, 6, number_format($cash - $grand, 2), 0, 1, 'R');

// Footer
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 6, "Thank you for shopping with us!", 0, 1, 'C');
$pdf->Cell(0, 6, "*** This is a Computer Generated Receipt ***", 0, 1, 'C');

// Output PDF
$pdf->Output("receipt_$tid.pdf", "D");

$db->close();
?>
