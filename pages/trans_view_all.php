<?php 
include '../includes/connection.php';
session_start();

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop script execution
}
// Fetch user role and branch
$querya = "SELECT u.TYPE_ID, u.BRANCH_ID FROM users u WHERE u.ID = " . intval($_SESSION['MEMBER_ID']);
$resulta = mysqli_query($db, $querya) or die(mysqli_error($db));

if ($row = mysqli_fetch_assoc($resulta)) {
    $userTypeID = $row['TYPE_ID'];
    $branchID = $row['BRANCH_ID'];

    if ($userTypeID == 1) {
        include '../includes/sidebar.php';
    } elseif ($userTypeID == 2) {    
        include '../includes/sidebar_cashier.php';
    } elseif ($userTypeID == 3) {
        include '../includes/sidebar_manger.php';
    } else {
        die('Unauthorized User Type');
    }
} else {
    die('User not found in database.');
}

$from_date = $_GET['from_date'] ?? date('Y-m-d', strtotime('monday this week'));
$to_date = $_GET['to_date'] ?? date('Y-m-d');
?><div class="card shadow mb-4">
<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <!-- Transactions & Toggle -->
    <div class="d-flex align-items-center">
        <h4 class="m-2 font-weight-bold">Transactions</h4>

        <!-- Toggle Switch -->
        <div class="d-flex align-items-center ml-3">
            <label class="switch">
                <input type="checkbox" id="toggleView">
                <span class="slider round"></span>
            </label>
            <span class="ml-2">View Product</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div>
        <a href="trans_view_all.php?from_date=<?= date('Y-m-d') ?>&to_date=<?= date('Y-m-d') ?>" class="btn btn-light">
            <i class="fas fa-calendar-day"></i> Today
        </a>
        <a href="trans_view_all.php?from_date=<?= date('Y-m-d', strtotime('monday this week')) ?>&to_date=<?= date('Y-m-d') ?>" class="btn btn-light">
            <i class="fas fa-calendar-week"></i> This Week
        </a>
        <a href="trans_view_all.php?from_date=<?= date('Y-m-01') ?>&to_date=<?= date('Y-m-d') ?>" class="btn btn-light">
            <i class="fas fa-calendar-alt"></i> This Month
        </a>
    </div>
</div>

<style>
@media (max-width: 768px) {
.card-header {
    flex-direction: column; /* Stack elements for small screens */
    align-items: stretch; /* Align elements to full width */
}

.card-header .d-flex.align-items-center {
    flex-direction: row; /* Keep title and toggle switch aligned horizontally */
    justify-content: flex-start; /* Align to the left */
}

.card-header h4 {
    font-size: 16px; /* Adjust font size for smaller screens */
    margin-right: 10px; /* Space after title */
}

.card-header .ml-3 {
    margin-left: 10px; /* Adjust spacing for toggle switch */
}

.card-header > div:last-child {
    margin-top: 10px; /* Add spacing between toggle and buttons */
    display: flex; /* Keep buttons inline */
    justify-content: flex-end; /* Align buttons to the right */
    gap: 8px; /* Space between buttons */
}

.card-header .btn {
    width: auto; /* Ensure buttons are not stretched */
}
}
</style>

    <div class="card-body">
    <form method="GET" id="dateFilterForm">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label font-weight-bold">Select Date From:</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="from_date" value="<?= htmlspecialchars($from_date) ?>">
        </div>
        <label class="col-sm-1 col-form-label text-center font-weight-bold">To:</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="to_date" value="<?= htmlspecialchars($to_date) ?>">
        </div>

                <?php if ($userTypeID == 1) { ?>
                    <div class="col-sm-3">
                        <select class="form-control" name="branch_id" onchange="this.form.submit()">
                            <option value="">All Branches</option>
                            <?php
                            $selectedBranchID = $_GET['branch_id'] ?? '';
                            $branch_query = "SELECT BRANCH_ID, BRANCH_NAME FROM branches";
                            $branch_result = mysqli_query($db, $branch_query);
                            
                            while ($branch = mysqli_fetch_assoc($branch_result)) {
                                $selected = ($selectedBranchID == $branch['BRANCH_ID']) ? 'selected' : '';
                                echo "<option value='{$branch['BRANCH_ID']}' $selected>{$branch['BRANCH_NAME']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                <?php } elseif ($userTypeID == 3 || $userTypeID == 2) {
                    $user_branch_query = "SELECT BRANCH_NAME FROM branches WHERE BRANCH_ID = {$branchID}";
                    $user_branch_result = mysqli_query($db, $user_branch_query);
                    $user_branch_name = mysqli_fetch_assoc($user_branch_result)['BRANCH_NAME'];
                ?>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= $user_branch_name ?>" readonly>
                    </div>
                <?php } ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
            <thead class="bg-light text-dark">
                <tr class="text-center">
                    <th>Products</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

                <tbody>
                    <?php  
                    $totalSubtotal = 0;
                    $query = "SELECT TD.PRODUCTS, TD.DESCRIPTION, TD.QTY, TD.PRICE, (TD.QTY * TD.PRICE) AS SUBTOTAL
                    FROM transaction_details TD
                    JOIN transaction T ON TD.TRANS_NO = T.TRANS_NO
                    JOIN customer C ON T.CUST_ID = C.CUST_ID
                    JOIN branches B ON C.BRANCH_ID = B.BRANCH_ID
                    WHERE DATE(T.DATE) BETWEEN ? AND ?";

                    
                    $params = [$from_date, $to_date];
                    $types = "ss";
                    
                    if (!empty($_GET['branch_id'])) {
                        $branch_id = intval($_GET['branch_id']);
                        $query .= " AND B.BRANCH_ID = ?";
                        $params[] = $branch_id;
                        $types .= "i";
                    } elseif ($userTypeID == 3 || $userTypeID == 2) { 
                        $query .= " AND B.BRANCH_ID = ?";
                        $params[] = intval($branchID);
                        $types .= "i";
                    }
                    
                    $query .= " ORDER BY T.DATE DESC";
                    
                    $stmt = mysqli_prepare($db, $query);
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        $subtotal = (float) $row['SUBTOTAL'];
                        $totalSubtotal += $subtotal;
                        
                        echo "<tr class='text-center'>
                                <td>{$row['PRODUCTS']}</td>
                                <td>{$row['DESCRIPTION']}</td>
                                <td><span class='badge badge-primary p-2'>{$row['QTY']}</span></td>
                                <td>₱ " . number_format($row['PRICE'], 2) . "</td>
                                <td>₱ " . number_format($subtotal, 2) . "</td>
                              </tr>";
                    }
                    
                    ?>
                </tbody>
                <tfoot>
    <tr class="bg-light text-dark">
        <td></td>
        <td colspan="3" class="text-right font-weight-bold">Total:</td>
        <td class="font-weight-bold">₱ <?= number_format($totalSubtotal, 2) ?></td>
    </tr>
