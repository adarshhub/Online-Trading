<?php  include("includes/includedFiles.php");
       include("config/config.php");

$username = $_SESSION['username'];
$query = mysqli_query($con,"SELECT * FROM users WHERE username='$username'");
$row = mysqli_fetch_array($query);

$firstname = ucfirst($row['firstname']);
$lastname = ucfirst($row['lastname']);
$email = $row['email'];
?>

<div class="sub-container">
    <div id="profile-details-container">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="profile-firstname" class="control-label col-sm-3">First Name:</label>
                <input required type="text" class="form-control col-sm-5" name="profile-firstname" id="profile-firstname" value="<?php echo $firstname; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="profile-lastname" class="control-label col-sm-3">Last Name:</label>
                <input required type="text" class="form-control col-sm-5" name="profile-lastname" id="profile-lastname" value="<?php echo $lastname; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="profile-email" class="control-label col-sm-3">Email address:</label>
                <input required type="email" class="form-control col-sm-5" name="profile-email" id="profile-email" value="<?php echo $email; ?>" disabled>
            </div>
            <button type="button" class="btn btn-warning" id="edit-profile-button" onclick="editProfile()">Edit Profile</button>
            <button type="button" class="btn btn-danger" id="change-password-button" data-toggle="modal" data-target="#passwordChangeModal">Change Password</button>
        </form>
    </div>
    <div id="asset-holding-container" >
        <h2>My Orders</h2>
        <ul id="my-orders" class="list-group">
        <li class="alert alert-default order-list-item"><span class="asset-name vertical-center"><strong>Name</strong></span><span class="asset-name vertical-center"><strong>Type</strong></span><span class="asset-name vertical-center"><strong>Volume</strong></span><span class="asset-name vertical-center"><strong>Rate</strong></span></li>
        <div class='alert alert-info' id="no-orders"><strong>No Open Orders!</strong></div>
            <?php 
            $myorder_query = mysqli_query($con,"SELECT asset, num_id FROM orders WHERE placed_by='$username'");
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
                    
                } else {
                    echo "<script>document.getElementById('no-orders').classList.remove('hide');</script>";
                }
            }
            ?>
        </ul>
    </div>
</div>