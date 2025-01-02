<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$page_name = "Halaman Utama";
include_once('header.php');


// Initialize variables for error or success messages
$loginError = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get input values
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hash the password as MD5(session_pfx . username)
    $hash = md5($session_pfx . $password);
    $sql = "SELECT * FROM employee WHERE usr='" . $username . "'";
    $rs = mq($sql);
    
    if ($r = mfa($rs)) {
        if ($r["pwd"] == $hash) {
        	set("id", $r["id"]);
			set("usr", $r["usr"]);
			set("name", $r["name"]);
			set("profileimg", $r["img"]);
			go("laman_utama.php"); 
        exit();
        } else {
            $loginError = "Nama pengguna atau kata laluan salah.";

        }
    	    
    }
}
?>
<style>
    body{
    background-image:url(https://sppmadas.com.my/admin/islamic-bg.jpg);
    background-size:cover;
}
</style>


<div class="container d-flex justify-content-center align-items-center" style="flex-direction: column;">
    <img src="https://sppmadas.com.my/admin/logo.png" class="login-logo center">
    <div class="text-center mt-3 mb-3" style="padding-top: 2%">
	<h1>SELAMAT DATANG</h1>
	<h1><b>Sistem e-Cuti Muamalat</b></h1>
      <p>  Madrasah Darussakinah Tawau
    </p>
</div>
    <div class="login-container">
        <form action="#" method="POST">
            <h2>Log Masuk</h2>
                        <!-- Display error message if any -->
            <?php if (!empty($loginError)): ?>
                <div class="error"><?php echo $loginError; ?></div>
            <?php endif; ?>
            <label for="username">Nama Pengguna</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Kata Laluan</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" value="submit">Log Masuk</button>
        </form>
    </div>
</div>