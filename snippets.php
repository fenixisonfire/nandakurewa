<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Snippets</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand">
                  Welcome
                  <?php
                    session_start();
                    $username = $_SESSION["name"];
                    echo " $username";
                  ?>
                </a>
            </div>

            <?php
              $db = new PDO("mysql:dbname=simpsons;host=localhost", "root", "");
              $temp_db = $db->query("SELECT id FROM students WHERE students.name = '$username';");
              $userid = $temp_db->fetchColumn();
            ?>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profile.php?userid=<?=$userid?>&re=1">Profile</a></li>
                    <li><a href="snippets.php?userid=<?=$userid?>">My Snippets</a></li>
                    <li><a href="file.php?userid=<?=$userid?>">Upload File</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>
                          <?php
                          $userid_of_profile = $_GET["userid"];
                          $temp_db = $db->query("SELECT name FROM students WHERE students.id = '$userid_of_profile';");
                          $username_of_profile = $temp_db->fetchColumn();
                            if($username == $username_of_profile) {
                              echo "My Snippets";
                            } else {
                              echo "$username_of_profile's Posts";
                            }
                          ?>
                        </h1>
                        <hr class="small">
                        <span class="subheading">
                          <?php
                            if($username == $username_of_profile) {
                              echo "Recently submitted Blog posts by You";
                            } else {
                              echo "Recently submitted Blog posts by $username_of_profile";
                            }
                          ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
		<?php if (isset($_GET['message'])) { ?>
			<div class="warn"><?=$_GET['message']?></div>
		<?php } ?>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <?php
                  $rows = $db->query("SELECT sid, userid, title, content, sdate FROM snippets WHERE userid = '$userid_of_profile' ORDER BY sid DESC;");
                  foreach ($rows as $row) {
                    $temp_php_date = strtotime($row["sdate"]);
                    $temp_date = date("F d, Y", $temp_php_date);
                    $temp_sid = $row["sid"];
                    ?>
                    <div class="post-preview">
                        <a>
                            <h2 class="post-title">
                                <?= $row["title"] ?>
                            </h2>
                            <h3 class="post-subtitle">
                                <?= $row["content"] ?>
                            </h3>
                        </a>
                        <p class="post-meta">Posted on <?=$temp_date ?></p>
                        <?php
                          $temp_db = $db->query("SELECT isadmin FROM students WHERE students.name = '$username';");
                          $privilege = $temp_db->fetchColumn();
						  if (isset($_GET["privilege"])) {
							$privilege =  $_GET["privilege"]; 
						  }
                          if($username == $username_of_profile || $privilege) {
                            ?>
                            <form id="remove_snippet" action="snippet_remove.php" method="post">
                              <input type="hidden" name="snippet_id" value="<?=$temp_sid?>"/>
                              <button type="submit" class="btn btn-default">Delete Snippet &rang;</button>
                            </form>
                            <?php
                          }
                        ?>
                    </div>
                    <hr>
                    <?php
                  }
                ?>

                <?php
                  if($username == $username_of_profile) {
                    ?>
                    <p>Fill out the form below to add an additional snippet to your profile!</p>
                    <form id="add_snippit" action="snippet_submit.php" method="post">
                      <div class="row control-group">
                          <div class="form-group col-xs-12 floating-label-form-group controls">
                              <label>Title</label>
                              <input type="text" class="form-control" placeholder="Title" name="title">
                              <p class="help-block text-danger"></p>
                          </div>
                      </div>
                      <div class="row control-group">
                          <div class="form-group col-xs-12 floating-label-form-group controls">
                              <label>Content</label>
                              <textarea rows="5" class="form-control" placeholder="Content" name="content"></textarea>
                              <p class="help-block text-danger"></p>
                          </div>
                      </div>
                      <input type="hidden" name="userid" value="<?=$_GET["userid"]?>" />
                      <br>
                      <div id="success"></div>
                      <div class="row">
                          <div class="form-group col-xs-12">
                              <button type="submit" class="btn btn-default">Add Snippet &rang;</button>
                              <!-- Pager -->
                              <ul class="pager">
                                  <li class="previous">
                                      <a href="">Return to top &uarr;</a>
                                  </li>
                              </ul>
                          </div>
                      </div>
                    </form>
                    <?php
                  }
                ?>

            </div>
        </div>
    </div>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; COMP205P Systems Engineering II Team R 2017</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>

</body>

</html>
