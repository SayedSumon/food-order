<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Change Password</h1>
        <br><br>

        <?php 
            if(isset($_GET['id']))
            {
                $id=$_GET['id'];
            }
        ?>

        <form action="" method="POST">


            <table class="tbl-30">
                <tr>
                    <td>Current Password: </td>
                    <td>
                        <input type="password" name="current_password" placeholder="Current Password">
                    </td>
                </tr>

                <tr>
                    <td>New Password</td>
                    <td>
                        <input type="password" name="new_password" placeholder="New Password">
                    </td>
                </tr>

                <tr>
                    <td>Confirm Password: </td>
                    <td>
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Change Password" class="btn-secondary">

                    </td>
                </tr>
            </table>
        </form>

        <br><br>
    </div>
</div>


<?php


            //Check wheather the Submit Button is clicked or not
            if(isset($_POST['submit']))
            {
              //echo "clicked";
              
              //1.Get the Data From form
              $id=$_POST['id'];
              $current_password = md5($_POST['current_password']);
              $new_password = md5($_POST['new_password']);
              $confirm_password = md5($_POST['confirm_password']);


              //2. Check whether the user with current ID and Current Password Exists or Not
              $sql = "SELECT * FROM tbl_admin WHERE id=$id AND password='$current_password'";

              //Execute the query
              $res = mysqli_query($conn, $sql);

              if($res==true)
              {
                //Check whether data is available or not 
                $count=mysqli_num_rows($res);

                if($count==1)
                {

                  //User Exists and Password Can be Changed
                  //echo "User Found";
                  //check whether the new password and confirm password match or not 
                  if($new_password==$confirm_password)
                  {
                    //Update the Password
                    $sql2 = "UPDATE tbl_admin SET
                    password='$new_password'
                    WHERE id=$id
                    ";

                    //Execute the query
                    $res2 = mysqli_query($conn, $sql2);

                    //Check whether the query execute or not
                    if($res2==true)
                    {
                      //Display Success Message
                      //Redirect to Manage Admin Page with error Message
                      $_SESSION['change-pwd'] = "<div>Password Changed Successfully.</div>";
                      //Redirect the User
                      header('location:'.SITEURL.'admin/manage-admin.php');
                    }
                    else
                    {
                      //Display Error Message
                      //Redirect to Manage Admin Page with error Message
                      $_SESSION['change-pwd'] = "<div>Failed to change password.</div>";
                      //Redirect the User
                      header('location:'.SITEURL.'admin/manage-admin.php');
                    }
                  }
                  else
                  {
                    //Redirect to Manage Admin Page with error Message
                    $_SESSION['pwd-not-match'] = "<div>Password did not match.</div>";
                    //Redirect the User
                    header('location:'.SITEURL.'admin/manage-admin.php');

                  }
                  
                }
                else
                {
                  //User Does not Exist Set Message and Redirect
                  $_SESSION['user-not-found'] = "<div>User Not Found.</div>";
                  //Redirect the User
                  header('location:'.SITEURL.'admin/manage-admin.php');



                }
              }

              //3. Check whether the New Password and Confirm Password Match or Not

              //4. Change Password if all above is true
            }

?>




<?php include('partials/footer.php'); ?>