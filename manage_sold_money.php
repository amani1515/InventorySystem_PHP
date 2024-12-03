<?php
  $page_title = 'Sold Money';
  require_once('includes/load.php');
  
  // Check the user level
  page_require_level(2);

  // Set the number of records per page
  $limit = 2;

  // Get the current page number from the URL (default to page 1 if not set)
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

  // Calculate the offset based on the current page
  $offset = ($page - 1) * $limit;

  // Get all daily revenue records for the current page
  $revenues = find_all_revenues($offset, $limit);

  // Get the total number of daily revenue records
  $total_revenues = count_all_revenues();

  // Calculate the total number of pages
  $total_pages = ceil($total_revenues / $limit);
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="add_sold_money.php" class="btn btn-primary">Add New</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th class="text-center" style="width: 10%;">የገባ ብር መጠን</th>
              <th class="text-center" style="width: 10%;">አጠቃላይ ወጭ</th>
              <th class="text-center" style="width: 10%;">ቀን</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <?php
            // Initialize the row number (start from the offset + 1)
            $row_number = $offset + 1;
          ?>

          <tbody>
            <?php foreach ($revenues as $revenue): ?>
              <tr>
                <td class="text-center"><?php echo $row_number++; ?></td>
                <td><?php echo remove_junk($revenue['total_revenue']); ?></td>
                <td><?php echo remove_junk($revenue['wechi']); ?></td>
                <td class="text-center"><?php echo read_date($revenue['date']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_sold_money.php?id=<?php echo (int)$revenue['id'];?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_sold_money.php?id=<?php echo (int)$revenue['id'];?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- Pagination links -->
        <div class="text-center">
          <?php if ($total_pages > 1): ?>
            <ul class="pagination">
              <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                  <a href="sold_money.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
