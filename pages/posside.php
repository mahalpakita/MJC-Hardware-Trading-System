<!-- SIDE PART NA SUMMARY -->
<?php 
require_once('redirect.php');
allowOnlyCashier();
?>

<div class="card-body">
    
<?php   
if (!isset($_SESSION['pointofsale']) || !is_array($_SESSION['pointofsale'])) {
    $_SESSION['pointofsale'] = []; // Ensure it is always an array
}

if (!empty($_SESSION['pointofsale'])):  
    $total = 0;  

    foreach ($_SESSION['pointofsale'] as $product) {  
        $total += $product['quantity'] * $product['price'];
    }

    // VAT calculations (outside loop)
    $lessvat = ($total / 1.12) * 0.12;
    $netvat = ($total / 1.12);
    $addvat = ($total / 1.12) * 0.12;
?>


<!-- DROPDOWN FOR CUSTOMER FILTERED BY BRANCH -->
<?php
// Siguraduhin na naka-set ang MEMBER_ID
if (!isset($_SESSION['MEMBER_ID'])) {
    die('Error: MEMBER_ID is not set in session.');
}

// Get user’s BRANCH_ID securely
$queryUser = "SELECT BRANCH_ID FROM users WHERE ID = ?";
$stmt = mysqli_prepare($db, $queryUser);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['MEMBER_ID']);
mysqli_stmt_execute($stmt);
$resultUser = mysqli_stmt_get_result($stmt);

if ($rowUser = mysqli_fetch_assoc($resultUser)) {
    $userBranchID = $rowUser['BRANCH_ID'];

    if ($userBranchID !== null) {
        // Get customers only from the same branch as the user
        $sql = "SELECT CUST_ID, FIRST_NAME, LAST_NAME 
                FROM customer 
                WHERE BRANCH_ID = ? 
                ORDER BY FIRST_NAME ASC";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userBranchID);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        // Kunin ang default Walk-in Customer ID sa database
        $defaultQuery = "SELECT CUST_ID FROM customer WHERE FIRST_NAME = 'Walk-in' AND BRANCH_ID = ?";
        $stmtDefault = mysqli_prepare($db, $defaultQuery);
        mysqli_stmt_bind_param($stmtDefault, "i", $userBranchID);
        mysqli_stmt_execute($stmtDefault);
        $resultDefault = mysqli_stmt_get_result($stmtDefault);
        $defaultCustomerID = ($rowDefault = mysqli_fetch_assoc($resultDefault)) ? $rowDefault['CUST_ID'] : '';

        // Generate dropdown
        $opt = "<select class='form-control' style='border-radius: 0px;' name='customer' required>
                <option value='' disabled hidden>Select Customer</option>";

        while ($row = mysqli_fetch_assoc($res)) {
            $selected = ($row['CUST_ID'] == $defaultCustomerID) ? "selected" : "";
            $opt .= "<option value='".$row['CUST_ID']."' $selected>".$row['FIRST_NAME'].' '.$row['LAST_NAME']."</option>";
        }

        $opt .= "</select>";
    } else {
        $opt = "<p class='text-danger'>Error: User's branch ID is NULL.</p>";
    }
} else {
    $opt = "<p class='text-danger'>Error retrieving user branch.</p>";
}
?>

    <?php 
         date_default_timezone_set('Asia/Manila'); // Set timezone to Philippine Time
         echo "Today's date is : ";
         $today = date("Y-m-d h:iA"); // 12-hour format with AM/PM
         echo $today;
    ?> 
    
    <input type="hidden" name="date" value="<?php echo $today; ?>">
    
    <div class="form-group row text-left mb-3">
        <div class="col-sm-12 text-primary btn-group">
            <?php echo $opt; ?>
            <a href="#" data-toggle="modal" data-target="#customerModal" type="button" class="btn btn-primary bg-gradient-primary" style="border-radius: 0px;">
                <i class="fas fa-fw fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- TOTAL CALCULATION DISPLAY -->
    <div class="form-group row mb-2">
        <div class="col-sm-5 text-left text-primary py-2"><h6>Subtotal</h6></div>
        <div class="col-sm-7">
            <div class="input-group mb-2">
                <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                <input type="text" class="form-control text-right" value="<?php echo number_format($total, 2); ?>" readonly name="subtotal">
            </div>
        </div>
    </div>

    <div class="form-group row mb-2">
        <div class="col-sm-5 text-left text-primary py-2"><h6>Less VAT</h6></div>
        <div class="col-sm-7">
            <div class="input-group mb-2">
                <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                <input type="text" class="form-control text-right" value="<?php echo number_format($lessvat, 2); ?>" readonly name="lessvat">
            </div>
        </div>
    </div>

    <div class="form-group row mb-2">
        <div class="col-sm-5 text-left text-primary py-2"><h6>Net of VAT</h6></div>
        <div class="col-sm-7">
            <div class="input-group mb-2">
                <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                <input type="text" class="form-control text-right" value="<?php echo number_format($netvat, 2); ?>" readonly name="netvat">
            </div>
        </div>
    </div>

    <div class="form-group row mb-2">
        <div class="col-sm-5 text-left text-primary py-2"><h6>Add VAT</h6></div>
        <div class="col-sm-7">
            <div class="input-group mb-2">
                <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                <input type="text" class="form-control text-right" value="<?php echo number_format($addvat, 2); ?>" readonly name="addvat">
            </div>
        </div>
    </div>

    <div class="form-group row text-left mb-2">
        <div class="col-sm-5 text-primary"><h6 class="font-weight-bold py-2">Total</h6></div>
        <div class="col-sm-7">
            <div class="input-group mb-2">
                <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                <input type="text" class="form-control text-right" value="<?php echo number_format($total, 2); ?>" readonly name="total">
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#posMODAL">SUBMIT</button>

    <?php endif; ?>
