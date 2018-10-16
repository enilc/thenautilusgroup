        <!--
            nav_bar.php is a flat file used to store the top and side navigation bars for the Spottr Bootstrap template.
        -->

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a href="#" class="navbar-center hidden-sm hidden-xs" style="margin: 0; float: none; text-align:center"><img class="img-fluid " src="images/logo2.png" style="position: absolute; left: 50%; margin-left: -50px !important; display: block; height:50px;"></a>
                <a href="#" class="navbar-center hidden-md hidden-lg hidden-xl" style="margin: 0; float: none; text-align:center"><img class="img-fluid " src="images/logo2.png" style="position: absolute; right: 1%; margin-left: -50px !important; margin-bottom: 5px; display: block; height:50px;"></a>

            </div>
            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li>

                        <a href=<?php echo "\"logout.php?sid=" . session_id() . "\"" ?>><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>

            <!-- /.navbar-top-links -->

            <!-- /.navbar-static-side -->
        </nav>