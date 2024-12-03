<?php
$page_title = 'Edit Daily Revenue';
require_once('includes/load.php');
page_require_level(2);

// Check if an ID is passed in the URL
$revenue_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$revenue_id) {
  $session->msg("d", "Missing revenue id.");
  redirect('manage_sold_money.php');
}

// Retrieve the specific revenue record to edit
$revenue = find_by_id('daily_sold_money', $revenue_id);
if (!$revenue) {
  $session->msg("d", "Revenue record not found.");
  redirect('manage_sold_money.php');
}

if (isset($_POST['edit_revenue'])) {
  $req_fields = array('revenue', 'wechi');
  validate_fields($req_fields);

  if (empty($errors)) {
    $revenue_amount = remove_junk($db->escape($_POST['revenue']));
    $wechi = remove_junk($db->escape($_POST['wechi']));
    $date = empty($_POST['date']) ? $revenue['date'] : $db->escape($_POST['date']);

    // Update the existing record
    $query = "UPDATE daily_sold_money SET ";
    $query .= "date = '{$date}', total_revenue = '{$revenue_amount}', wechi = '{$wechi}' ";
    $query .= "WHERE id = '{$revenue_id}'";

    if ($db->query($query)) {
      $session->msg('s', "Revenue updated successfully");
      redirect('manage_sold_money.php', false);
    } else {
      $session->msg('d', 'Sorry, failed to update revenue!');
      redirect('edit_sold_money.php?id=' . $revenue_id, false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_sold_money.php?id=' . $revenue_id, false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Edit Daily Revenue</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_sold_money.php?id=<?php echo (int)$revenue['id']; ?>" class="clearfix">
          <div class="form-group">
            <label for="date">Date</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
              <input type="date" class="form-control" name="date" value="<?php echo remove_junk($revenue['date']); ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="revenue">ገቢ (in Birr)</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
              <input type="number" class="form-control" name="revenue" value="<?php echo remove_junk($revenue['total_revenue']); ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="wechi">ወጭ (in Birr)</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
              <input type="number" class="form-control" name="wechi" value="<?php echo remove_junk($revenue['wechi']); ?>">
            </div>
          </div>
          <button type="submit" name="edit_revenue" class="btn btn-danger">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
