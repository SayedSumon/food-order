<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <?php

            //Check whether the id is set or not
            if(isset($_GET['id']))
            {
                //Get the ID and all other details
                $id = $_GET['id'];
                //Create SQL Query to get all other details
                $sql1 = "SELECT * FROM tbl_food WHERE id=$id";
                //Execute the Query
                $res1 = mysqli_query($conn, $sql1);
                //count the Rows to check whether the id is valid or not
                $count = mysqli_num_rows($res1);
                if($count==1)
                {
                    //Get all the data
                    $row1 = mysqli_fetch_assoc($res1);
                    $title = $row1['title'];
                    $description = $row1['description'];
                    $price = $row1['price'];
                    $current_image = $row1['image_name'];
                    $current_category = $row1['category_id'];
                    $featured = $row1['featured'];
                    $active = $row1['active'];
                }
                else
                {
                    //redirect to manage category with session message
                    $_SESSION['no-food-found'] = "<div class='error'>Food not found.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
            }
            else
            {
                //redirect to Manage Category
                header('location:'.SITEURL.'admin/manage-food.php');
            }        
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price" value="<?php echo $price; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php

                            if($current_image !="")
                            {
                                //Display the Image
                                ?>
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image ?>" width="100px">
                                <?php
                            }
                            else
                            {
                                //Display Message
                                echo "<div class='error'>Image Not Added.</div>";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>
                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category">
                            <?php
                                //Query to Get Active Categories
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                //Execute the Query
                                $res = mysqli_query($conn, $sql);
                                //Count Rows
                                $count = mysqli_num_rows($res);

                                //check whether the Category is selected or not
                                if($count>0)
                                {
                                    //CAtegory Available
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        $category_title = $row['title'];
                                        $category_id = $row['id'];
                                        
                                        ?>
                                        <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                        <?php
                                    }
                                }
                                else
                                {
                                    //Category Not Available 
                                    echo "<option value='0'>Category Not Available.</option>";
                                }

                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes">Yes
                        <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes">Yes
                        <input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary"> 
                    </td>
                </tr>
                              
            </table>
        </form>

        <?php

            if(isset($_POST['submit']))
            {
                //1. Get all the values from our form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $current_image = $_POST['current_image'];
                $category = $_POST['category'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];
            
                //2.Updating New Image if selected
                //check whether the image is selected or not
                if(isset($_FILES['image']['name']))
                {
                    //Get the Image Details
                    $image_name = $_FILES['image']['name'];

                    //check whether the image is available or not
                    if($image_name != "")
                    {
                        //Image Available 
                        //1.Upload the New Image

                        $ext = end(explode('.',$image_name));

                        $image_name = "Food_Name_".rand(000,999).'.'.$ext;
                        
                        $src = $_FILES['image']['tmp_name'];
                        $dst = "../images/food/".$image_name;

                        //Finally upload the Image

                        $upload = move_uploaded_file($src, $dst);

                        //Check whether the image is uploaded or not 
                        //And if the image is not uploaded then we will stop the process and redirect with error message

                        if($upload==false)
                        {
                            //set message
                            $_SESSION ['upload'] = "<div class='error'>Failed to upload Image. </div>";
                            //Redirect to Add CAtegory Page 
                            header('location:'.SITEURL.'admin/add-food.php');
                            //Stop the Process
                            die();
                        }

                        //2.Remove the Current Image if available
                        if($current_image != "")
                        {
                            $remove_path = "../images/food/".$current_image;
                            $remove = unlink($remove_path);

                            //Check whether the image is removed or not
                            //If failed to remove then display message and stop the processs
                            if($remove==false)
                            {
                                //Failed to remove image
                                $_SESSION['failed-remove']="<div class='error'>Failed to remove current Image.</div>"; 
                                header('location:'.SITEURL.'admin/manage-food.php'); 
                                die();
                                //Stop the Process
                            }
                        }
                        
                    }
                    else
                    {
                        $image_name = $current_image;
                    }
                }
                else
                    {
                        $image_name = $current_image;
                    }

                //3. Update the Database 
                $sql2 = "UPDATE tbl_food SET
                    title = '$title',
                    description = '$description',
                    price = '$price', 
                    image_name = '$image_name',
                    category_id = '$category',
                    featured = '$featured',
                    active = '$active' 
                    WHERE id=$id
                ";

                //Execute the Query 
                $res2 = mysqli_query($conn, $sql2);

                //4. Redirect to Manage food with Message
                //Check whether executed or not
                if(($res)==true)
                {
                    //Category Updated
                    $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>"; 
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //failed to update category 
                    $_SESSTON['update'] = "<div class='error'> Failed to Update Category.</div>"; 
                    header('location:'.SITEURI.'admin/manage-food.php');
                }
            }

        ?>

    </div>

</div>

<?php include('partials/footer.php'); ?>