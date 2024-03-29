<?php 


/*
 ========================
 ==  Items Page
 ==  http://gregfranko.com/jquery.selectBoxIt.js/index.html
 ==  http://gregfranko.com/jquery.selectBoxIt.js/customDownload.html
 ==  http://jqueryui.com/download/
 ========================
*/

ob_start(); // Output Buffering Start

session_start(); 
 
$pageTitle = 'Items';


if(isset($_SESSION['Username'])){
	  
      include 'init.php';
       
      $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


      if($do == 'Manage'){ 

         $stmt = $con->prepare("SELECT 
                                    items.*, 
                                    categories.Name AS category_name, 
                                    users.Username
                                FROM 
                                    items
                                INNER JOIN 
                                    categories 
                                ON 
                                    categories.ID = items.Cat_ID
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserID = items.Member_ID
                                ");

         // Execute The Statement

         $stmt->execute();

         // Assign To Variable

         $items = $stmt->fetchAll();

       ?>
          
         <h1 class="text-center">Manage Items</h1> 
         <div class="container">
            <div class="table-responsive">
               <table class="main-table text-center table table-bordered">
                  <tr>
                     <td>#ID</td>
                     <td>Name</td>
                     <td>Description</td>
                     <td>Price</td>
                     <td>Adding Date</td>
                     <td>Category</td>
                     <td>Username</td>
                     <td>Control</td>
                  </tr>

                  <?php 

                foreach ($items as $item){
                echo "<tr>";
                  echo "<td>" . $item['Item_ID'] . "</td>";
                  echo "<td>" . $item['Name'] . "</td>";
                  echo "<td>" . $item['Description'] . "</td>";
                  echo "<td>" . $item['Price'] . "</td>";
                  echo "<td>" . $item['Add_Date'] . "</td>";
                  echo "<td>" . $item['category_name'] . "</td>";
                  echo "<td>" . $item['Username'] . "</td>";
                  echo "<td>
                        <a href='items.php?do=Edit&itemid=". $item['Item_ID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='items.php?do=Delete&itemid=". $item['Item_ID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                       echo "</td>";
              echo "</tr>";
           }
        ?>
       </table>
     </div>
     <a href="items.php?do=Add" class="btn btn-sm btn-primary">
       <i class="fa fa-plus"></i> New Item</a>
 </div>
         
     <?php

      }elseif($do == 'Add'){  ?>

       <h1 class="text-center">Add New Item</h1> 
         <div class="container">
         <form class="form-horizontal" action="?do=Insert" method="POST">
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Name</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                        type="text" 
                        name="name" 
                        class="form-control" 
                        placeholder="Name of The Item">
               </div>
            </div>
             <!-- End Name Field -->

            <!-- Start Description Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Description</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                         type="text" 
                         name="description" 
                         class="form-control" 
                         placeholder="Description of The Item">
               </div>
            </div>
            <!-- End Description Field -->

            <!-- Start Price Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Price</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                         type="text" 
                         name="price" 
                         class="form-control" 
                         placeholder="Price of The Item">
               </div>
            </div>
            <!-- End Price Field -->

             <!-- Start Country Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Country</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                         type="text" 
                         name="country" 
                         class="form-control" 
                         placeholder="Country of Made">
               </div>
            </div>
            <!-- End Country Field -->

             <!-- Start Status Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Status</label>
               <div class="col-sm-10 col-md-6">
                  <select  name="status">
                      <option value="0">...</option>
                      <option value="1">New</option>
                      <option value="2">Like New</option>
                      <option value="3">Used</option>
                      <option value="4">Very Old</option>
                  </select>
               </div>
            </div>
            <!-- End Status Field -->

             <!-- Start Members Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Member</label>
               <div class="col-sm-10 col-md-6">
                  <select  name="member">
                      <option value="0">...</option>
                      <?php

                         $stmt = $con->prepare("SELECT * FROM users");
                         $stmt->execute();
                         $users = $stmt->fetchAll();

                         foreach ($users as $user) {
                           
                            echo "<option value='". $user['UserID'] ."'>". $user['Username'] ."</option>";
                         }

                      ?>
                  </select>
               </div>
            </div>
            <!-- End Members Field -->

             <!-- Start Categories Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Category</label>
               <div class="col-sm-10 col-md-6">
                  <select  name="category">
                      <option value="0">...</option>
                      <?php

                         $stmt2 = $con->prepare("SELECT * FROM categories");
                         $stmt2->execute();
                         $cats = $stmt2->fetchAll();

                         foreach ($cats as $cat) {
                           
                            echo "<option value='". $cat['ID'] ."'>". $cat['Name'] ."</option>";
                         }

                      ?>
                  </select>
               </div>
            </div>
            <!-- End Categories Field -->

            <!-- Start Submit Field -->
            <div class="form-group">
               <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
               </div>
            </div>
            <!-- End Submit Field -->
           
         </form>
         </div>

      <?php 

      }elseif($do == 'Insert'){

          if($_SERVER['REQUEST_METHOD'] == 'POST'){

                echo "<h1 class='text-center'>Insert Item</h1>";
                echo "<div class='container'>";
             
                // Get Variables From The Form

                $name     =  $_POST['name'];
                $desc     =  $_POST['description'];
                $price    =  $_POST['price'];
                $country  =  $_POST['country'];
                $status   =  $_POST['status'];
                $member   =  $_POST['member'];
                $cat      =  $_POST['category'];



                // Validate The Form
                
                $formErrors = array();
                
                if(empty($name)){

                   $formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
                }

                if(empty($desc)){

                   $formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
                }


                if(empty($price)){

                   $formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
                }

                if(empty($country)){

                   $formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
                }

                if($status == 0){

                   $formErrors[] = 'You Must Choose the <strong>Status</strong>';
                }
 

                 if($member == 0){

                   $formErrors[] = 'You Must Choose the <strong>Member</strong>';
                }


                if($cat == 0){

                   $formErrors[] = 'You Must Choose the <strong>Category</strong>';
                }

                

                // Loop Into Errors Array And Echo It

                  foreach ($formErrors as $error) {
                   
                   echo '<div class="alert alert-danger">' . $error .'</div>';

                  }


                 // Check If There 's No Error Proceed The Update Operation'
                
                  if(empty($formErrors)){

                      // Insert Userinfo In Database
                      $stmt = $con->prepare("INSERT INTO 
                                             items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID)
                                             VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember)");
                      $stmt->execute(array(

                          'zname'    => $name,
                          'zdesc'    => $desc,
                          'zprice'   => $price,
                          'zcountry' => $country,
                          'zstatus'  => $status,
                          'zcat'     => $cat,
                          'zmember'  => $member

                      ));

                      // // Echo Success Message
                      $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

                      redirectHome($theMsg, 'back');


                 } 

          }else{
            
             echo "<div class='container'>";

             $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

             redirectHome($theMsg);

             echo "</div>";

          }

          echo "</div>";
            

      }elseif($do == 'Edit'){
         
      // Check If Get Request item Is Numeric & Get The Integer Value Of It
      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; 

      // Select All Data Depend On This ID ()
      $stmt = $con->prepare("SELECT * 
                             FROM items 
                             WHERE Item_ID = ?");
      
      
      // Execute Query
      $stmt->execute(array($itemid));

      // Fetch The Data
      $item = $stmt->fetch();

      // The Row Count
      $count = $stmt->rowCount();

      // If There's Such ID Show Form
      if($count > 0){ ?>

          <h1 class="text-center">Edit Item</h1> 
         <div class="container">
         <form class="form-horizontal" action="?do=Update" method="POST">
           <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Name</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                        type="text" 
                        name="name" 
                        class="form-control" 
                        placeholder="Name of The Item"
                        value="<?php echo $item['Name']; ?>">
               </div>
            </div>
             <!-- End Name Field -->

            <!-- Start Description Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Description</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                         type="text" 
                         name="description" 
                         class="form-control" 
                         placeholder="Description of The Item"
                         value="<?php echo $item['Description']; ?>">
               </div>
            </div>
            <!-- End Description Field -->

            <!-- Start Price Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Price</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                         type="text" 
                         name="price" 
                         class="form-control" 
                         placeholder="Price of The Item"
                         value="<?php echo $item['Price']; ?>">
               </div>
            </div>
            <!-- End Price Field -->

             <!-- Start Country Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Country</label>
               <div class="col-sm-10 col-md-6">
                  <input 
                         type="text" 
                         name="country" 
                         class="form-control" 
                         placeholder="Country of Made"
                         value="<?php echo $item['Country_Made']; ?>">
               </div>
            </div>
            <!-- End Country Field -->

             <!-- Start Status Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Status</label>
               <div class="col-sm-10 col-md-6">
                  <select  name="status">
                      <option value="1" <?php if($item['Status'] == 1){ echo 'selected'; }?>>New</option>
                      <option value="2" <?php if($item['Status'] == 2){ echo 'selected'; }?>>Like New</option>
                      <option value="3" <?php if($item['Status'] == 3){ echo 'selected'; }?>>Used</option>
                      <option value="4" <?php if($item['Status'] == 4){ echo 'selected'; }?>>Very Old</option>
                  </select>
               </div>
            </div>
            <!-- End Status Field -->

             <!-- Start Members Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Member</label>
               <div class="col-sm-10 col-md-6">
                  <select  name="member">
                      <?php

                         $stmt = $con->prepare("SELECT * FROM users");
                         $stmt->execute();
                         $users = $stmt->fetchAll();

                         foreach ($users as $user) {
                           
                            echo "<option value='". $user['UserID'] ."'";
                            if($item['Member_ID'] == $user['UserID']){ echo 'selected'; }
                            echo ">". $user['Username'] ."</option>";
                         }

                      ?>
                  </select>
               </div>
            </div>
            <!-- End Members Field -->

             <!-- Start Categories Field -->
            <div class="form-group form-group-lg">
               <label class="col-sm-2 control-label">Category</label>
               <div class="col-sm-10 col-md-6">
                  <select  name="category">
                      <?php

                         $stmt2 = $con->prepare("SELECT * FROM categories");
                         $stmt2->execute();
                         $cats = $stmt2->fetchAll();

                         foreach ($cats as $cat) {
                           
                            echo "<option value='". $cat['ID'] ."'";
                            if($item['Cat_ID'] == $cat['ID']){ echo 'selected'; }
                            echo ">". $cat['Name'] ."</option>";
                         }

                      ?>
                  </select>
               </div>
            </div>
            <!-- End Categories Field -->

            <!-- Start Submit Field -->
            <div class="form-group">
               <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="Save Item" class="btn btn-primary btn-sm">
               </div>
            </div>
            <!-- End Submit Field -->
           
         </form>
         </div>

        <?php 
        
        // If There's No Such ID, Show Error Message

        }else{
             
             echo "<div class='container'>";

             $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

             redirectHome($theMsg, 'back');

             echo "</div>";
        }
   

      }elseif($do == 'Update'){


      echo "<h1 class='text-center'>Update Item</h1>";
          echo "<div class='container'>";

          if($_SERVER['REQUEST_METHOD'] == 'POST'){
             
                // Get Variables From The Form

                $id       =  $_POST['itemid'];
                $name     =  $_POST['name'];
                $desc     =  $_POST['description'];
                $price    =  $_POST['price'];
                $country  =  $_POST['country'];
                $status   =  $_POST['status'];
                $cat      =  $_POST['category'];
                $member   =  $_POST['member'];
            

                 
                // Validate The Form
                
                $formErrors = array();
                
                if(empty($name)){

                   $formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
                }

                if(empty($desc)){

                   $formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
                }


                 if(empty($price)){

                   $formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
                 }

                 if(empty($country)){

                   $formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
                 }

                 if($status == 0){

                   $formErrors[] = 'You Must Choose the <strong>Status</strong>';
                 }
 

                 if($member == 0){

                   $formErrors[] = 'You Must Choose the <strong>Member</strong>';
                 }


                 if($cat == 0){

                   $formErrors[] = 'You Must Choose the <strong>Category</strong>';
                 }

                

                // Loop Into Errors Array And Echo It

                  foreach ($formErrors as $error) {
                   
                   echo '<div class="alert alert-danger">' . $error .'</div>';

                  }



                // Check If There 's No Error Proceed The Update Operation'
                
                if(empty($formErrors)){

                // Update The Database With THis Info
                $stmt = $con->prepare("UPDATE 
                                           items 
                                       SET 
                                           Name = ?, 
                                           Description = ?, 
                                           Price = ?,
                                           Country_Made = ?,
                                           Status = ?,
                                           Cat_ID = ?,
                                           Member_ID = ?
                                       WHERE 
                                           Item_ID = ?");

                $stmt->execute(array(
                                  $name,
                                  $desc, 
                                  $price, 
                                  $country, 
                                  $status,
                                  $cat,
                                  $member,
                                  $id));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                
                redirectHome($theMsg, 'back');
                 
              } 

           }else{

                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

                redirectHome($theMsg);
           }

            echo "</div>";

      }elseif($do == 'Delete'){

        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";

       // Check If Get Request ItemID Is Numeric & Get The Integer Value Of It
       $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; 

      // Select All Data Depend On This ID ()
       
       $check = checkItem('Item_ID', 'items' , $itemid); 


      // If There's Such ID Show The Form
       if($check > 0){ 

            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
            $stmt->bindParam(":zid", $itemid);
            $stmt->execute();

            // Echo Success Message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

            redirectHome($theMsg);

        }else{

             $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

             redirectHome($theMsg);

        }
    
        echo '</div>';


      }elseif($do == 'Approve'){

           
      }

      include $tpl . 'footer.php';

}else{

    header('Location:index.php');

    exit();

}

ob_end_flush(); // Release The Output
         
      
         
 