<?php require_once("./Includes/DB.php"); ?>
<?php require_once("./Includes/Functions.php"); ?>
<?php require_once("./Includes/Sessions.php"); 
 $SearchQueryParameter = $_GET["id"];?>
 <?php Confirm_Login(); ?>

<?php
if(isset($_POST["Submit"])){
  $PostTitle = $_POST["PostTitle"];
  $Category  = $_POST["Category"];
  $Image     = $_FILES["Image"]["name"];
  $Target    = "./Uploads/".basename($_FILES["Image"]["name"]);
  $PostText  = $_POST["PostDescription"];
  $Admin = "jevin";
  date_default_timezone_set("Asia/Kolkata");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($PostTitle)){
    $_SESSION["ErrorMessage"]= "Title Cant be empty";
    Redirect_to("EditPost.php?id=$SearchQueryParameter");
  }elseif (strlen($PostTitle)<5 and strlen($PostTitle)>49) {
    $_SESSION["ErrorMessage"]= "Post Title constraint violated";
    Redirect_to("EditPost.php?id=$SearchQueryParameter");
  }elseif (strlen($PostText)>9999) {
    $_SESSION["ErrorMessage"]= "Post Description should be less than than 10000 characters";
    Redirect_to("EditPost.php?id=$SearchQueryParameter");
  }else{
   
          if (!empty($_FILES["Image"]["name"])) {
      $sql = "UPDATE posts
              SET title='$PostTitle', category='$Category', image='$Image', post='$PostText'
              WHERE id='$SearchQueryParameter'";
    }else {
      $sql = "UPDATE posts
              SET title='$PostTitle', category='$Category', post='$PostText'
              WHERE id='$SearchQueryParameter'";
    }
    
    $Execute=$ConnectingDB->query($sql);
    move_uploaded_file($_FILES["Image"]["tmp_name"],$Target);
    if($Execute){
      $_SESSION["SuccessMessage"]="Post with id : " .$SearchQueryParameter." updated Successfully";
      Redirect_to("./Posts.php");
    }else {
      $_SESSION["ErrorMessage"]= "Something went wrong. Try Again !";
      Redirect_to("EditPost.php?id=$SearchQueryParameter");
    }
  }
}
 ?>
<html lang="en">
<head>
  <link rel="stylesheet" href="./Css/Styles.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  
  <title>EditPost</title>
</head>
<body>
<!-- NAVBAR -->
  
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">BlogiFY</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a href="MyProfile.php" class="nav-link"> <i class="fas fa-user text-success"></i> My Profile</a>
        </li>
        <li class="nav-item">
          <a href="Dashboard.php" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item">
          <a href="Posts.php" class="nav-link">Posts</a>
        </li>
        <li class="nav-item">
          <a href="Categories.php" class="nav-link">Categories</a>
        </li>
        <li class="nav-item">
          <a href="Admins.php" class="nav-link">Manage Admins</a>
        </li>
        <li class="nav-item">
          <a href="Comments.php" class="nav-link">Comments</a>
        </li>
        <li class="nav-item">
          <a href="Blog.php?page=1" class="nav-link" target="_blank">Live Blog</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto ">
        <li class="nav-item"><a href="Logout.php" class="nav-link text-danger">
          <i class="fas fa-user-times"></i> Logout</a></li>
      </ul>
      </div>
    </div>
  </nav>
      <div style="height:10px; background:rgb(0, 122, 204);"></div>
     <!-- NAVBAR END -->
    <!-- HEADER -->
    <header class="bg-dark text-white py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
          <h1><i class="fas fa-edit" style="color:rgb(0, 122, 204);"></i> Edit Post</h1>
          </div>
        </div>
      </div>
    </header>
    <!-- HEADER END -->
    <!-- main area -->
<section class="container py-2 mb-4">
  <div class="row">
    <div class="offset-lg-1 col-lg-10" style="min-height:400px;">
      <?php
       echo ErrorMessage();
       echo SuccessMessage();
     
      
       $sql  = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
       $stmt = $ConnectingDB ->query($sql);
       while ($DataRows=$stmt->fetch()) {
         $TitleToBeUpdated    = $DataRows['title'];
         $CategoryToBeUpdated = $DataRows['category'];
         $ImageToBeUpdated    = $DataRows['image'];
         $PostToBeUpdated     = $DataRows['post'];
       }
       ?>
      <form class="" action="EditPost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
        <div class="card bg-secondary text-light mb-3">
          <div class="card-body bg-dark">
            <div class="form-group">
              <label for="title"> <span class="FieldInfo"> Post Title: </span></label>
               <input class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo $TitleToBeUpdated; ?>">
            </div>
            <div class="form-group">
              <span class="FieldInfo">Existing Category: </span>
              <?php echo $CategoryToBeUpdated;?>
              <br>
              <label for="CategoryTitle"> <span class="FieldInfo"> Choose Categroy </span></label>
               <select class="form-control" id="CategoryTitle"  name="Category">
                 <?php
                 //Fetchinng all the categories from category table
                 global $ConnectingDB;
                 $sql  = "SELECT id,title FROM category";
                 $stmt = $ConnectingDB->query($sql);
                 while ($DataRows = $stmt->fetch()) {
                   $Id            = $DataRows["id"];
                   $CategoryName  = $DataRows["title"];
                  ?>
                  <option> <?php echo $CategoryName; ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="form=group mb-1">
              <span class="FieldInfo">Existing Image: </span>
              <img  class="mb-2" src="Uploads/<?php echo $ImageToBeUpdated;?>" width="170px"; height="70px"; ><br>
              <label for="imageSelect"><span class="FieldInfo">Select Image</span> </label>
              <div class="custom-file">
              <input class="custom-file-input" type="File" name="Image" id="imageSelect" value="">
              <label for="imageSelect" class="custom-file-label">Select Image </label>
              </div>
            </div>
            <div class="form-group">
              <label for="Post"> <span class="FieldInfo"> Post: </span></label>
              <textarea class="form-control" id="Post" name="PostDescription" rows="8" cols="80">
                <?php echo $PostToBeUpdated;?>
              </textarea>
            </div>
            <div class="row">
              <div class="col-lg-6 mb-2">
                <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back To Dashboard</a>
              </div>
              <div class="col-lg-6 mb-2">
                <button type="submit" name="Submit" class="btn btn-success btn-block">
                  <i class="fas fa-check"></i> Update
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

</section>


    <!-- end main area -->

    <!-- FOOTER -->
    <footer class="bg-dark text-white">
      <div class="container">
        <div class="row">
          <div class="col">
          <p class="lead text-center">Made with <i class="fas fa-heart" style="color:red;"></i>  by team AccessDenied.</p>
          <p class="lead text-center"> <span id="year"></span> &copy; ----All right Reserved.</p>
          </div>
         </div>
      </div>
    </footer>
        <div style="height:10px; background:rgb(0, 122, 204);"></div>
    <!-- FOOTER END-->


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>
  $('#year').text(new Date().getFullYear());
</script>
</body>
</html>  