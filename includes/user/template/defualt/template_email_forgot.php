<?php $message='<div bgcolor="#e3e3e3" style="font-family:Arial;color:#707070;font-size:12px;background-color:#e3e3e3;margin:0;padding:0px">
    <div align="center" style="font-family:Arial;width:600px;background-color:#ffffff;margin:0 auto;padding:0px">
        <div style="font-family:Arial;border-bottom-color:#cccccc;border-bottom-width:1px;border-bottom-style:solid;background-color:#eee;margin:0px;padding:4px">
            New Password
        </div>
        <div align="left" style="font-family:Arial;text-align:left;margin:0px;padding:10px">
            <div>
                Dear <strong style="font-family:Arial;margin:0px;padding:0px">  '.$user_login.'</strong>, <br><br>
                <h3 style="font-family:Arial;font-size:14px;font-weight:bold;margin:0 0 5px 5px;padding:0px">Your password changed successfully. Details as follow</h3>
                <br><br>
                Login url :'.get_permalink().'
                <br> <br>
                User Name : '.$user_login.'
                <br>
                Your new password is: '.$random_password.' 
                <br>
                <br><br>
            </div>
            </div>
        <div style="font-family:Arial;border-top-style:solid;border-top-color:#cccccc;border-top-width:1px;color:#707070;font-size:12px;background-color:#efefef;margin:0px;padding:15px">
        </div>
        <div style="font-family:Arial;border-top-width:1px;border-top-color:#cccccc;border-top-style:solid;background-color:#eee;margin:0px;padding:10px">
            You\'re receiving this email because you have register member on '.$site_url.'
            <div class="yj6qo"></div><div class="adL">
            </div></div><div class="adL">
        </div></div><div class="adL">
    </div></div>';