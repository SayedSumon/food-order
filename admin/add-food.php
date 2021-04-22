<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>
        <br></br>

        <?php

            if(isset($_SESSION['upload']))
            {
               echo $_SESSION['upload'];
               unset($_SESSION['upload']);
            }
        ?>
        <br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Title of the Food">
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description of the Food."></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category" >

                        <?php

                            //Create e PHP code to display categories from Database 
                            //1. Create SQL to get all active categories from database 
                            $sql= "SELECT * FROM tbl_category WHERE active='Yes'";

                            //executing query
                            $res = mysqli_query($conn, $sql);

                            //Count Rows to check whether we have categories or not 
                            $count = mysqli_num_rows($res);

                            //If count is greater than zero, we have categories else we dont have categories
                            if($count>0)
                            {
                                //We have categories
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    //get the details of categories
                                    $id = $row['id'];
                                    $title = $row['title'];
                                    ?>
                                    <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
                                    <?php
                                }
                            }
                            else
                            {
                                //we donot have categories
                                ?>
                                <option value="0">No Category Found</option>
                                <?php
                            }

                            //2. Display on Drpopdown
                        ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary"> 
                    </td>
                </tr>
        
            </table>        
        </form>

        <?php

            //Check whether the button is clicked or not
            if(isset($_POST['submit']))
            {
                //Add the Food in Database: 
            
                //1. Get the Data from Form
                $title = $_POST['title']; 
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];

                //check whether radion button for featured and active are checked or not
                if(isset($_POST['featured']))
                {
                    $featured = $_POST['featured'];
                }
                else{
                    $featured = "No";
                }
                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }
                else{
                    $active = "No";
                }

                //2. Upload the Image if selected

                if(isset($_FILES['image']['name']))
                {
                    //Upload the image
                    //to upload image we need image name, source path and destination path 
                    
                    $image_name = $_FILES['image']['name'];
                   
                    if($image_name != "")
                    {
                        $ext = end(explode('.',$image_name));

                        $image_name = "Food_Name_".rand(000,999).'.'.$ext;
                        
                        $src = $_FILES['image']['tmp_name'];
                        $dst = "../images/food/".$image_name;

                        //Finally upload the Image
                        $upload = move_uploaded_file($src, $dst);

                        //Check whether the image is uploaded or not 
                        if($upload==false)
                        {
                            //set message
                            $_SESSION ['upload'] = "<div class='error'>Failed to upload Image. </div>";
                            //Redirect to Add CAtegory Page 
                            header('location:'.SITEURL.'admin/add-category.php');
                            //Stop the Process
                            die();
                        }
                    }
                }

                else
                {
                    $image_name="";
                }

                //3. Insert Into Database

                $sql2 = "INSERT INTO tbl_food SET
                    title='$title',
                    description = '$description',
                    price = $price,
                    image_name='$image_name',
                    category_id= '$category',
                    featured='$featured',
                    active='$active'
                ";

                $res2 =  mysqli_query($conn, $sql2);

                //4. Redirect with MEssage to Manage Food page
                if($res2==true)
                {
                    $_SESSION['add'] = "<div class='success'>Food Added Successfuly.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else{
                    $_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
            }
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>