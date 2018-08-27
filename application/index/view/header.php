<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-7-25
 * Time: 下午1:10
 */
session_start();

$_SESSION['user'] = 'admin';
if (!isset($_SESSION['user'])){
    header('Location:404.html');
    exit();
}
?>
<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!--    toastr-->
<script src="../plugins/CodeSeven-toastr/build/toastr.min.js"></script>
<!--    toastr-->
<link href="../plugins/CodeSeven-toastr/build/toastr.min.css" rel="stylesheet" />
<script>
   $(function () {
       // toastr
       toastr.options.positionClass = 'toast-top-center';
       toastr.options.closeButton = true;
       // css
       // toastr.options.positionClass = 'toast-center-center';

       toastr.options.timeOut = 2000; // How long the toast will display without user interaction
       toastr.options.extendedTimeOut = 2000; // How long the toast will display after a user hovers over it

       // toastr.options.progressBar = true;

       $('#useToggle').click(function () {
           console.log($(this).attr('aria-expanded'));
           if (!$(this).attr('aria-expanded')){
               $.ajax({
                   type: 'post',
                   url: "../functions/getUserInfo.php",
                   data: {username: $('#useToggle span').text()},
                   dataType: 'json',
                   success: function(result){
                       console.log(result);

                       $('.user-header p').html(result.login + ' - '+ result.description + '<small>' + result.email +'</small>');
                   },
                   error: function () {
                       toastr.error("get userInfo fail");
                   }
               });
           }

       });


   })


</script>
<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="atsIndex.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>TS</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>AutoTest</b>System</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="useToggle">
                        <!-- The user image in the navbar-->
                        <img src="../resource/img/sizu.gif" class="user-image" alt="User Image">
<!--                        <img src="../dist/img/user3-128x128.jpg" class="user-image" alt="User Image">-->
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo $_SESSION['user']?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="../resource/img/sizu.gif" class="img-circle" alt="User Image">
                            <p></p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
<!--                                <a href="#" class="btn btn-default btn-flat">Profile</a>-->
                            </div>
                            <div class="pull-right">
                                <a href="tpmsLink.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
