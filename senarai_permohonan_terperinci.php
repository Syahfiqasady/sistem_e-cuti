<?php

$page_name = "Halaman Utama";
include_once('header.php');
include_once('nav.php');

$update = "";
$error = "";

$leave_id = frm('id');
$hash = frm('h');
$correct_hash = md5("yourhash" . $leave_id);

if ($hash != $correct_hash){
     session_unset(); 
    session_destroy();
    
    go($site_url);
    exit();
}

$sql = mq("SELECT * FROM leave_request WHERE id = ".  $leave_id);
$rsx = mfa($sql);
if($rsx["read_new"] == 1){
    $update_read = "UPDATE leave_request SET read_new = 2 WHERE id = " . $leave_id;
    mq($update_read);
}
?>

<div class="container leave-dashboard">
         <? include_once('top.php')?>

        <h1>Maklumat Permohonan</h1>
        <div class="leave-details">
            <table class="table table-bordered">
                <tr>
                    <th style="width:50%">Nama Kakitangan</th>
                    <td><?= get('name') ?></td>
                </tr>
                <tr>
                    <th>Jenis Cuti</th>
                    <td><?= $leave_type[$rsx['leave_type_id']] ?></td>
                </tr>
                <tr>
                    <th>Bilangan Hari</th>
                    <td><?= $rsx['days'] ?> hari</td>
                </tr>
                <tr>
                    <th>Tarikh Bermula Cuti</th>
                    <td><?= sdf($rsx['strt_dt']) ?></td>
                </tr>
                <tr>
                    <th>Tarikh Kembali Mengajar</th>
                    <td><?= sdf($rsx['end_dt']) ?></td>
                </tr>
                <tr>
                    <th>Gambar</th>
                    <td><?= ($rsx['img'] == "") ? "-" : "<img src='". $base_url . img($rsx['img']) . "' style='width:100&; height:auto;'>" ?></td>
                </tr>
                <tr>
                    <th>Sebab</th>
                    <td><?= ($rsx['reason'] == "") ? "-" : $rsx['reason'] ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <div class="status-<?= $rsx['status'] ?>">
                            <?= $status[$rsx['status']] ?>
                        </div>
                    </td>
                </tr>
                                <tr>
                    <th>Baki Cuti Baru (Sekiranya diluluskan)</th>
                    <td>
                        
                            <?= $rsx['balance']?> hari
                     
                    </td>
                </tr>
                <tr>
                    <th>Nota</th>
                    <td><?= ($rsx['remark'] == "") ? "-" : $rsx['remark'] ?></td>
                </tr>
                <tr>
                    <th>Tarikh Permohonan</th>
                    <td><?= sdf($rsx['created_dt']) ?></td>
                </tr>
                <tr>
                    <th>Tarikh Dikemas Kini</th>
                    <td><?= sdf($rsx['updated_dt']) ?></td>
                </tr>
            </table>

            <a href="javascript:history.back();" class="btn btn-primary">Kembali</a>
        </div>
    </div>



<?php include_once('footer.php')?>