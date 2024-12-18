<ul class="nav nav-tabs" data-bs-toggle="tabs">
    <li class="nav-item">
        <a href="settings/general" class="nav-link">General</a>
    </li>
    <li class="nav-item">
        <a href="settings/upload" class="nav-link">Upload</a>
    </li>
    <li class="nav-item">
        <a href="settings/mail" class="nav-link">Email</a>
    </li>
    <li class="nav-item">
        <a href="settings/mailtemplates" class="nav-link">Email templates</a>
    </li>
    <li class="nav-item">
        <a href="settings/language" class="nav-link">Language</a>
    </li>
    <li class="nav-item">
        <a href="settings/advertising" class="nav-link">Advertising</a>
    </li>
    <li class="nav-item">
        <a href="settings/contact" class="nav-link">Contact</a>
    </li>
</ul>
<script>
    var current_path = window.location.href.replace('<?php echo $settings['site_url'] . $settings['admin_route'] . '/' ?>', '');
    $('a.nav-link[href="'+current_path+'"]').addClass('active');
</script>