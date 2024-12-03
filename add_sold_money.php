<?php
  $page_title = 'Add Daily Revenue';
  require_once('includes/load.php');
  page_require_level(2);

  if(isset($_POST['add_product'])){
    $req_fields = array('revenue');
    validate_fields($req_fields);
    
    if(empty($errors)){
      $revenue  = remove_junk($db->escape($_POST['revenue']));
      $wechi  = remove_junk($db->escape($_POST['wechi']));
      
      // Set the date
      $date = empty($_POST['date']) ? make_date() : read_date($db->escape($_POST['date']));
      
      // Insert or update daily revenue
      $query  = "INSERT INTO daily_sold_money (date, total_revenue,wechi) ";
      $query .= "VALUES ('{$date}', '{$revenue}','{$wechi}') ";
      $query .= "ON DUPLICATE KEY UPDATE total_revenue = '{$revenue}'";

      if($db->query($query)){
        $session->msg('s',"Revenue saved successfully");
        redirect('manage_sold_money.php', false);
      } else {
        $session->msg('d','Sorry, failed to save!');
        redirect('add_sold_money.php', false);
      }

    } else{
      $session->msg("d", $errors);
      redirect('add_sold_money.php', false);
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
          <span>የተሸጠ በር መመዝገቢያ ፎርም</span><br>
          <span>እባክዎ አንዴ save ካሉ በኋላ በትክክል እንደተመዘገበ ካረጋጋጡ በኋላ ድጋሜ save አይበሉት</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_sold_money.php" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                <input type="date" class="form-control" name="date" placeholder="Date">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                <input type="number" class="form-control" name="revenue" placeholder="የገባልዎትን ብር እዚህ  ያስገቡ">
              </div>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                <input type="number" class="form-control" name="wechi" placeholder="ወጭ ብር እዚህ  ያስገቡ">
              </div>
            </div>
            <button type="submit" name="add_product" class="btn btn-danger">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
