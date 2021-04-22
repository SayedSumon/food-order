<?php

    //Include constants File 
    include('../config/constants.php');

    //Check whether the id and image_name value is set or not
    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        //Get the value and Delete
        $id = $_GET['id'];
        $image_name = $_GET['image_name']; 
        //Remove the physical image file is available 
        if($image_name != "")
        {
            //image is Available. So remove it 
            $path = "../images/food/".$image_name; 
            //Remove the Image 
            $remove = unlink($path); 
            //if failed to remove image then add an error message and stop the process
            if($remove==false) 
            {
                //Set the Session Message 
                $_SESSTON['remove'] = "<div class='error'>Failed to Remove Category Image.</div>";
                //Redirect to Manage food page 
                header('location:'.SITEURL.'admin/manage-food.php'); 
                //Stop the Process 
                die(); 
            }
        }
        //Delete Data from Database
        $sql = "DELETE FROM tbl_food WHERE id=$id";

        //Execute the Query 
        $res = mysqli_query($conn, $sql);

        //Check whether the data is delete from database or not

        if($res = true)
        {
            //SET Success Message and Redirect
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
            //Redirect to Manage Category 
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //Set Fail Message and Redirecs
            $_SESSION['delete'] = "<div class='error'>Failed to Delete category.</div>";
            //Redirect to Manage food 
            header('location:'.SITEURL.'admin/manage-food.php');
        }
    }
    else
    {
        //redirect to Manage food page
        header('location:'.SITEURL.'admin/manage-food.php');
    }
?>