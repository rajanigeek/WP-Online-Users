<div class="bootstrap-wrapper hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include('views-wpuser-header.php'); ?>      
        <div class="content-wrapper"> 
            <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Pro features!</h4>
    <h4>Get Flat 25% off on <a target="_blank" class="text-green" href="www.wpseeds.com/product/profile-pro/">ProfilePro Plugin.</a> Use Coupon code <span class="text-muted"> 'WPSEEDS25'</span></h4>
</div>
            <section class="content-header">
                <h1>
                    WP User
                    <small>Setting</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Setting</li>
                </ol>
            </section>
            <section class="content">                
                <div ng-app="listpp" ng-app lang="en">
                    <div ng-view></div>    
                </div>
            </section>
            <?php include('views-wpuser-footer.php'); ?>
            <?php include('views-wpuser-controll-sidebar.php'); ?>
        </div> 
    </div>
</div>

