<?php include "../../config/database.php";
include "../../navbar.php";
session_start();

if(isset($_SESSION["error"])){
  $error = $_SESSION["error"];
  echo "<span>$error</span>";
}

$category = mysqli_query($con,"SELECT * FROM `GVWA`.`categories` ORDER BY `categories`.`name` ASC");
#$category = mysqli_fetch_all($category);

#$returnResult = []; //initialise empty array
while($row = $category->fetch_assoc())
{
    $returnResult[] = $row;
}
$category = $returnResult;

// Is PHP function magic_quotee enabled?
$WarningHtml = '';
if( ini_get( 'magic_quotes_gpc' ) == true ) {
	$WarningHtml .= "<div class=\"warning\">The PHP function \"<em>Magic Quotes</em>\" is enabled.</div>";
}
// Is PHP function safe_mode enabled?
if( ini_get( 'safe_mode' ) == true ) {
	$WarningHtml .= "<div class=\"warning\">The PHP function \"<em>Safe mode</em>\" is enabled.</div>";
}


navigation_bar();
?>
    <section class="pt-4">
        <form action="#" method="GET">
        <div class="row">
          <div class="col">
            <select id="category" name="category" class="custom-select">
                <option value="all" selected="selected">All Categories</option>
                <?php
                    if (! empty($category)) {
                        foreach ($category as $key => $value) { 
                            echo '<option value="' . implode( "",$category[$key]) . '">' . implode("",$category[$key]) . '</option>';
                        }
                    }
                ?>
            </select>
              </div>
                    <input type="text" name="query" />
                    <input type="submit" value="Search" name="submit" />
                    
        </form>
          </div>
        </div>
      </div>
    </section>

    <?php include "../../config/database.php";

    if( isset( $_REQUEST[ 'submit' ] ) ) {
        // Get input
        $category = $_REQUEST[ 'category' ];
        $query = $_REQUEST[ 'query' ];

        // Check database
        if ($category != "all") {
            $raw_query =  "SELECT name,price,ID,image,rating FROM GVWA.items 
            WHERE  ((`name` = '$query') OR (`name` LIKE '%".$query."%')) AND `category` = '$category'";
        } else {
            $raw_query = "SELECT name,price,ID,image,rating FROM GVWA.items
                WHERE (`name` LIKE '%".$query."%');";
        }
        $raw_results = mysqli_query($con,  $raw_query ) or die( '<pre>' . ((is_object($con)) ? mysqli_error($con) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

        ?>
  <section class="pt-4">
  <div class="container">
    <div class="row pt-4" is="dmx-repeat" id="repeat1" dmx-bind:repeat="data_view1.data" key="ListingID">      
        <?php
        // Get results

        while( $product_array = mysqli_fetch_assoc( $raw_results ) ) {
            // Get values
            ?>
  
      <div class="col-12 col-md-6 col-lg-4 mb-4" dmx-animate-move.duration:400="" dmx-animate-enter.duration:400.delay:100="fadeIn"> 
            <div class="card" >
              <div class="card-header" dmx-text="Title"><?php
                $id = $product_array['ID'];
                $name = $product_array['name'];
                echo "<a>$name</a>";?>
                </div>
              <a href=<?php echo "../XSS/index.php?product_id=$id";?>>
              <img class="card-img-top" alt="Item Image" src="<?php echo $product_array["image"]; ?>"></div>
              <ul class="list-group list-group-flush">
              <li class="list-group-item bg-dark text-light">
                
              </li>
              </ul>
              </a>
              <div class="card-body py-2">
                <div class="d-flex">
                <div class="d-block font-weight-bold p-1">Price: </div>
                <div class="d-block p-1" dmx-text="Rating"><?php echo "£".$product_array["price"]; ?></div>
                
                <div class="d-block font-weight-bold p-1">Rating: </div>
                <div class="d-block p-1">
                <?php
                $rating = $product_array["rating"];
                echo $rating;
                echo "<span class='stars'>";
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( round( $rating - .25 ) >= $i ) {
                        echo "<i class='fa fa-star'></i>"; //fas fa-star for v5
                    } elseif ( round( $rating + .25 ) >= $i ) {
                        echo "<i class='fa fa-star-half-o'></i>"; //fas fa-star-half-alt for v5
                    } else {
                        echo "<i class='fa fa-star-o'></i>"; //far fa-star for v5
                    }
                }
                echo '</span>';
                ?>
                </div>
                </div>
                <form method="post"  action="../ShoppingCart/updateShoppingCart.php?action=add&code=<?php echo $product_array["ID"]; ?>
                &price=<?php echo $product_array["price"]; ?>">        
                <div class="cart-action"><input type="text" value = "1" class="product-quantity" name="quantity" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
                </form>
              </div>
      </div>
       
        
    
    <?php
    }

    mysqli_close($con);
}

    unset($_SESSION["error"]);

    ?>
    </div>
    </div>
    </section>



<form action="../../config/reload.php?page_name">
    <input type="hidden" value=<?php echo __DIR__;?>/search.php id="page_name" name="page_name"/>
    <input type="submit" value="Reset Search backend" />
</form>

<form action="../../config/reload.php?page_name">
    <input type="hidden" value=<?php echo __FILE__;?> id="page_name" name="page_name"/>
    <input type="submit" value="Reset Search Frontend" />
</form>

</body>
</html>






