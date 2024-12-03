<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
  function page_require_level($require_level) {
    global $session;
    $current_user = current_user();
    
    // Check if the user is logged in
    if (!$session->isUserLoggedIn(true)) {
        $session->msg('d', 'Please login...');
        redirect('index.php', false);
    }
    
    // Check if the user level is available
    if (empty($current_user) || !isset($current_user['user_level'])) {
        $session->msg('d', 'User level is undefined.');
        redirect('home.php', false);
    }
    
    // Get the user's group level status
    $login_level = find_by_groupLevel($current_user['user_level']);
    
    // Check if the group status is deactivated
    if (isset($login_level['group_status']) && $login_level['group_status'] === '0') {
        $session->msg('d', 'This level user has been banned!');
        redirect('home.php', false);
    }
    
    // Check if the user's level meets the required level
    if ((int)$current_user['user_level'] <= (int)$require_level) {
        return true;
    } else {
        $session->msg("d", "Sorry! You don't have permission to view this page.");
        redirect('home.php', false);
    }
}

   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
   function join_product_table($offset, $limit) {
    global $db;
    $sql  = "SELECT p.id, p.name, p.melekiya, p.quantity, p.buy_price, p.sale_price, p.date,p.last_upadate,p.lastly_added_product,";
    $sql .= " c.name AS categorie, m.file_name AS image";
    $sql .= " FROM products p";
    $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql .= " LEFT JOIN media m ON m.id = p.media_id";
    $sql .= " LIMIT {$limit} OFFSET {$offset}";
    
    return find_by_sql($sql);
}
function find_all_revenues($offset, $limit) {
  global $db;
  $sql  = "SELECT * FROM daily_sold_money ";
  $sql .= "ORDER BY date DESC ";
  $sql .= "LIMIT {$offset}, {$limit}";
  return find_by_sql($sql);
}

function count_all_revenues() {
  global $db;
  $sql    = "SELECT COUNT(id) AS total FROM daily_sold_money";
  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);
  return (int)$row['total'];
}

function count_all_products() {
  global $db;
  $sql = "SELECT COUNT(id) AS total FROM products";
  $result = $db->query($sql);
  $row = $result->fetch_assoc();
  return (int)$row['total'];
}

  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function dailySales($year, $month, $offset = 0, $limit = 10) {
  global $db;
  
  // Get the current date in 'YYYY-MM-DD' format
  $current_date = date('Y-m-d');
  
  // SQL query to fetch sales for the current day with pagination
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%d') AS date, p.name,";
  $sql .= " p.melekiya, SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= " SUM(s.qty) AS total_sales";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE(s.date) = '{$current_date}'";  // Filter by the current day
  $sql .= " GROUP BY DATE(s.date), s.product_id";
  $sql .= " LIMIT {$limit} OFFSET {$offset}";  // Implementing limit and offset for pagination
  
  return find_by_sql($sql);
}

function count_daily_sales($year, $month) {
  global $db;
  
  $sql = "SELECT COUNT(DISTINCT s.product_id) AS total_sales_count";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE YEAR(s.date) = '{$year}' AND MONTH(s.date) = '{$month}'";
  
  $result = find_by_sql($sql);
  
  return !empty($result) ? $result[0]['total_sales_count'] : 0;
}





/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name,";
  $sql .= " SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  // Adjust the WHERE clause to match year and month (e.g., '2024-11')
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m') = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT(s.date, '%Y-%m-%e'), s.product_id"; // Group by the full date
  $sql .= " ORDER BY DATE_FORMAT(s.date, '%Y-%m-%d') ASC"; // Order by full date
  return find_by_sql($sql);
}


// for Gross 


function find_total_sold_money($start_date, $end_date) {
  global $db;
  $sql = "SELECT SUM(total_revenue) AS total_sold_money FROM daily_sold_money WHERE date BETWEEN '{$start_date}' AND '{$end_date}'";
  $result = $db->query($sql);
  return ($result && $db->num_rows($result) > 0) ? $db->fetch_assoc($result)['total_sold_money'] : 0;
}

// Function to calculate Wechi (adjust based on your requirements)
function calculate_wechi($start_date, $end_date) {
  global $db;
  $sql = "SELECT SUM(wechi) AS total_wechi FROM daily_sold_money WHERE date BETWEEN '{$start_date}' AND '{$end_date}'";
  $result = $db->query($sql);
  return ($result && $db->num_rows($result) > 0) ? $db->fetch_assoc($result)['total_wechi'] : 0;
}

// Function to calculate total cost of products in stock based on their buying price
function calculate_stock_value() {
  global $db;
  $sql = "SELECT SUM(quantity * buy_price) AS stock_value FROM products";
  $result = $db->query($sql);
  return ($result && $db->num_rows($result) > 0) ? $db->fetch_assoc($result)['stock_value'] : 0;
}

// Function to calculate total buying cost of sold products within a date range
// Function to calculate the total cost of sold products
// Function to calculate the total cost of sold products using the buying price
function calculate_sold_product_cost($start_date, $end_date) {
  global $db;
  $sql = "SELECT SUM(s.qty * p.buy_price) AS total_sold_product_cost 
          FROM sales AS s 
          JOIN products AS p ON s.product_id = p.id 
          WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $result = $db->query($sql);
  return ($result && $db->num_rows($result) > 0) ? $db->fetch_assoc($result)['total_sold_product_cost'] : 0;
}

function join_product_table_out_of_stock($offset = 0, $limit = 15) {
  global $db;

  $sql = "SELECT * FROM products WHERE quantity = 0 LIMIT $limit OFFSET $offset";
  $result = $db->query($sql);
  return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to count the total number of out-of-stock products
function count_out_of_stock_products() {
  global $db;

  $sql = "SELECT COUNT(*) FROM products WHERE quantity = 0";
  $result = $db->query($sql);
  $row = $result->fetch_row();
  return $row[0];  // Return the count
}


function join_product_table_low_stock($offset = 0, $limit = 15) {
  global $db;

  // Query to get products where quantity is less than 5
  $sql = "SELECT * FROM products WHERE quantity < 5 LIMIT $limit OFFSET $offset";
  $result = $db->query($sql);
  return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to count the total number of low-stock products (quantity < 5)
function count_low_stock_products() {
  global $db;

  // Query to count products where quantity is less than 5
  $sql = "SELECT COUNT(*) FROM products WHERE quantity < 5";
  $result = $db->query($sql);
  $row = $result->fetch_row();
  return $row[0];  // Return the count
}


?>
