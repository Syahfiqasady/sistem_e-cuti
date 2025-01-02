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

<style>

.table th{
    text-align:center;
}

.table td{
    font-size: 1rem;
    text-align: center;
    cursor: pointer;
}
</style>
<div class="container leave-dashboard">
     <? include_once('top.php')?>
    <!-- Leave History (optional section) -->
   <div class="row">
    <div class="col-12">
      <div class="card leave-card">
    <div class="card-header">
        <h4 class="card-title">Sejarah Permohonan Cuti</h4>
    </div>
    <div class="card-body">
        <!-- Year Selector Form -->
        <form method="GET" action="" id="yearFilterForm">
            <label for="year">Pilih Tahun:</label>
            <select name="year" id="year" onchange="this.form.submit()">
                <?php
                    // Get the current year and previous years for the dropdown
                    $current_year = date('Y');
                    // Check if a year is selected, otherwise use current year as default
                    $selected_year = isset($_GET['year']) ? $_GET['year'] : $current_year;
                    
                    // Loop through years from the current year to 2000
                    for ($i = $current_year; $i >= 2000; $i--) {
                        // Mark the selected year in the dropdown
                        echo "<option value='$i'" . ($i == $selected_year ? ' selected' : '') . ">$i</option>";
                    }
                ?>
            </select>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th ></th>
                        <th style="width:200px"> Tarikh Permohonan</th>
                        <th>Jenis Cuti</th>
                        <th>Bilangan Hari</th>
                        <th>Sebab</th>
                        <th>Status</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get the selected year or use the current year as default
                    $selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

                    // Modify SQL query to filter by the selected year
                    $sql = mq("SELECT * FROM leave_request WHERE employee_id = " . get("id") . " AND YEAR(created_dt) = $selected_year ORDER BY id DESC");

                    while ($lr = mfa($sql)) {
                        $hash = md5("e-cutiMuamalat" . $lr['id']);
                    ?>
                    <tr class="row-status-<?=$lr['status']?>" onclick="window.location.href='<?= $site_url ?>senarai_permohonan_terperinci.php?id=<?= $lr['id'] ?>&h=<?= $hash ?>';">
                        <td><? if($lr['read_new'] ==1){  ?><div style="padding:4px; background-color: #f8d7da; border-radius:5px; color: #721c24; width:30px">1</div> <? }?></td>
                        <td><?= sdf($lr['created_dt']) ?></td>
                        <td><?= $leave_type[$lr['leave_type_id']] ?></td>
                        <td class="text-center"><?= $lr['days'] ?></td>
                        <td><?= ($lr['reason'] == "") ? "-" : $lr['reason'] ?></td>
                        <td class="text-center"><div class="status-<?=$lr['status']?>"><?= $status[$lr['status']]?></div></td>
                        <td><?= ($lr['remark'] == "") ? "-" : $lr['remark'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    </div>
</div>

</div>