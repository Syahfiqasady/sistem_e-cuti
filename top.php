
<?
$current_date = date('Y-m-d');
$rsy = "SELECT name, date, end_date FROM public_holiday WHERE date >= '$current_date' ORDER BY date ASC LIMIT 1";
$ry = mq($rsy);
$upcoming_holiday = mfa($ry);

if ($upcoming_holiday) {
    $holiday_name = $upcoming_holiday['name'];
    $holiday_date = date('d F Y', strtotime($upcoming_holiday['date']));
    $holiday_enddate = date('d F Y', strtotime($upcoming_holiday['end_date']));// Format to Malay date
} else {
    $holiday_name = "Cuti akan datang"; // Default if no upcoming holiday is found
    $holiday_date = "N/A";
}
?>
<div class="center">
       <img src="https://sppmadas.com.my/admin/logo.png" class="top-logo">
    <h2 class="text-center">E-Cuti Muamalat</h2>
    <p>Selamat Datang, <?= get('name')?></p>
    <!-- Announcement Banner -->
<!--<div class="announcement-banner" id="announcement-banner">-->
<!--    🎉 Pejabat akan ditutup pada <strong><?= $holiday_date ?></strong> - <strong><?= $holiday_enddate ?></strong>  untuk <?= $holiday_name ?>! 🎉-->
<!--</div>-->

    <p class="text-center" style="font-size:12px; color:red">Sila ambil CTM sekiranya bilangan hari cuti yang hendak dimohon lebih dari hari yang anda layak/baki cuti anda.</p>
</div>