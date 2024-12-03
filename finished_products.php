<?php
  $page_title = 'Out of Stock Products';
  require_once('includes/load.php');
  
  // Check the user level
  page_require_level(2);

  // Set the number of products per page
  $limit = 15;

  // Get the current page number from the URL (default to page 1 if not set)
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

  // Calculate the offset based on the current page
  $offset = ($page - 1) * $limit;

  // Get all out-of-stock products for the current page
  $products = join_product_table_out_of_stock($offset, $limit);

  // Get the total number of out-of-stock products
  $total_products = count_out_of_stock_products();

  // Calculate the total number of pages
  $total_pages = ceil($total_products / $limit);
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
          <a href="add_product.php" class="btn btn-primary">Add New</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Product Title </th>
              <th class="text-center" style="width: 10%;"> መለኪያ </th>
              <th class="text-center" style="width: 10%;"> Categories </th>
              <th class="text-center" style="width: 10%;"> In-Stock </th>
              <th class="text-center" style="width: 10%;"> Buying Price </th>
              <th class="text-center" style="width: 10%;"> Selling Price </th>
              <th class="text-center" style="width: 10%;"> Product Added </th>
              <th class="text-center" style="width: 10%;"> Product lastly updated </th>
              <th class="text-center" style="width: 10%;"> last updated quantity </th>
              <th class="text-center" style="width: 100px;"> Actions </th>
            </tr>
          </thead>
          
          <?php
            // Initialize the row number (start from the offset + 1)
            $row_number = $offset + 1;
          ?>

          <tbody>
            <?php foreach ($products as $product): ?>
              <tr>
                <td class="text-center"><?php echo $row_number++; ?></td> <!-- Display dynamic row number -->
                <td><?php echo remove_junk($product['name']); ?></td>
                <td><?php echo remove_junk($product['melekiya']); ?></td>
                <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
                <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"><?php echo read_date($product['date']); ?></td>
                <td class="text-center"><?php echo read_date($product['last_upadate']); ?></td>
                <td class="text-center"><?php echo remove_junk($product['lastly_added_product']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
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
                  <a href="finished_product.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
