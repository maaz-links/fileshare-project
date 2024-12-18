<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <title><?php echo $settings['site_title']; ?></title>

    <base href="<?php echo $settings['site_url'] ?>">

    <!-- Favicon -->
    <link href="<?php echo $settings['site_url'] . $settings['favicon_path']; ?>" rel="shortcut icon" type="image/png">

    <!-- Search engine tags -->
    <meta name="description" content="<?php echo $settings['site_desc']; ?>">
    <meta name="author" content="<?php echo $settings['site_name']; ?>">
    <meta name="keywords" content="<?php echo $settings['site_keywords']; ?>"/>
    <meta property="og:image" itemprop="image" content="<?php echo $settings['social_meta_tag_image']; ?>">

    <!-- External libraries and fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,300,600,800,900" rel="stylesheet" type="text/css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link href="assets/css/vegas.min.css?v=<?php echo $settings['version']; ?>" rel="stylesheet">

    <!-- Preload icons -->
    <link rel="preload" href="assets/themes/<?php echo $settings['theme'] ?>/mecwbjnp.json" as="fetch">
    <link rel="preload" href="assets/themes/<?php echo $settings['theme'] ?>/lupuorrc.json" as="fetch">
    <link rel="preload" href="assets/themes/<?php echo $settings['theme'] ?>/yyecauzv.json" as="fetch">

    <!-- Droppy stylesheet -->
    <link rel="stylesheet" href="assets/themes/<?php echo $settings['theme'] ?>/css/style.css?v=<?php echo rand() . $settings['version']; ?>">

    <?php if(!empty($settings['theme_color'])): ?>
        <!-- Style overwrite -->
        <style>.button.is-info, .upload-block .upload-form .radio-group .radio.selected { background: <?php echo $settings['theme_color'] ?> !important; color: <?php echo (!empty($settings['theme_color_text']) ? $settings['theme_color_text'] : '#fff'); ?> } .close-btn { color: <?php echo (!empty($settings['theme_color']) ? $settings['theme_color'] : '#485fc7'); ?> } .navbar.is-info { background-color: <?php echo (!empty($settings['theme_color']) ? $settings['theme_color'] : '#3e8ed0'); ?> }</style>
    <?php endif; ?>

    <?php
    if(isset($custom_css) && count($custom_css) > 0) {
        foreach ($custom_css as $css) {
            echo '<link href="' . $css . '" rel="stylesheet">';
        }
    }
    ?>

    <style>
        body, html {
            width: 100% !important;
            height: 100% !important;
        }

        .upload-block {
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
        }

        .upload-block-inner {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            border-radius: 0 !important;
        }

        .upload-block-tooltip {
            display: none !important;
        }

        .upload-block .upload-progress-details {
            width: 100%;
        }

        .tab-window {
            margin-top: 0 !important;
        }
    </style>

    <?php if(!empty($settings['custom_css_code'])): ?>
        <style><?= $settings['custom_css_code'] ?></style>
    <?php endif; ?>

    <!-- Javascript -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
