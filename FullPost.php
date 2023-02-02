<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php");
 $SearchQueryParameter = $_GET["id"]; ?>
 <?php 
 if(isset($_POST["Submit"])){
  $Name    = $_POST["CommenterName"];
  $Email   = $_POST["CommenterEmail"];
  $Comment = $_POST["CommenterThoughts"];
  date_default_timezone_set("Asia/Kolkata");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($Name)||empty($Email)||empty($Comment)){
    $_SESSION["ErrorMessage"]= "All fields must be filled out";
    Redirect_to("FullPost.php?id=$SearchQueryParameter");
  }elseif (strlen($Comment)>500) {
    $_SESSION["ErrorMessage"]= "Comment length should be less than 500 characters";
    Redirect_to("FullPost.php?id=$SearchQueryParameter");
  }else{
    // Query to insert comment in DB When everything is fine
  
    $sql  = "INSERT INTO comments(datetime,name,email,comment,approvedby,status, post_id)";
    $sql .= "VALUES(:dateTime,:name,:email,:comment, 'pending', 'OFF', :postIdFromURL)";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt -> bindValue(':dateTime',$DateTime);
    $stmt -> bindValue(':name',$Name);
    $stmt -> bindValue(':email',$Email);
    $stmt -> bindValue(':comment',$Comment);
    $stmt -> bindValue(':postIdFromURL',$SearchQueryParameter);
    $Execute = $stmt -> execute();
    //var_dump($Execute);
    if($Execute){
      $_SESSION["SuccessMessage"]="Comment Submitted Successfully";
      Redirect_to("FullPost.php?id=$SearchQueryParameter");
    }else {
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again !";
      Redirect_to("FullPost.php?id=$SearchQueryParameter");
    }
  }
}

 ?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="Css/Styles.css">
  <title>Document</title>
</head>
<body>
<!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">BlogiFy</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a href="Blog.php?page=1" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">About Us</a>
        </li>
        <li class="nav-item">
          <a href="Blog.php?page=1" class="nav-link">Blog</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Contact Us</a>
        </li>
        
      </ul>
      <ul class="navbar-nav ml-auto">
        <form class="form-inline " action="Blog.php">
          <div class="form-group">
          <input class="form-control mr-2 " type="text" name="Search" placeholder="Search here"value="">
          <button  class="btn btn-primary " name="SearchButton">Go</button>
          </div>
        </form>
      </ul>
      </div>
    </div>
  </nav>
    <div style="height:10px; background:rgb(0, 122, 204);"></div>
  
  
<!-- NAVBAR END -->
    <!-- HEADER -->
    <div class="container">
      <div class="row mt-4">
        <!-- start main area -->
        <div class="col-sm-8" >
          <h1><span style="color:rgb(1, 122, 204);">B</span>logi<span style="color:rgb(1, 122, 204);">FY</span></h1>
          <h1 class="lead">Help Bloggers to promote their blog.</h1>
           <?php
           echo ErrorMessage();
           echo SuccessMessage();
           ?>
          <?php
          if(isset($_GET["SearchButton"])){
            $Search = $_GET["Search"];
            $sql = "SELECT * FROM posts
            WHERE datetime LIKE :search
            OR title LIKE :search
            OR category LIKE :search
            OR post LIKE :search";
            $stmt = $ConnectingDB->prepare($sql);
            $stmt->bindValue(':search','%'.$Search.'%');
            $stmt->execute();
          }
          else{
            $PostIdFromURL = $_GET["id"];
            if (!isset($PostIdFromURL)) {
              $_SESSION["ErrorMessage"]="Bad Request !";
              Redirect_to("Blog.php?page=1");
            }
            $sql  = "SELECT * FROM posts where id='$PostIdFromURL'";
            $stmt = $ConnectingDB-> query($sql);
          }
          
           while ($DataRows = $stmt->fetch()) {
            $PostId          = $DataRows["id"];
            $DateTime        = $DataRows["datetime"];
            $PostTitle       = $DataRows["title"];
            $Category        = $DataRows["category"];
            $Admin           = $DataRows["author"];
            $Image           = $DataRows["image"];
            $PostDescription = $DataRows["post"];
           ?>
           <div class="card">
             <img src="Uploads/<?php echo htmlentities($Image); ?>" style="max-height:450px;" class="img-fluid card-img-top" />
            <div class="card-body">
              <h4 clas="card-title"><?php echo htmlentities($PostTitle); ?></h4>
              <small class="text-muted">Category: <span class="text-dark"> <a href="Blog.php?category=<?php echo htmlentities($Category); ?>"> <?php echo htmlentities($Category); ?> </a></span> & Written by <span class="text-dark"> <a href="Profile.php?username=<?php echo htmlentities($Admin); ?>"> <?php echo htmlentities($Admin); ?></a></span> On <span class="text-dark"><?php echo htmlentities($DateTime); ?></span></small>
                 <!--  <span style="float:right;" class="badge badge-dark text-light">Comments:</span> -->
              <hr>
              <p class="card-text">
                <?php echo htmlentities($PostDescription); ?></p>
            </div>
           </div>

           <?php } ?>
           <!-- comment section start -->
            <span class="FieldInfo">Comments</span>
            <br>
            <?php
          
            $sql  = "SELECT * FROM comments
             WHERE post_id='$SearchQueryParameter' AND status='ON' ";
            $stmt =$ConnectingDB->query($sql);
            while ($DataRows = $stmt->fetch()) {
              $CommentDate   = $DataRows['datetime'];
              $CommenterName = $DataRows['name'];
              $CommentContent= $DataRows['comment'];
            ?>
            <div>
             <div class="media CommentBlock">
              <img class="d-block img-fluid align-self-start" src="images/comment.png" alt="">
              <div class="media-body ml-2">
              <h6 class="lead"><?php echo $CommenterName; ?></h6>
              <p class="small"><?php echo $CommentDate; ?></p>
              <p><?php echo $CommentContent; ?></p>
             </div>
            </div>
           </div>
           <hr>
           <?php } ?>

            <div>
            <form class="" action="FullPost.php?id=<?php echo $SearchQueryParameter ?>" method="post">
              <div class="card mb-3">
                <div class="card-header">
                  <h5 class="FieldInfo">Share your thoughts about this post</h5>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                      </div>
                    <input class="form-control" type="text" name="CommenterName" placeholder="Name" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                      </div>
                    <input class="form-control" type="text" name="CommenterEmail" placeholder="Email" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <textarea name="CommenterThoughts" class="form-control" rows="6" cols="80"></textarea>
                  </div>
                  <div class="">
                    <button type="submit" name="Submit" class="btn btn-success">Submit</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- end main area -->
        <!-- start side main are -->
        <div class="col-sm-4">

        </div>
        <!-- end side main area -->
      </div>
    </div>
  
    <!-- HEADER END -->
<br>
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