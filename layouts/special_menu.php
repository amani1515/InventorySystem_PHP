<style>
  /* Sidebar styles */
.sidebar-menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar-menu > li {
  position: relative;
}

.sidebar-menu li a {
  display: block;
  padding: 10px 20px;
  color: #fff;
  background-color: #343a40;
  text-decoration: none;
  border-bottom: 1px solid #444;
  transition: background-color 0.3s ease;
}

.sidebar-menu li a:hover {
  background-color: #007bff;
}

.sidebar-menu li a i {
  margin-right: 10px;
}

/* Submenu styles */
.submenu {
  display: none;
  padding-left: 20px;
  background-color: #454d55;
  list-style-type: none;
  margin: 0;
}

.submenu li a {
  color: #fff;
  padding: 8px 20px;
  text-decoration: none;
}

.submenu li a:hover {
  background-color: #0056b3;
}

/* Toggle submenu */
.submenu-toggle + .submenu {
  display: block;
}

.submenu-toggle.active + .submenu {
  display: block;
}

/* Make submenu visible on hover for larger screens */
@media (min-width: 768px) {
  .sidebar-menu li:hover .submenu {
    display: block;
  }
}

/* For mobile responsiveness */
@media (max-width: 767px) {
  .sidebar-menu {
    width: 100%;
    position: absolute;
    top: 0;
    left: -250px;
    background-color: #343a40;
    padding-top: 60px;
    transition: left 0.3s ease;
  }

  .sidebar-menu li {
    display: block;
    width: 100%;
  }

  .sidebar-menu li a {
    padding: 15px 20px;
  }

  .sidebar-menu.open {
    left: 0;
  }

  .submenu {
    padding-left: 40px;
  }

  .submenu li a {
    padding: 10px 20px;
  }

  /* Icon styles */
  .submenu-toggle {
    cursor: pointer;
  }

  .submenu-toggle:after {
    content: '\f107';
    font-family: 'Glyphicons Halflings';
    font-size: 12px;
    float: right;
  }

  .submenu-toggle.active:after {
    content: '\f106';
  }
}

</style>
<ul class="sidebar-menu">
  <li>
    <a href="home.php">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a>
  </li>
  <li>
    <a href="categorie.php">
      <i class="glyphicon glyphicon-indent-left"></i>
      <span>Categories</span>
    </a>
    
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
      <span>Products</span>
    </a>
    <ul class="nav submenu">
       <li><a href="product.php">Manage product</a></li>
       <li><a href="add_product.php">Add product</a></li>
    </ul>
  </li>
  <li>
    <a href="media.php">
      <i class="glyphicon glyphicon-picture"></i>
      <span>Media</span>
    </a>
  </li>
</ul>
<script>
  $(document).ready(function() {
  // For mobile submenu toggle
  $('.submenu-toggle').on('click', function() {
    $(this).toggleClass('active');
    $(this).next('.submenu').slideToggle();
  });

  // Toggle sidebar on small screens (Hamburger menu)
  $('.toggle-sidebar').on('click', function() {
    $('.sidebar-menu').toggleClass('open');
  });
});

</script>