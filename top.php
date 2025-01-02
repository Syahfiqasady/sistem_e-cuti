    <style>
        /* Announcement banner styles */
        .announcement-banner {
            background-color: #ffeb3b; /* Yellow background */
            color: #333; /* Dark text color for contrast */
            text-align: center;
            padding: 15px 20px;
            font-family: Arial, sans-serif;
            font-size: 1.1em;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Dismiss button styles */
        .announcement-banner .close-btn {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 1.2em;
            cursor: pointer;
            color: #333;
        }

        /* Close button hover effect */
        .announcement-banner .close-btn:hover {
            color: #000;
        }

        /* Page content padding to avoid overlap */
        .content {
            margin-top: 80px; /* Space to avoid overlap with fixed banner */
            padding: 20px;
            font-family: Arial, sans-serif;
        }
    </style>
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
<!--    🎉 Madrasah akan ditutup pada <strong><?= $holiday_date ?></strong> - <strong><?= $holiday_enddate ?></strong>  untuk <?= $holiday_name ?>! 🎉-->
<!--</div>-->

    <p class="text-center" style="font-size:12px; color:red">Sila ambil CTM sekiranya bilangan hari cuti yang hendak dimohon lebih dari hari yang anda layak/baki cuti anda.</p>
</div>