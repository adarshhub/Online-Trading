<?php  include("includes/includedFiles.php");
       include("config/config.php");

$username = $_SESSION['username'];
$query = mysqli_query($con,"SELECT * FROM users WHERE username='$username'");
$row = mysqli_fetch_array($query);

$firstname = ucfirst($row['firstname']);
$lastname = ucfirst($row['lastname']);
$email = $row['email'];
?>

<div class="sub-container form-inline">
    <div class="col-sm-8 mb-2 row-sm-10" style="height: 88vh;">
        <div id="profile-details-container" class="form-inline">
            <div id="profile-inputs" class="col-sm-9">
                <div class="form-inline mb-2">
                    <label for="profile-firstname" class="control-label col-sm-3">First Name:</label>
                    <input required type="text" class="form-control col-sm-4" name="profile-firstname" id="profile-firstname" value="<?php echo $firstname; ?>" disabled>
                </div>
                <div class="form-inline mb-2">
                    <label for="profile-lastname" class="control-label col-sm-3">Last Name:</label>
                    <input required type="text" class="form-control col-sm-4" name="profile-lastname" id="profile-lastname" value="<?php echo $lastname; ?>" disabled>
                </div>
                <div class="form-inline mb-2">
                    <label for="profile-email" class="control-label col-sm-3">Email address:</label>
                    <input required type="email" class="form-control col-sm-4" name="profile-email" id="profile-email" value="<?php echo $email; ?>" disabled>
                </div>
            </div>  
            <div id="profile-btns" class="col-sm-1">
                <button type="button" class="btn btn-warning mb-2" id="edit-profile-button" onclick="editProfile()">Edit Profile</button>
                <button type="button" class="btn btn-danger mb-2" id="change-password-button" data-toggle="modal" data-target="#passwordChangeModal">Change Password</button>
            </div>
        </div>
        <div id="asset-holding-container" >
            <h2 class="display-4">My Orders</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Volume</th>
                        <th scope="col">Rate</th>
                    </tr>
                </thead>
            <tbody id="my-orders">
            </tbody>
            </table>
            <div class='alert alert-info' id="no-orders"><strong>No Open Orders!</strong></div>
            <?php 

                function get_my_orders(){
                    global $con, $username;

                    $myorder_query = mysqli_query($con,"SELECT asset, num_id FROM orders WHERE placed_by='$username'");

                    if(mysqli_num_rows($myorder_query) > 0){

                        while($row = mysqli_fetch_array($myorder_query)){

                            $asset = $row['asset'];
                            $num_id = $row['num_id'];

                            $myasset_query= mysqli_query($con,"SELECT * from $asset WHERE placed_by='$username' AND id='$num_id'");

                            if(mysqli_num_rows($myasset_query) > 0){
                                $order_details = mysqli_fetch_array($myasset_query);

                                $type = $order_details['order_type'];
                                $volume = $order_details['volume'];
                                $rate = $order_details['rate'];

                                echo "<script>init_order_list('$asset', '$volume', '$rate', '$type');
                                document.getElementById('no-orders').classList.add('hide');
                                </script>";
                                
                            } 
                        }
                        
                    } 
                    else {
                        echo "<script>document.getElementById('no-orders').classList.remove('hide');</script>";
                    }
                }

                get_my_orders();
            ?>
            </ul>
        </div>
    </div>
    <div class="col-sm-3" style="height: 88vh;">
        <div id="balance-container" >
        <h2 class="display-4 mb-2">Balance</h2>
        <div class="form-inline"><h2 class="col-sm-4">INR</h2><strong><span class="asset-name col-sm-3" id="inr-balance">0</span></strong></div>
        <ul id="my-balance" class="list-group">  
            <?php 

            $mybalance_query = mysqli_query($con,"SELECT asset, amount FROM balance WHERE username='$username'");
            
            if(mysqli_num_rows($mybalance_query) > 0){
                           
                $asset_array = array();
                $amount_array = array();
    
                while($row = mysqli_fetch_array($mybalance_query)){
                    $asset = $row['asset'];
                    $amount = $row['amount'];
    
                    array_push($asset_array,$asset);
                    array_push($amount_array,$amount);
                }
    
                $balance = new \stdClass();
    
                $balance->assets = $asset_array;
                $balance->amounts = $amount_array;
    
                $obj = json_encode($balance);
    
    
                echo "<script>init_balance($obj);</script>";
            }
    
            ?>
            </ul>
        </div>
    </div>
</div>