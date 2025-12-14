<?php
if (!isset($_GET['book_id'])) {
  header("Location: index.php");
  exit;
}

$book_id = $_GET['book_id'];
$errors = [];

// Payment validation
if (isset($_POST['pay'])) {
  $method = $_POST['payment_method'] ?? '';

  if ($method === 'card') {
    $card_name = trim($_POST['card_name'] ?? '');
    $card_number = trim($_POST['card_number'] ?? '');
    $expiry = trim($_POST['expiry'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');

    if ($card_name === '' || $card_number === '' || $expiry === '' || $cvv === '') {
      $errors[] = "Please fill all card details.";
    }
  } elseif ($method === 'upi') {
    $upi_id = trim($_POST['upi_id'] ?? '');

    // Valid UPI handles
    $valid_upi_suffixes = ['@icici', '@sbi', '@hdfcbank', '@axisbank', '@paytm', '@ybl'];

    $is_valid_upi = false;
    foreach ($valid_upi_suffixes as $suffix) {
      if (str_ends_with(strtolower($upi_id), strtolower($suffix))) {
        $is_valid_upi = true;
        break;
      }
    }

    if ($upi_id === '') {
      $errors[] = "Please enter your UPI ID.";
    } elseif (!$is_valid_upi) {
      $errors[] = "UPI ID must end with a valid handle (e.g., @icici, @sbi, @paytm).";
    }
  } else {
    $errors[] = "Please select a payment method.";
  }

  if (empty($errors)) {
    header("Location: download.php?book_id=" . urlencode($book_id));
    exit;
  }
}

// str_ends_with() for PHP < 8.0
if (!function_exists('str_ends_with')) {
  function str_ends_with($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Demo Payment</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    body {
      background-color: #f6f6f2;
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }

    form {
      width: 400px;
      background-color: rgba(255, 255, 255, 0.13);
      backdrop-filter: blur(10px);
      padding: 30px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #333;
    }

    form * {
      font-family: 'Poppins', sans-serif;
      font-size: 16px;
      border: none;
      outline: none;
      box-sizing: border-box;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-top: 15px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.25);
      box-shadow: inset 2px 2px 5px rgba(0,0,0,0.05);
      color: #333;
    }

    .payment-section {
      display: none;
    }

    .visible {
      display: block;
    }

    button {
      margin-top: 30px;
      padding: 12px;
      width: 100%;
      border-radius: 8px;
      background-color: #14B8A6;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #0D9488;
    }

    h3 {
      text-align: center;
    }

    .error-box {
      background: #ffe0e0;
      color: #c00;
      border: 1px solid #c00;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }

    /* Toggle Switch */
    .switch-wrapper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 20px 0;
    }

    .switch-label {
      font-weight: bold;
    }

    .switch-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .switch-track {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 26px;
      background: #6366F1;
      border-radius: 15px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .switch-track::after {
      content: "";
      position: absolute;
      width: 22px;
      height: 22px;
      left: 2px;
      top: 2px;
      background: white;
      border-radius: 50%;
      transition: transform 0.3s ease;
    }

    input[type="checkbox"]#paymentToggle {
      display: none;
    }

    input[type="checkbox"]#paymentToggle:checked + .switch-track {
      background: #14B8A6;
    }

    input[type="checkbox"]#paymentToggle:checked + .switch-track::after {
      transform: translateX(24px);
    }
  </style>

  <script>
    function showSection(method) {
      document.getElementById('card-fields').classList.remove('visible');
      document.getElementById('upi-fields').classList.remove('visible');
      if (method === 'card') {
        document.getElementById('card-fields').classList.add('visible');
      } else if (method === 'upi') {
        document.getElementById('upi-fields').classList.add('visible');
      }
    }

    window.onload = function () {
      const toggle = document.getElementById('paymentToggle');
      const methodInput = document.getElementById('payment_method');
      const labelText = document.getElementById('paymentModeText');

      // Initialize view
      showSection(methodInput.value);

      toggle.addEventListener('change', function () {
        const newMethod = this.checked ? 'upi' : 'card';
        methodInput.value = newMethod;
        labelText.textContent = newMethod.charAt(0).toUpperCase() + newMethod.slice(1);
        showSection(newMethod);
      });
    };
  </script>
</head>
<body>

<?php if (!empty($errors)): ?>
  <div class="error-box">
    <?php foreach ($errors as $error): ?>
      <p><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form method="POST">
  <h3>Demo Payment</h3>

  <div class="switch-wrapper">
    <label class="switch-label">Payment Method:</label>
    <div class="switch-toggle">
      <input type="checkbox" id="paymentToggle" name="payment_toggle">
      <label for="paymentToggle" class="switch-track"></label>
      <span id="paymentModeText">Card</span>
    </div>
    <input type="hidden" name="payment_method" id="payment_method" value="card">
  </div>

  <!-- Card Payment Fields -->
  <div id="card-fields" class="payment-section">
    <label>Name on Card</label>
    <input type="text" name="card_name" placeholder="John Doe">

    <label>Card Number</label>
    <input type="text" name="card_number" placeholder="0000 0000 0000 0000">

    <label>Expiry</label>
    <input type="text" name="expiry" placeholder="MM/YY">

    <label>CVV</label>
    <input type="text" name="cvv" placeholder="123">
  </div>

  <!-- UPI Payment Field -->
  <div id="upi-fields" class="payment-section">
    <label>UPI ID</label>
    <input type="text" name="upi_id" placeholder="example@upi">
  </div>

  <button type="submit" name="pay">Pay ₹49.00 Now</button>
</form>

</body>
</html>
