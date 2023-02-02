<?php require_once("./Includes/DB.php"); ?>
<?php require_once("./Includes/Functions.php"); ?>
<?php require_once("./Includes/Sessions.php"); 
 $SearchQueryParameter = $_GET["id"];?>
 <?php Confirm_Login(); ?>

<?php
$sql  = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
$stmt = $ConnectingDB ->query($sql);
while ($DataRows=$stmt->fetch()) {
  $TitleToBeDeleted    = $DataRows['title'];
  $CategoryToBeDeleted = $DataRows['category'];
  $ImageToBeDeleted    = $DataRows['image'];
  $PostToBeDeleted     = $DataRows['post'];
}

if(isset($_POST["Submit"])){
   
    global $ConnectingDB;
    $sql = "DELETE FROM posts WHERE id='$SearchQueryParameter'";
    $Execute =$ConnectingDB->query($sql);
  
    if($Execute){
      $Target_Path_To_DELETE_Image = "./Uploads/$ImageToBeDeleted";
      unlink($Target_Path_To_DELETE_Image);
      $_SESSION["SuccessMessage"]="Post DELETED Successfully";
      Redirect_to("Posts.php");
    }else {
      $_SESSION["ErrorMessage"]= "Something went wrong. Try Again !";
      Redirect_to("Posts.php");
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
  
  <title>DeletePost</title>
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
          <h1><i class="fas fa-edit" style="color:rgb(0, 122, 204);"></i> Delete Post</h1>
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
       ?>
      <form class="" action="DeletePost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
        <div class="card bg-secondary text-light mb-3">
          <div class="card-body bg-dark">
            <div class="form-group">
              <label for="title"> <span class="FieldInfo"> Post Title: </span></label>
               <input disabled class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo $TitleToBeDeleted; ?>">
            </div>
            <div class="form-group">
              <span class="FieldInfo">Existing Category: </span>
              <?php echo $CategoryToBeDeleted;?>
              <br>
            </div>
            <div class="form-group">
              <span class="FieldInfo">Existing Image: </span>
              <img  class="mb-1" src="Uploads/<?php echo $ImageToBeDeleted;?>" width="170px"; height="70px"; >
            </div>
            <div class="form-group">
              <label for="Post"> <span class="FieldInfo"> Post: </span></label>
              <textarea disabled class="form-control" id="Post" name="PostDescription" rows="8" cols="80">
                <?php echo $PostToBeDeleted;?>
              </textarea>
            </div>
            <div class="row">
              <div class="col-lg-6 mb-2">
                <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back To Dashboard</a>
              </div>
              <div class="col-lg-6 mb-2">
                <button type="submit" name="Submit" class="btn btn-danger btn-block">
                  <i class="fas fa-trash"></i> Delete
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