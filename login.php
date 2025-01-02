<?
$page_title = "Log Masuk Pentadbir";
$sql_table = "employee";
$login = 1;
require_once "__config.php";
?>
<? require_once $lib_base . "header1.lib"; ?>
<?
$p_flux = array(
	//list			name					type			check
	"usr",			"Nama Pengguna",		"text_20",		"reqchk",
	"pwd",			"Kata Laluan",			"pwd_20",		"reqchk",
);

if (get("loginfail") == "") {
	set("loginfail", 0);
}
?>
<? require_once $lib_base . "mergei.lib"; ?>
<?
require_once $lib_base . "preparam.lib";

if ($submitted) {
	require_once $lib_base . "validation.lib";

	if (get("loginfail") >= 6) {
		$validated = 0;
	}

	if ($validated) {
		$sql = "SELECT * FROM " . $sql_table . " WHERE usr='" . clean($p["usr"]) . "'";
		$rs = mq($sql);
		if ($r = mfa($rs)) {
			if ($r["pwd"] == frmp("hash")) {
				if ($r["active"] == 1) {
					set("id", $r["id"]);
					set("usr", $r["usr"]);
					set("name", $r["name"]);
					set("gambar", $r["img"]);
					set("designation", $r["designation"]);
					set("isadmin", $r["isadmin"]);
					set("isclerk", $r["isclerk"]);
					set("isteacher", $r["isteacher"]);
					set("istreasurer", $r["istreasurer"]);
					set("last_login", $r["last_login"]);
					set("current_session", sdtf(now()));

					if (md5($session_pfx . $r["usr"]) == frmp("hash")) {
						set("samepass", 1);
					}
					set("loginfail", 0);

					$skey = rnd(32);
					set("skey", $skey);

					$sql = "UPDATE " . $sql_table . " SET last_login='" . now() . "',skey='" . $skey . "' WHERE id='" . get("id") . "'";
					mq($sql);
					if (isset($log_audit["lg"]) && $log_audit["lg"]) {
						$log_info = "LOGIN SUCCESS (" . $r["usr"] . ")";
						require $lib_base . "auditlog.lib";
					}

					go("home.php");
				} else {
					$error .= epfx($error, "Akaun anda tidak aktif");
					if (isset($log_audit["lg"]) && $log_audit["lg"]) {
						$log_info = "Login GAGAL (" . $r["usr"] . ") - tidak aktif";
						require $lib_base . "auditlog.lib";
					}
				}
			} else {
				$error .= epfx($error, "Nama pengguna atau kata laluan tidak sah");
				set("loginfail", get("loginfail") + 1);
				if (isset($log_audit["lg"]) && $log_audit["lg"]) {
					$log_info = "LOGIN GAGAL  (" . $r["usr"] . ") - kata laluan salah";
					require $lib_base . "auditlog.lib";
				}
			}
		} else {
			$error .= epfx($error, "Nama pengguna atau kata laluan tidak sah");
			set("loginfail", get("loginfail") + 1);
			if (isset($log_audit["lg"]) && $log_audit["lg"]) {
				$log_info = "LOGIN GAGAL  (" . $p["usr"] . ") - nama pengguna salah";
				require $lib_base . "auditlog.lib";
			}
		}
	}
}
?>
<? require_once $lib_base . "notification.lib"; ?>
<div class="center" style="padding-top: 2%">
	<h1>SELAMAT DATANG</h1>
	<p style="font-size: 25px;margin-bottom: 2px;">PENTADBIRAN MADRASAH DARUSSAKINAH<br>
Kg Durian Merotai Besar 91008 Tawau.

</p>
</div>
<? require_once $lib_base . "subheader.lib"; ?>
<style>
body{
    background-image:url(islamic-bg.jpg);
    background-size:cover;
}
	.text-center {
		text-align: center;
	}

	.pt-4 {
		padding-top: 2rem;
	}

	.btn {
		width: 27%;
		height: 41px;
		border-radius: 5px;
		font-weight: 700;
		font-size: 15px;
		margin: 2px 4px;
	}

	.form-input {
		width: 55%;
		height: 43px;
		border: 1px solid black;
		border-radius: 5px;
		font-size: 15px;
		margin: 5px;
	}

	.btn-primary {
		background-color: #01a0c8;
	}

	.btn-secondary {
		background-color: #00dc9f;
	}

	.main-content {
		display: block;
		height: 0%;
	}
		.main-content form {
    margin: 0px !important; 
</style>

<? if (get("loginfail") < 6) { ?>
	<table width="70%">
		<tr class="text-center">
			<td colspan="2">
				<input type="text" size="20" id="usr" name="usr" value="<?= $p["usr"] ?>" class="form-input" placeholder="Nama Pengguna">
				<script>
					gebi('usr').focus();
				</script>
			</td>
		<tr class="text-center">
			<td colspan="2"><input type="password" size="20" id="pwd" name="pwd" value="<?= $p["pwd"] ?>" class="form-input" placeholder="Kata Laluan"></td>
		<tr class="text-center">
			<td colspan="2" align="center" class="">
				<input type="hidden" id="hash" name="hash" value="" />
				<input type="submit" name="submit" style="background-color: var(--primary);" value="  Log Masuk  " onclick="dologin('<?= $session_pfx ?>');" class="btn btn-primary">
				<input type="button" name="clear" style="background-color: var(--secondary);" value="  Padam  " onclick="gebi('usr').value=''; gebi('pwd').value='';" class="btn btn-secondary">
			</td>
	</table>
<? } else { ?>
	<br><br><b class="red">Terlalu banyak percubaan gagal.<br>Sila cuba lagi kemudian.</b><br><br>&nbsp;
<? } ?>

<? require_once $lib_base . "subfooter2.lib"; ?>
<? require_once $lib_base . "forms.lib"; ?>
<table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 10px;">
	<tr>
		<td class="medium m" height="25">
			&copy;<?= date('Y') ?> <a href="<?= $company_url ?>" style="color:#303030;"><?= $company_name ?></a>. Hak Cipta Terpelihara. Dibangunkan oleh BLU.
</table>

<? require_once $lib_base . "footer2.lib" ?>