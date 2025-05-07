<?php

require_once '../vendor/autoload.php'; 
session_start();
include '../includes/connection.php'; 

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['po_id'])) {
    die("Invalid Request.");
}

$po_id = intval($_GET['po_id']);

// Fetch PO details
$sql_po = "SELECT po.PO_ID, s.COMPANY_NAME, po.ORDER_DATE, po.EXPECTED_DELIVERY_DATE, po.STATUS, 
                  CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME) AS CREATED_BY_NAME
           FROM purchase_orders po
           JOIN supplier s ON po.SUPPLIER_ID = s.SUPPLIER_ID
           JOIN employee e ON po.CREATED_BY = e.EMPLOYEE_ID
           WHERE po.PO_ID = $po_id";

$result_po = mysqli_query($db, $sql_po);
$po = mysqli_fetch_assoc($result_po);

if (!$po) {
    die("Purchase Order not found.");
}

// Fetch PO items
$sql_items = "SELECT p.NAME, poi.QUANTITY_ORDERED, poi.UNIT_PRICE 
              FROM purchase_order_items poi
              JOIN product p ON poi.PRODUCT_ID = p.PRODUCT_ID
              WHERE poi.PO_ID = $po_id";
$result_items = mysqli_query($db, $sql_items);

ob_start();
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
ob_end_clean();

// Create PDF
$pdf = new TCPDF();
$pdf->SetTitle("Purchase Order #{$po['PO_ID']}");
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

// ** Header: Logo (Left), Company Name (Right) **
$pdf->Image('../img/clogo.png', 10, 10, 30); // Left-aligned logo
$pdf->SetXY(50, 10);
$pdf->SetFont('helvetica', 'B', 18);
$pdf->Cell(0, 10, "MJC HARDWARE TRADING", 0, 1, 'R');
$pdf->SetFont('helvetica', '', 12);
$pdf->SetXY(50, 20);
$pdf->Cell(0, 6, "Purchase Order", 0, 1, 'R');
$pdf->Ln(10);

// ** Purchase Order Details (Right-Aligned) **
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 8, " Purchase Order Details ", 0, 1, 'C', true);
$pdf->Ln(2);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(45, 6, "PO ID:", 0, 0);
$pdf->Cell(50, 6, $po['PO_ID'], 0, 0);
$pdf->Cell(45, 6, "Supplier:", 0, 0);
$pdf->Cell(50, 6, $po['COMPANY_NAME'], 0, 1);

$pdf->Cell(45, 6, "Order Date:", 0, 0);
$pdf->Cell(50, 6, $po['ORDER_DATE'], 0, 0);
$pdf->Cell(45, 6, "Expected Delivery:", 0, 0);
$pdf->Cell(50, 6, $po['EXPECTED_DELIVERY_DATE'], 0, 1);

$pdf->Cell(45, 6, "Status:", 0, 0);
$pdf->Cell(50, 6, $po['STATUS'], 0, 1);

$pdf->Ln(5);

// ** Ordered Items Table **
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(0, 102, 204);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 8, "Product Name", 1, 0, 'C', true);
$pdf->Cell(30, 8, "Quantity", 1, 0, 'C', true);
$pdf->Cell(40, 8, "Unit Price", 1, 0, 'C', true);
$pdf->Cell(40, 8, "Total Price", 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$total_cost = 0;

while ($row = mysqli_fetch_assoc($result_items)) {
    $total_price = $row['QUANTITY_ORDERED'] * $row['UNIT_PRICE'];
    $total_cost += $total_price;

    $pdf->Cell(70, 8, $row['NAME'], 1);
    $pdf->Cell(30, 8, $row['QUANTITY_ORDERED'], 1, 0, 'C');
    $pdf->Cell(40, 8, number_format($row['UNIT_PRICE'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($total_price, 2), 1, 1, 'R');
}

// ** Total Cost (Right-Aligned) **
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(140, 8, "Total Cost:", 0, 0, 'R');
$pdf->Cell(40, 8, number_format($total_cost, 2), 0, 1, 'R');

$pdf->Ln(10);

// ** Footer (Signature Section) **
$pdf->SetFont('helvetica', '', 12);
$pageWidth = $pdf->GetPageWidth();
$margin = 10;
$usableWidth = $pageWidth - (2 * $margin);
$colWidth = $usableWidth / 2;

$createdBy = "Created by: " . $po['CREATED_BY_NAME'];
$approvedBy = "Approved by: ___________________________";

$pdf->Cell($colWidth, 6, $createdBy, 0, 0, 'L');
$pdf->Cell($colWidth, 6, $approvedBy, 0, 1, 'R');

$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, "*** This is a system-generated document. ***", 0, 1, 'C');

// Output PDF
$pdf->Output("Purchase_Order_{$po['PO_ID']}.pdf", "I");

?>
