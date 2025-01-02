<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$page_name = "Halaman Utama";
include_once('header.php');
include_once('nav.php');
// 	require_once "__config.php";
// 	require_once "../header.lib";
$update = "";
$error = "";
?>
<div class="container leave-dashboard">
     <? include_once('top.php')?>
    <div class="row">
        <? $sql = mq("SELECT * FROM leave_type");
        while ($rsx=mfa($sql)){
        $leave_balance = mfa(mq("SELECT days_balance, reserved_days FROM leave_balance WHERE employee_id = " . get('id'). " AND leave_type_id = " . $rsx['id'] . " AND year = $current_year"));
        $used = $rsx['days'] - $leave_balance[0];
        if ($leave_balance[1] == 0){
            $net_balance = $leave_balance[0];
        }else {
            $net_balance = $leave_balance[0] - $leave_balance[1];
        }
        
        ?>
              <!-- Annual Leave Card -->
        <div class="col-lg-4 col-6 mb-1 p-2">
            <div class="card leave-card">
                <div class="card-header center">
                    <h4 class="card-title"><?= $rsx['name']?></h4>
                    <!--<p style="font-size:12px; margin-bottom:0px; margin-top:0.5rem"><?= $rsx['remarks']?></p>-->
                </div>
                <div class="card-body" style="font-size:0.6em">
                    <div class="row">
                        <div class="col-4 center mp-1" >
                            <div>
                                Kelayakan <br>
                                <span class="days"><?=$rsx['days']?></span>
                            </div>
                            
                        </div>
                        <div class="col-4 center mp-1" >
                           <div class="" >
                                Digunakan<br>
                                <span class="days"><?=$used?></span>
                            </div>
                        </div>
                        <div class="col-4 center mp-1" >
                            <div>
                                Baki <br>
                                <span class="days"><?=$net_balance?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="permohonan_baru.php?cuti=<?=$rsx['id']?>" class="btn btn-form">Mohon Cuti</a>
                </div>
            </div>
        </div>

        <? } ?>
      
    </div>

</div>