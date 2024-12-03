<?php
$page_title = 'Sales Report';
$results = '';
require_once('includes/load.php');
page_require_level(3);  // User level permission check

if (isset($_POST['submit'])) {
  $req_dates = array('start-date', 'end-date');
  validate_fields($req_dates);

  if (empty($errors)) {
    $start_date = remove_junk($db->escape($_POST['start-date']));
    $end_date = remove_junk($db->escape($_POST['end-date']));
    $results = find_sale_by_dates($start_date, $end_date);
    
    // Fetch total sold money from daily_sold_amount table
    $daily_sold = find_total_sold_money($start_date, $end_date);
    
    // Calculate Wechi
    $wechi = calculate_wechi($start_date, $end_date);

    // Calculate stock value
    $stock_value = calculate_stock_value();

    // Calculate cost of sold products
    $sold_product_cost = calculate_sold_product_cost($start_date, $end_date);

    // Calculate Gross Profit
    $gross_profit = ($stock_value + $daily_sold) - ($stock_value + $sold_product_cost);
  } else {
    $session->msg("d", $errors);
    redirect('sales_report.php', false);
  }
} else {
  $session->msg("d", "Select dates");
  redirect('sales_report.php', false);
}

// Function to get total sold money from daily_sold_amount table

?>

<!doctype html>
<html lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Sales Report</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
  <style>
    @media print {
      html, body { font-size: 9.5pt; margin: 0; padding: 0; }
      .page-break { page-break-before: always; width: auto; margin: auto; }
    }
    .page-break { width: 980px; margin: 0 auto; }
    .sale-head { margin: 40px 0; text-align: center; }
    .sale-head h1, .sale-head strong { padding: 10px 20px; display: block; }
    .sale-head h1 { margin: 0; border-bottom: 1px solid #212121; }
    .table>thead:first-child>tr:first-child>th { border-top: 1px solid #000; }
    table thead tr th { text-align: center; border: 1px solid #ededed; }
    table tbody tr td { vertical-align: middle; }
    .sale-head, table.table thead tr th, table tbody tr td, table tfoot tr td { border: 1px solid #212121; white-space: nowrap; }
    .sale-head h1, table thead tr th, table tfoot tr td { background-color: #f8f8f8; }
    tfoot { color: #000; text-transform: uppercase; font-weight: 500; }
  </style>
</head>
<body>
  <?php if ($results): ?>
    <div class="page-break">
      <div class="sale-head">
        <h1>የወረታ መድሃኒት ቤት ሽያጭ Report</h1>
        <strong>ከቀን <?php if (isset($start_date)) echo $start_date; ?> እስከ ቀን <?php if (isset($end_date)) echo $end_date; ?> Full report</strong>
      </div>
      <table class="table table-border">
        <thead>
          <tr>
            <th>Date</th>
            <th>Product Title</th>
            <th>Buying Price</th>
            <th>Selling Price</th>
            <th>Total Sold amount</th>
            <th>Total Selling Price</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $result): ?>
            <tr>
              <td><?php echo remove_junk($result['date']); ?></td>
              <td><?php echo remove_junk(ucfirst($result['name'])); ?></td>
              <td class="text-right"><?php echo remove_junk($result['buy_price']); ?></td>
              <td class="text-right"><?php echo remove_junk($result['sale_price']); ?></td>
              <td class="text-right"><?php echo remove_junk($result['total_sales']); ?></td>
              <td class="text-right"><?php echo remove_junk($result['total_saleing_price']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr class="text-right">
            <td colspan="4"></td>
            <td colspan="1">አጠቃላይ የተሸጠ ብር /Grand Total</td>
            <td><?php echo number_format(total_price($results)[0], 2); ?> &nbsp;ETB</td>
          </tr>
          <tr class="text-right">
            <td colspan="4"></td>
            <td colspan="1">ትርፍ /Profit</td>
            <td><?php echo number_format(total_price($results)[1], 2); ?> &nbsp;ETB</td>
          </tr>
          <tr class="text-right">
            <td colspan="4"></td>
            <td colspan="1">አጠቃላይ ገቢ የተደረገ ብር /Total Sold Money</td>
            <td><?php echo number_format($daily_sold, 2); ?> &nbsp;ETB</td>
          </tr>
          <tr class="text-right">
            <td colspan="4"></td>
            <td colspan="1">ወጭ /Wechi</td>
            <td><?php echo number_format($wechi, 2); ?> &nbsp;ETB</td>
          </tr>
          <tr class="text-right">
            <td colspan="4"></td>
            <td colspan="1">ትርፍ - ወጭ /Profit - Wechi</td>
            <td><?php echo number_format(total_price($results)[1] - $wechi, 2); ?> &nbsp;ETB</td>
          </tr>
          <tr class="text-right">
            <td colspan="4"></td>
            <td colspan="1">Balance</td>
            <td><?php echo number_format($wechi + $daily_sold - total_price($results)[0], 2); ?> &nbsp;ETB</td>
          </tr>
          <tr class="text-right">
            <td colspan="4"></td>
            <td colspan="1">Gross Profit</td>
            <td><?php echo number_format($sold_product_cost, 2); ?> &nbsp;ETB</td>
          </tr>
        </tfoot>
      </table>
    </div>
  <?php else: ?>
    <?php $session->msg("d", "Sorry, no sales have been found."); redirect('sales_report.php', false); ?>
  <?php endif; ?>
</body>
