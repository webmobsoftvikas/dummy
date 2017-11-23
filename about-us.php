<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       <title>Peer to Peer Lending Platform | Financial Platform Switzerland</title>
<meta name="description" content="Instimatch is a platform which enables institutional lenders and borrowers to match their financing needs. ">
<meta name="keywords" content="peer to peer lending platform, Peer to peer Plattform, Public Finance Platforms, financial Platform switzerland">

		<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73662373-1', 'auto');
  ga('send', 'pageview');

</script>
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <!--<link href="css/modern-business.css" rel="stylesheet">-->
        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <!-- Navigation -->
        <div class="container-fluid navbar-inverse nav-home-contain ">
            <ul class="nav navbar-nav  navbar-right nav-home">
				<li><a class="<?php echo ($_SESSION['language'] == 'german' ) ? 'selected' : ''; ?>" href="javascript:changeLanguage('german');">DE</a></li>
				<li><a class="<?php echo ($_SESSION['language'] == 'france' ) ? 'selected' : ''; ?>" href="javascript:changeLanguage('france');">FR</a></li>
				<li><a class="<?php echo ($_SESSION['language'] == 'english' ) ? 'selected' : ''; ?>" href="javascript:changeLanguage('english');">EN</a></li>
            </ul>
        </div>
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="logo-home"><a href="https://instimatch.ch/" class="navbar-brand"><img width="256" height="73" class="pull-left" src="images/logo.png" alt="" style="margin-top: -12px;"></a></div>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">                    
                    <ul id="nav_menu" class="nav navbar-nav nav-index navbar-right nav-main">
                       <li><a   href="https://instimatch.ch/">Home</a></li>
                        <li><a  class="active" href="https://instimatch.ch/about-us.php">About us</a></li>
						 <li><a  href="https://instimatch.ch/what-we-do.php">What we do</a></li>
                        <li><a  href="https://instimatch.ch/contact-us.php">Contact us</a></li>
						
						  <li><a  href="https://instimatch.ch/how-does-it-work.php">How does it work</a></li>

                    </ul>                  
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

       
        <!-- Page Content -->
        <div class="container" style="position: relative; top: 34px;">
            <div class="row"><h2>About Us:</h2>
          <p>  Instimatch is a platform which enables institutional lenders and borrowers to match their financing needs. It is a simple to use interface which allows the borrower to enter the amount required, maturity and other relevant credit details.
Via a market place, lenders are able to observe incoming borrowing requests and can make an offer accordingly.</p>
<p>There are two types of borrower interfaces at present, one for local councils, hospitals etc. and another for regional banks.</p>
<p>The matching system allows for a very low priced and efficient way of sourcing financing from pension funds, insurance companies and other cash rich institutions.
Instimatch can also be rolled out internationally, following extensive testing in Switzerland.</p>

            </div>
            <!-- /.row -->
            <hr>
           

        </div>
        <!-- /.container -->
 <!-- Footer -->
            <footer>
                <div class="row">
                   <div class="col-lg-12">
                        <p class="footer-p">Copyright © Instimatch 2017</p>
                    </div>
                </div>
            </footer>
        <!-- jQuery -->
        <script src="js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

		<script>
		function changeLanguage(language) {
			$.ajax({
				url: "https://instimatch.ch/applications/c_login/changeLanguage/" + language,
				method: "post",
				cache: false,
				success: function () {
					location.reload();
				}
			});
		}
		</script>
    </body>
<style>
.navbar-inverse {background:#000 !important;}
</style>
</html>
