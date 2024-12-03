<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Sidebar</title>
  <style>
    /* Toggle button styles */
    .menu-toggle-icon {
      display: none;
      font-size: 24px;
      padding: 10px;
      cursor: pointer;
      position: fixed;
      top: 10px;
      left: 10px;
      background-color: #333;
      color: white;
      z-index: 1000;
      border-radius: 5px;
    }

    /* Mobile-specific styles */
    @media (max-width: 768px) {
      .admin_sideManue {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      
      .menu-toggle-icon {
        display: block;
      }
      
      .admin_sideManue.menu-open {
        transform: translateX(0);
      }
    }
  </style>
</head>
<body>

<!-- Toggle Icon -->
<div class="menu-toggle-icon" onclick="toggleMenu()">☰</div>

<!-- Sidebar Menu -->
<div class="admin_sideManue">
  <ul>
    <li><a href="admin.php"><i class="glyphicon glyphicon-home"></i><span>Dashboard</span></a></li>
    <li><a href="#" class="submenu-toggle"><i class="glyphicon glyphicon-user"></i><span>User Management</span></a>
      <ul class="nav submenu">
        <li><a href="group.php">Manage Groups</a></li>
        <li><a href="users.php">Manage Users</a></li>
      </ul>
    </li>
    <li><a href="categorie.php"><i class="glyphicon glyphicon-indent-left"></i><span>Categories</span></a></li>
    <li><a href="#" class="submenu-toggle"><i class="glyphicon glyphicon-th-large"></i><span>Products</span></a>
      <ul class="nav submenu">
        <li><a href="product.php">Manage Products</a></li>
        <li><a href="add_product.php">Add Products</a></li>
        <li><a href="finished_products.php">ያለቁ</a></li>
        <li><a href="almost_finished.php">ሊያልቅ ትንሽ የቀረው</a></li>
      </ul>
    </li>
    <li><a href="media.php"><i class="glyphicon glyphicon-picture"></i><span>Media Files</span></a></li>
    <li><a href="#" class="submenu-toggle"><i class="glyphicon glyphicon-credit-card"></i><span>Sales</span></a>
      <ul class="nav submenu">
        <li><a href="sales.php">Manage Sales</a></li>
        <li><a href="add_sale.php">Add Sale</a></li>
        
      </ul>
    </li>
    <li><a href="#" class="submenu-toggle"><i class="glyphicon glyphicon-credit-card"></i><span>Sold money</span></a>
      <ul class="nav submenu">
        <li><a href="manage_sold_money.php">Manage money</a></li>
        
        <li><a href="add_sold_money.php">የተሸጠ ብር ይመዝግቡ</a> </li>
      </ul>
    </li>
    <li><a href="#" class="submenu-toggle"><i class="glyphicon glyphicon-duplicate"></i><span>Sales Report</span></a>
      <ul class="nav submenu">
        <li><a href="sales_report.php">Sales by dates</a></li>
        <li><a href="monthly_sales.php">Monthly sales</a></li>
        <li><a href="daily_sales.php">Daily sales</a></li>
      </ul>
    </li>
  </ul>
</div>

<script>
  function toggleMenu() {
    document.querySelector('.admin_sideManue').classList.toggle('menu-open');
  }
</script>

</body>
</html>