</head>
<body><div class="tab-window">
    <a href="#" class="close-btn"><i class="lni lni-close"></i></a>
    <hr>
    <div class="tab" id="tab-language">
        <h1><?php echo lang('change_language'); ?></h1>

        <label class="label"><?php echo lang('select_pref_lang'); ?></label>
        <div class="control has-icons-left">
            <div class="select is-rounded is-fullwidth">
                <select onchange="General.changeLanguage()" id="languagePicker">
                    <option disabled selected> -- <?php echo lang('select_language'); ?> -- </option>
                    <?php
                    foreach($language_list as $row)
                    {
                        echo '<option value="' . $row->path . '">' . $row->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="icon is-small is-left">
                <i class="lni lni-world"></i>
            </div>
        </div>
        <br>
        <?php
        if($settings['ad_1_enabled'] == 'true') :
            ?>
            <div style="margin-right: auto; margin-left: auto; margin-top: 120px; width: 468px; height: 60px;">
                <?php echo $settings['ad_1_code']; ?>
            </div>
        <?php
        endif;
        ?>
    </div>
    <?php if($settings['contact_enabled'] == 'true'): ?>
        <div class="tab" id="tab-contact">
            <h1><?php echo lang('contact'); ?></h1>
            <form class="contact-form">
                <div class="field">
                    <label class="label"><?php echo lang('email'); ?></label>
                    <div class="control">
                        <input class="input" type="email" name="contact_email" placeholder="<?php echo lang('contact_email_description'); ?>">
                    </div>
                </div>
                <div class="field">
                    <label class="label"><?php echo lang('subject'); ?></label>
                    <div class="control">
                        <input class="input" type="text" name="contact_subject" placeholder="<?php echo lang('contact_subject_description'); ?>">
                    </div>
                </div>
                <div class="field">
                    <label class="label"><?php echo lang('message'); ?></label>
                    <div class="control">
                        <textarea class="textarea" name="contact_message" placeholder="<?php echo lang('contact_message_description'); ?>"></textarea>
                    </div>
                </div>
                <div class="g-recaptcha" data-sitekey="<?php echo $settings['recaptcha_key']; ?>" style="margin-bottom: 20px;"></div>
                <div class="field is-right">
                    <p class="control">
                        <button class="button is-info">
                            <?php echo lang('send'); ?>
                        </button>
                    </p>
                </div>
            </form>
        </div>
    <?php endif; ?>
    <?php if(isset($page) && $page == 'download' && isset($data) && $data['file_previews'] == 'true'): ?>
        <div class="tab large" id="tab-files">
            <div class="files">
                <?php foreach ($files as $file) { ?>
                    <div class="file">
                        <a href="<?php echo $settings['site_url'].'handler/file?file_id='.$file['id'].'&file_secret='.$file['secret_code'] ?>&download=true" class="download" download>
                            <div class="file-content-block">
                                <?php
                                if(count($files) == 1) {
                                    $file_path = $settings['upload_dir'] . $file['secret_code'] . '-' . $file['file'];
                                } else {
                                    $file_path = $settings['upload_dir'] . $upload_id . '/' . $file['secret_code'] . '-' . $file['file'];
                                }
                                $ext = pathinfo($file_path, PATHINFO_EXTENSION);

                                if($file['thumb'] == 1) {
                                    echo '<img data-src="'.$settings['site_url'].'handler/file?file_id='.$file['id'].'&file_secret='.$file['secret_code'].'&thumb=1" loading="lazy" class="lazyload">';
                                } elseif(in_array($ext, array('jpg','JPG','jpeg','JPEG','png','PNG','gif','webp')) && $file['size'] < 500000) {
                                    echo '<img data-src="'.$settings['site_url'].'handler/file?file_id='.$file['id'].'&file_secret='.$file['secret_code'].'" loading="lazy" class="lazyload">';
                                } else {
                                    ?>
                                    <lord-icon
                                            src="assets/themes/<?php echo $settings['theme'] ?>/nocovwne.json"
                                            trigger="click"
                                            colors="primary:#746e6e,secondary:#746e6e"
                                            style="width:150px;height:150px; margin: 0 auto;">
                                    </lord-icon>
                                <?php } ?>
                            </div>

                            <div class="file-details">
                                <div class="details">
                                    <span class="name"><?php echo $file['file'] ?></span>
                                    <span class="size"><?php echo byte_format($file['size']) ?></span>
                                </div>
                                <span class="download"><i class="lni lni-cloud-download"></i></span>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php
    endif;

    if(is_array($extra_pages) && !empty($extra_pages)) {
        foreach ($extra_pages AS $key => $tab) {
            if ($tab['type'] != 'link') {
                echo '<div class="tab" id="tab-' . ($tab['type'] == 'terms_page' ? 'terms' : $key) . '">' . $tab['content'] . '</div>';
            }
        }
    }

    if(is_array($custom_tabs) && !empty($custom_tabs)) {
        foreach ($custom_tabs AS $key => $tab) {
            if ($tab['type'] == 'inline') {
                echo '<div class="tab" id="tab-'.$key.'">';
                require_once APPPATH . 'plugins/' . $tab['plugin'] . '/' . $tab['view'];
                echo '</div>';
            }
        }
    }
    ?>
</div>

