<?php  include("includes/includedFiles.php");
       include("config/config.php");

$username = $_SESSION['username'];
$query = mysqli_query($con,"SELECT * FROM users WHERE username='$username'");
$row = mysqli_fetch_array($query);

$firstname = ucfirst($row['firstname']);
$lastname = ucfirst($row['lastname']);
$email = $row['email'];
?>

<div>
    <div id="profile-details-container">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="profile-firstname" class="control-label col-sm-5">First Name:</label>
                <input required type="text" class="form-control col-sm-10" name="profile-firstname" id="profile-firstname" value="<?php echo $firstname; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="profile-lastname" class="control-label col-sm-5">Last Name:</label>
                <input required type="text" class="form-control col-sm-10" name="profile-lastname" id="profile-lastname" value="<?php echo $lastname; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="profile-email" class="control-label col-sm-5">Email address:</label>
                <input required type="email" class="form-control col-sm-10" name="profile-email" id="profile-email" value="<?php echo $email; ?>" disabled>
            </div>
            <button type="button" class="btn btn-warning" id="edit-profile-button" onclick="editProfile()">Edit Profile</button>
            <button type="button" class="btn btn-danger" id="change-password-button" data-toggle="modal" data-target="#passwordChangeModal">Change Password</button>
        </form>
    </div>
    <div id="asset-holding-container" >
        <h2>My Orders</h2>
        <ul id="my-orders" class="list-group">
            <?php 
            $myorder_query = mysqli_query($con,"SELECT asset, num_id FROM orders WHERE placed_by='$username'");
            while($row = mysqli_fetch_array($myorder_query)){

                $asset = $row['asset'];
                $num_id = $row['num_id'];

                $myasset_query= mysqli_query($con,"SELECT * from $asset WHERE placed_by='$username' AND id='$num_id'");
                $order_details = mysqli_fetch_array($myasset_query);

                $type = $order_details['order_type'];
                $volume = $order_details['volume'];
                $rate = $order_details['rate'];

                echo "<script>init_order_list('$asset', '$volume', '$rate', '$type')</script>";
                
            }
            ?>
        </ul>
    </div>
</div>