</div>
<!-- Modal -->
<div class="modal fade" id="posMODAL" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">TRANSACTION SUMMARY</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form action="pos_process.php" method="POST">
        <div class="modal-body">
          <div class="mb-2">
            <label>Transaction No.:</label>
            <input type="text" name="transNo" id="transNo" class="form-control" required maxlength="8" oninput="validateNumberOnly(this)">
            <div id="transNoFeedback" class="invalid-feedback" style="display: none;"></div>
          </div>
          <div class="mb-2" id="referenceNoContainer" style="display: none;">
            <label>Reference No.:</label>
            <input type="text" name="referenceNo" id="referenceNo" class="form-control" maxlength="10" oninput="validateNumberOnly(this)">
          </div>
          <div class="mb-2">
            <label>Payment Method:</label>
            <select name="payment" id="payment" class="form-control" required>
              <option value="cash" selected>Cash</option>
              <option value="online">Online</option>
              <option value="bank">Bank</option>
            </select>
          </div>
          <div class="mb-2">
            <label>Discount:</label>
            <input type="text" name="discount" id="discountInput" class="form-control" placeholder="Enter % or Amount">
            <input type="hidden" name="discountType" id="discountType" value="AMOUNT">
          </div>
          <div class="text-center">
            <h3>GRAND TOTAL</h3>
            <h3 class="font-weight-bold py-3 bg-light">
              <span id="originalTotal" style="text-decoration: line-through; color: gray; display: none;">₱ <?php echo number_format($total, 2); ?></span>
              <span id="grandTotal">₱ <?php echo number_format($total, 2); ?></span>
            </h3>
          </div>
          <div class="text-center">
            <h3>CHANGE</h3>
            <h3 class="font-weight-bold py-3 bg-light">₱ <span id="change">0.00</span></h3>
          </div>
          <div class="mb-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">₱</span>
              </div>
              <input class="form-control text-right" id="txtNumber" type="text" name="cash" 
              style="font-size: 34px; height: 60px;" required oninput="validateCashInput(this)">
            </div>
            <div id="cashError" class="text-danger mt-2" style="display: none;">Invalid cash amount!</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="submitpos" id="proceedButton" class="btn btn-primary btn-block" disabled>PROCEED TO PAYMENT</button>
        </div>
      </form>
    </div>
  </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function validateNumberOnly(input) {
    input.value = input.value.replace(/[^0-9]/g, ''); // Remove any non-numeric characters
}
document.addEventListener("DOMContentLoaded", function () {
    const txtNumber = document.getElementById("txtNumber"),
        changeDisplay = document.getElementById("change"),
        proceedButton = document.getElementById("proceedButton"),
        paymentMethod = document.getElementById("payment"),
        referenceNoContainer = document.getElementById("referenceNoContainer"),
        discountInput = document.getElementById("discountInput"),
        grandTotalElement = document.getElementById("grandTotal"),
        originalTotalElement = document.getElementById("originalTotal"),
        transNoField = document.getElementById("transNo"),
        transNoFeedback = document.getElementById("transNoFeedback"),
        cashError = document.getElementById("cashError"),
        maxCashAmount = 100000000;

    let total = parseFloat(<?php echo json_encode($total); ?>) || 0;
    let autoUpdateCash = true;
    let lastValidDiscount = "";

    txtNumber.value = total.toFixed(2);

    function updateGrandTotal() {
        let discountValue = discountInput.value.trim();
        let discountAmount = 0;

        // ✅ Reset total if discount is empty
        if (discountValue === "") {
            grandTotalElement.innerText = `₱ ${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            originalTotalElement.style.display = "none";
            txtNumber.value = total.toFixed(2);
            updateChange(total);
            return;
        }

        if (discountValue.endsWith("%")) {
            let percent = discountValue.slice(0, -1);
            if (!/^\d+$/.test(percent) || percent > 100 || percent < 0) {
                discountInput.value = lastValidDiscount;
                return;
            }
            discountAmount = total * (parseFloat(percent) / 100);
        } else {
            if (!/^\d+(\.\d{0,2})?$/.test(discountValue) || parseFloat(discountValue) > total) {
                discountInput.value = lastValidDiscount;
                return;
            }
            discountAmount = parseFloat(discountValue);
        }

        lastValidDiscount = discountInput.value;
        let newTotal = Math.max(total - discountAmount, 0);
        originalTotalElement.style.display = discountAmount > 0 ? "inline" : "none";
        grandTotalElement.innerText = `₱ ${newTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        if (autoUpdateCash) {
            txtNumber.value = newTotal.toFixed(2);
        }

        updateChange(newTotal);
    }
    function updateChange(newTotal = total) {
    let cash = parseFloat(txtNumber.value) || 0;
    let change = cash - newTotal;

    changeDisplay.innerText = change >= 0 ? change.toFixed(2) : "0.00";
    proceedButton.disabled = cash < newTotal;
}

txtNumber.addEventListener("input", function () {
    autoUpdateCash = false;

    // Remove leading zero unless it's '0.'
    this.value = this.value.replace(/^0+(?=[1-9])/, '');

    // Allow only numbers and one decimal point
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');

    // Enforce two decimal places
    let parts = this.value.split('.');
    if (parts.length > 1) {
        parts[1] = parts[1].slice(0, 2); // Keep only first two decimal places
    }
    this.value = parts.join('.');

    let value = parseFloat(this.value) || 0;
    if (value > maxCashAmount) {
        this.value = maxCashAmount.toFixed(2);
        cashError.style.display = "block";
    } else {
        cashError.style.display = "none";
    }

    updateChange();
});


    paymentMethod.addEventListener("change", function () {
        referenceNoContainer.style.display = this.value === "online" ? "block" : "none";
        updateChange();
    });

    discountInput.addEventListener("input", function () {
    let value = discountInput.value.trim();

    // Remove any non-numeric characters except "." and "%"
    value = value.replace(/[^0-9.%]/g, '');

    // Remove leading zeros (except when it's "0" or "0.")
    if (value.match(/^0+\d/)) {
        value = value.replace(/^0+/, '');
    }

    // Ensure percentage input remains valid (e.g., "10%")
    if (value.includes("%") && value.indexOf("%") !== value.length - 1) {
        value = value.slice(0, value.indexOf("%") + 1);
    }

    discountInput.value = value;
    autoUpdateCash = true;
    updateGrandTotal();
});


    $(document).ready(function () {
        $("#transNo").on("input", function () {
            let transNo = $(this).val().trim();
            if (transNo) {
                $.post("check_transaction.php", { transNo }, function (response) {
                    let isDuplicate = response.status === "duplicate";
                    $("#transNo").toggleClass("is-invalid", isDuplicate);
                    $("#transNoFeedback").text(isDuplicate ? "Duplicate transaction number found!" : "").toggle(isDuplicate);
                }, "json");
            } else {
                $("#transNo").removeClass("is-invalid").next().hide();
            }
        });
    });

    updateGrandTotal();
    updateChange();
});

</script>



</form>
</div>