</tfoot>
            </table>
        </div>
    </div>
</div>


<?php if ($userTypeID != 2) { ?>
<form id="expenseForm" method="POST" action="save_expenses.php">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label font-weight-bold">Expenses:</label>
        <div class="col-sm-6">
            <div id="expenseContainer">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="expense_name[]" placeholder="Expense Name">
                    <input type="number" class="form-control" name="expense_amount[]" placeholder="Amount" step="0.01" min="0">
                    <div class="input-group-append">
                        <button class="btn btn-success add-expense" type="button">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php } ?>


<?php if ($userTypeID != 2) { ?>
    <div class="d-flex justify-content-end mt-3">
       
        <a href="javascript:void(0);" onclick="saveExpensesAndExport()" class="btn btn-success shadow-sm rounded-pill px-3 ml-2">
            <i class="fas fa-file-excel"></i> Save to Excel
        </a>
    </div>
 <?php } ?>


 <script>
function saveExpensesAndExport() {
    // Get the form data
    let formData = new FormData(document.querySelector('#expenseForm'));

    // Send a request to save expenses
    fetch('save_expenses.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Expenses saved:", data);
        
        // Redirect to export_excel.php after saving
        window.location.href = "export_excel_trans_pro.php?from_date=<?= $from_date ?>&to_date=<?= $to_date ?>";
    })
    .catch(error => console.error('Error saving expenses:', error));
}
</script>



<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector('.add-expense').addEventListener('click', function () {
        let expenseContainer = document.getElementById('expenseContainer');
        let newExpense = document.createElement('div');
        newExpense.classList.add('input-group', 'mb-2');
        newExpense.innerHTML = `
            <input type="text" class="form-control" name="expense_name[]" placeholder="Expense Name">
            <input type="number" class="form-control" name="expense_amount[]" placeholder="Amount" step="0.01" min="0">
            <div class="input-group-append">
                <button class="btn btn-danger remove-expense" type="button">-</button>
            </div>
        `;
        expenseContainer.appendChild(newExpense);

        // Attach event listener to remove button
        newExpense.querySelector('.remove-expense').addEventListener('click', function () {
            newExpense.remove();
        });
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[type="date"]').forEach(input => {
        function submitForm() {
            document.getElementById("dateFilterForm").submit();
        }

        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                submitForm();
            }
        });

        input.addEventListener("blur", function () {
            submitForm();
        });
    });
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        let params = new URLSearchParams(window.location.search);
        
        // If currently on trans_view_all.php, set the toggle as checked
        if (window.location.pathname.includes('trans_view_all.php')) {
            document.getElementById('toggleView').checked = true;
        }

        document.getElementById('toggleView').addEventListener('change', function() {
            let newPage = this.checked ? 'trans_view_all.php' : 'transaction.php';
            window.location.href = newPage + '?' + params.toString();
        });
    });
</script>


<style>
/* Toggle switch styling */
.switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 20px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #28a745;
}

input:checked + .slider:before {
    transform: translateX(20px);
}
</style>

<?php include '../includes/footer.php'; ?>
