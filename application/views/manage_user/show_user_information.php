<!--===========================
SHOW INFORMATION
============================-->
<style>
    /* USER PROFILE PAGE */
    #header-card {
       padding: 30px;
       background-color: rgba(214, 224, 226, 0.2);
       -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
       box-sizing: border-box;
       border-bottom-left-radius: 0px;
       border-bottom-right-radius: 0px;
   }
   #header-card.hovercard {
       position: relative;
       padding-top: 0;
       overflow: hidden;
       text-align: center;
       background-color: #fff;
       background-color: rgba(255, 255, 255, 1);
   }
   #header-card.hovercard .card-background {
       height: 130px;
   }
   .card-background img {
       -webkit-filter: blur(25px);
       -moz-filter: blur(25px);
       -o-filter: blur(25px);
       -ms-filter: blur(25px);
       filter: blur(25px);
       margin-left: -100px;
       margin-top: -300px;
       min-width: 120%;
   }
   #header-card.hovercard .useravatar {
       position: absolute;
       top: 15px;
       left: 0;
       right: 0;
   }
   #header-card.hovercard .useravatar img {
       width: 100px;
       height: 100px;
       max-width: 100px;
       max-height: 100px;
       -webkit-border-radius: 50%;
       -moz-border-radius: 50%;
       border-radius: 50%;
       border: 5px solid rgba(255, 255, 255, 0.5);
   }
   #header-card.hovercard .card-info {
       position: absolute;
       bottom: 14px;
       left: 0;
       right: 0;
   }
   #header-card.hovercard .card-info .card-title {
       padding:0 5px;
       font-size: 20px;
       line-height: 1;
       color: #262626;
       background-color: rgba(255, 255, 255, 0.1);
   }
   #header-card.hovercard .card-info {
       overflow: hidden;
       font-size: 12px;
       line-height: 20px;
       color: #737373;
       text-shadow:0px 0px 2px white;
       text-overflow: ellipsis;
   }
   #header-card.hovercard .bottom {
       padding: 0 20px;
       margin-bottom: 17px;
   }
   
   .counters .sp {
        font-size: 48px;
        display: block;
        color: #2dc997;
    }

    .counters p {
        padding: 0;
        margin: 0 0 20px 0;
        font-family: "Poppins", sans-serif;
        font-size: 14px;
    }
    .nav-pills .nav-link.active{
        background: #ccc;
        color:#737373;
    }

    .nav-pills .nav-link{
        color:#737373;
        border:0;
        background:#eee;
        border-radius:0px;
        border-right: 1px solid white;
        border-left: 1px solid white;
    }
    .nav-pills .nav-link:hover{
        background: #ddd;
    }
    .nav-pills .nav-link.active:hover{
        background: #ccc;
        color:#737373;
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url() ?>AdminDashboard">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url() ?>ManageUser">Manage Users</a>
            </li>
            <li class="breadcrumb-item active"><?= $user->user_firstname . " " . $user->user_lastname . " Information" ?></li>
        </ol>
        <div class = "row">
            <div class = "col-lg-12 col-sm-12">
                <div id = "header-card" class=" hovercard border border-secondary border-bottom-0 rounded-top">
                    <div class="card-background">
                        <img class="card-bkimg" alt="" src="<?= base_url().$user->user_picture?>">
                    </div>
                    <div class="useravatar">
                        <img alt="" src="<?= base_url().$user->user_picture?>">
                    </div>
                    <div class="card-info"> <span class="card-title"><?= $user->user_firstname . " " . $user->user_lastname?></span></div>
                </div>
            </div>
            <div class = "col-lg-12 col-sm-12 counters">
                <nav class="nav nav-pills nav-fill my-1" id="user_tab" role="tablist">
                    <a class="nav-item nav-link " data-toggle="tab" href="#transaction" role="tab" aria-controls="home" aria-selected="true">
                        <span data-toggle="counter-up" class ="sp">
                            <?php 
                            if (empty($transactions)){
                                echo "0";
                            }else{
                                echo count($transactions);
                            }?>
                        </span>
                        <p>Transactions</p>
                    </a>
                    <a class="nav-item nav-link active" data-toggle="tab" href="#pet" role="tab" aria-controls="home" aria-selected="true">
                        <span data-toggle="counter-up" class ="sp">
                            <?php 
                            if (empty($pets)){
                                echo "0";
                            }else{
                                echo count($pets);
                            }?>
                        </span>
                        <p>Pets</p>
                    </a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#activities" role="tab" aria-controls="home" aria-selected="true">
                        <span data-toggle="counter-up" class ="sp">
                        <?php 
                            if (empty($activities)){
                                echo "0";
                            }else{
                                echo count($activities);
                            }?>
                        </span>
                        <p>Activities</p>
                    </a>
                </nav>
                <div class="tab-content" id="user_tab_content">
                    <div class="p-3 tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="home-tab">
                        <?php include_once("show_transaction.php");?>
                    </div>
                    <div class="p-3 tab-pane fade show active" id="pet" role="tabpanel" aria-labelledby="profile-tab">
                        <?php include_once("show_pet.php");?>
                    </div>
                    <div class="p-3 tab-pane fade" id="activities" role="tabpanel" aria-labelledby="contact-tab">
                        <?php include_once("show_activity.php");?>
                    </div>
                </div>
            </div>
        </div>