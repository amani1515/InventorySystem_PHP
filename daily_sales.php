<?php
  $page_title = 'Daily Sales';
  require_once('includes/load.php');
  page_require_level(3);

  $year  = date('Y');
  $month = date('m');
  
  // Pagination setup
  $limit = 15;  // Number of results per page
  $total_sales = count_daily_sales($year, $month);  // Get the total number of sales
  $total_pages = ceil($total_sales / $limit);  // Calculate total pages
  
  // Get the current page from URL (default is page 1)
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;  // Calculate the offset for the query
  
  // Fetch the paginated sales data for the current day
  $sales = dailySales($year, $month, $offset, $limit);
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Daily Sales</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Product name </th>
              <th class="text-center" style="width: 15%;"> መለኪያ</th>
              <th class="text-center" style="width: 15%;"> Quantity sold</th>
              <th class="text-center" style="width: 15%;"> Total </th>
              <th class="text-center" style="width: 15%;"> Date </th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $counter = $offset + 1;  // Start count based on the offset
            foreach ($sales as $sale): 
            ?>
              <tr>
                <td class="text-center"><?php echo $counter++; ?></td> <!-- Display correct number -->
                <td><?php echo remove_junk($sale['name']); ?></td>
                <td><?php echo remove_junk($sale['melekiya']); ?></td>
                <td class="text-center"><?php echo (int)$sale['total_sales']; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
                <td class="text-center"><?php echo $sale['date']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <?php if ($page > 1): ?>
              <li><a href="?page=<?php echo $page - 1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="<?php echo ($i == $page) ? 'active' : ''; ?>"><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
              <li><a href="?page=<?php echo $page + 1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
            <?php endif; ?>
          </ul>
        </nav>

      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
