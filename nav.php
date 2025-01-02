<?
if (empty(get('id')) ){
     session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    go ($site_url);
    exit;
}

?>
<nav class="mobile-bottom-nav">
<div class="mobile-bottom-nav__item <?= ($this_file == "laman_utama.php") ? "mobile-bottom-nav__item--active": ""?>">
    <div class="mobile-bottom-nav__item-content" onclick="window.location.href='<?=$site_url?>laman_utama.php';">
        <i class="material-icons">home</i>
        Utama
    </div>		
</div>

<div class="mobile-bottom-nav__item <?= ($this_file == "senarai_permohonan.php") ? "mobile-bottom-nav__item--active" : "" ?>">
    <div class="mobile-bottom-nav__item-content" onclick="window.location.href='<?=$site_url?>senarai_permohonan.php';">
        <i class="material-icons">list_alt</i>  <!-- Changed to list_alt -->
        Senarai
    </div>
</div>

<div class="mobile-bottom-nav__item <?= ($this_file == "permohonan_baru.php") ? "mobile-bottom-nav__item--active" : "" ?>">
    <div class="mobile-bottom-nav__item-content" onclick="window.location.href='<?=$site_url?>permohonan_baru.php';">
        <i class="material-icons">add_circle</i> <!-- Changed to add_circle -->
        Mohon
    </div>		
</div>

<div class="mobile-bottom-nav__item <?= ($this_file == "profil.php") ? "mobile-bottom-nav__item--active" : "" ?>">
    <div class="mobile-bottom-nav__item-content" onclick="window.location.href='<?=$site_url?>logout.php';">
        <i class="material-icons">exit_to_app</i> <!-- Changed to exit_to_app -->
        Log Keluar
    </div>		
</div>

</nav>