<?php
if(!isset($settings['debug_mode']) && !isset($_GET['redirect'])) {
    echo '<script>setTimeout(function() { window.location.href = window.location.href + "?update=done&redirect=done"; }, 1000);</script>';
}
?>
    <div class="container-xl">
        <div class="page-body margins">
            <?php if(!$htaccess_check_2_3_2) : ?>
                <div class="alert alert-danger" style="margin: 10px 0 20px 0;">
                    <h4>Important!</h4>
                    It seems like you're missing the <code>application/.htaccess</code> file, please copy it over from your Droppy ZIP or download it from <a href="https://raw.githubusercontent.com/bcit-ci/CodeIgniter/develop/application/.htaccess" target="_blank">here</a> and place it into the application/ directory.
                    <br>
                </div>
            <?php endif; ?>

            <div class="row row-cards">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                System info
                            </h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-5">Site URL</dt>
                                <dd class="col-7"><a href="<?php echo $settings['site_url'] ?>"><?php echo $settings['site_url'] ?></a></dd>
                                <dt class="col-5">Install path</dt>
                                <dd class="col-7"><?php echo FCPATH ?></dd>
                                <dt class="col-5">Droppy version</dt>
                                <dd class="col-7"><?php echo $settings['version'] ?></dd>
                                <dt class="col-5">Droppy mode</dt>
                                <dd class="col-7"><?php echo ENVIRONMENT ?></dd>
                                <dt class="col-5">Droppy debug mode</dt>
                                <dd class="col-7"><?php echo $settings['debug_mode'] ?> <a href="system?action=debug" onclick="return confirm('Debugging mode should only be used for testing, it can generate a large log file on your server when you leave it enabled in production. Make sure to disable it when you\'re done with testing.')"><span class="badge bg-blue"><?php echo ($settings['debug_mode'] == 'true' ? 'Disable' : 'Enable') ?></span></a></dd>
                                <dt class="col-5">Active theme</dt>
                                <dd class="col-7"><?php echo $settings['theme'] ?></dd>
                                <dt class="col-5">Active plugins</dt>
                                <dd class="col-7">
                                    <?php
                                    foreach ($plugins as $plugin) {
                                        echo $plugin['name'] . '<br>';
                                    }
                                    ?>
                                </dd>
                                <dt class="col-5">PHP version</dt>
                                <dd class="col-7"><?php echo phpversion() ?></dd>
                                <dt class="col-5">PHP SAPI</dt>
                                <dd class="col-7"><?php echo php_sapi_name() ?></dd>
                                <dt class="col-5">PHP settings</dt>
                                <dd class="col-7">
                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                        <li><b>post_max_size:</b> <?php echo ini_get('post_max_size') ?></li>
                                        <li><b>upload_max_filesize:</b> <?php echo ini_get('upload_max_filesize') ?></li>
                                        <li><b>max_execution_time:</b> <?php echo ini_get('max_execution_time') ?></li>
                                        <li><b>memory_limit:</b> <?php echo ini_get('memory_limit') ?></li>
                                        <li><b>display_errors:</b> <?php echo ini_get('display_errors') ?></li>
                                        <li><b>output_buffering:</b> <?php echo ini_get('output_buffering') ?></li>
                                    </ul>
                                </dd>
                                <dt class="col-5">PHP loaded modules</dt>
                                <dd class="col-7"><?php foreach (get_loaded_extensions() as $module) { echo '<span class="badge bg-blue-lt">' . $module . '</span> '; } ?></dd>
                                <dt class="col-5">CURL version</dt>
                                <dd class="col-7"><?php echo curl_version()['version'] ?></dd>
                                <dt class="col-5">Available storage</dt>
                                <dd class="col-7"><?php echo $settings['upload_dir'] . ' <span class="badge bg-purple-lt">' . byte_format(disk_free_space($settings['upload_dir'])) . '</span>' ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="col">
                                <h4 class="card-title">Update system</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-panel" style="overflow:hidden;" id="updateDiv">
                                <?php
                                if(function_exists('curl_version') != '') :
                                    if(isset($latest_version) && $latest_version === false):
                                        ?>
                                        <div class="alert alert-danger">
                                            <p>Droppy was unable to contact the Proxibolt API server, please check back later. If the issue persists then please contact Proxibolt support.</p>
                                        </div>
                                    <?php
                                    elseif(isset($latest_version->version) && ($settings['version'] == $latest_version->version || $settings['version'] > $latest_version->version)):
                                        ?>
                                        <div class="alert alert-success">
                                            <p>You are using the latest version available (<?php echo $latest_version->version ?>).<br>
                                                Signup <a href="https://newsletter.proxibolt.com" target="_blank">here</a> and get notified when there is a new update available.
                                        </div>
                                    <?php
                                    else:
                                        ?>
                                        <h4 class="mb"><i class="fa fa-server"></i> Auto-Update</h4>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <?php
                                            if(isset($error) && !empty($error)) {
                                                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                            }
                                            if(isset($pb_message) && $pb_message->show == 1) {
                                                echo $pb_message->msg;
                                            }
                                            ?>
                                            <p>Your version of Droppy is outdated and needs to be updated, please enter your purchase code below and Droppy will do the rest for you.</p>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="purchase_code" placeholder="Your purchase code" value="<?php echo $settings['purchase_code']; ?>" required>
                                                <p><i>Don't know where to find your purchase code ? Please give a look to this article: <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-">here</a></i></p>
                                            </div>

                                            <button type="submit" class="btn btn-primary" onclick="this.innerHTML = 'Updating..'"><i class="fa fa-wrench"></i>&nbsp;Update</button>
                                        </form>
                                    <?php
                                    endif;
                                endif;
                                ?>
                            </div>
                            <br>
                            <?php if(isset($latest_version->version) && ($settings['version'] != $latest_version->version || $settings['version'] < $latest_version->version)): ?>
                                <div class="form-panel" style="overflow:hidden;" id="manualUpdate">
                                    <h4 class="mb"><i class="fa fa-server"></i> Manual update</h4>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="manual_update">
                                        <p>You can manually download the latest version to your desktop with the form below, this can be used if your system is unable to update automatically.</p>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="purchase_code" placeholder="Your purchase code" value="<?php echo $settings['purchase_code']; ?>" required>
                                            <p><i>Don't know where to find your purchase code ? Please give a look to this article: <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-">here</a></i></p>
                                        </div>

                                        <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i>&nbsp;Download</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                            <div class="form-panel" style="overflow:hidden; display: none;" id="updatingDiv">
                                <div style="padding-top: 25px;">
                                    <p style="text-align:center;"><i class="fa fa-spinner fa-pulse fa-3x"></i><br><br>
                                        Updating your system, please be patient.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="card">
                        <div class="card-header">
                            <div class="col">
                                <h4 class="card-title">Last 5 updates</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-panel" style="overflow:hidden;">
                                <table class="table table-bordered table-striped table-condensed sortable">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Version</th>
                                        <th>Date installed</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if($updates !== false && count($updates) > 0) {
                                        foreach ($updates as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $row['id'] . '</td>';
                                            echo '<td>' . $row['version'] . '</td>';
                                            echo '<td>' . $row['date'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col">
                                <h4 class="card-title">Recent Droppy logs <?php echo ($settings['debug_mode'] == 'false' ? '<span class="badge bg-red-lt">Disabled</span>' : '') ?></h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Log items are only added when debugging is enabled</p>
                            <?php if(file_exists(FCPATH . 'droppy.log')): ?>
                                <a class="btn btn-default" href="<?php echo $settings['site_url'] ?>droppy.log" download>Download droppy.log</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php if(isset($updated) && $updated): ?>
    <div class="modal modal-blur fade show" id="changelog-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changelog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h2>Droppy has been updated to V<?php echo $settings['version'] ?>!</h2>
                    <hr>
                    <h2>Are you enjoying these free updates?</h2>
                    <p>If you do then please leave a review on <a href="https://proxibolt.zendesk.com/hc/en-us/articles/5427494964754" target="_blank">CodeCanyon</a>, it would help out a lot &#128522;</p>
                    <img src="https://src.proxibolt.com/droppy/5stars.png" width="200"><br><br>
                    <a href="https://proxibolt.zendesk.com/hc/en-us/articles/5427494964754" target="_blank" class="btn btn-info">Leave review</a>
                    <hr>
                    <pre style="max-height: 500px; overflow-y: auto;">
V2.6.1
- Fixed issue that caused files with the same filename to be overwritten in the ZIP
- Fixed issue with thumbnail generation of large image uploads (Imagick library needs to be installed for large images)
- Small improvement to download stability on some server configurations

V2.6.0
- Added max. concurrent uploads option for extra tuning options
- Changes for new premium add-on update

V2.5.9.2 (Patch)
- Fixed issue with iframe terms accept
- Added "to" query param to iframe url

V2.5.9.1 (Patch)
- Fixed issue with default destruct option being set to incorrect value after update 2.5.9
- Fixed issue that caused the download back button to show on self-destruct downloads
- Fixed issue that caused the report button to show on download password page

V2.5.9
- Fixed issues with filetypes downloading on android
- Added iframe upload form option
- Added option to add custom CSS using the admin panel
- Added back button after starting download to retry download if needed
- Added delay to download "okay" button to prevent accidental page leave while download is initiated
- Added option to let users report malicious downloads

V2.5.8
- Fixed issue with admin destroy button + extra logging
- Fixed issue that caused the help popups to not appear when the password feature was disabled
- Small logging improvements

V2.5.7
- Login redirect to download page
- Added option to set social meta tag image

V2.5.6
- Added automatic browser language detection support, make sure to properly setup your locale values in your language settings.
- Added option to edit languages
- Added password reset feature to admin panel
- Added option to set a different admin url path
- Added ability to login using local Droppy accounts when Active directory is connected
- Changed logging level when in development mode
- Fixed some general issues and improved code quality

V2.5.5
- Fixed some issues that caused Droppy to stop working in PHP 7, although it's still recommended to use PHP 8.0 or higher
- Fixed issue that caused the mobile logo to not be shown on the mobile version of Droppy
- Added extra connection checks to auto-updater
- General bug fixes and improvements
- Added extra debugging lines

V2.5.4
- Added option to set different mobile logo that is shown on the mobile version of Droppy
- Added missing accept terms option back and moved it to the "General settings" page in the admin panel
- Added PHP 8 check
- Added option to set the order of the pages (Menu order)
- Fixed timezone setting not being used in the admin panel
- Fixed issue with "View terms" button on the accept terms window by introducing a new page type "Terms of service"

V2.5.3
- Added option to create multi-language pages
- Moved about and terms pages to the new multi-language page system
- Improved plugin loading

V2.5.2
- Fixed issue that caused mail to be sent to empty email address on upload destruction
- Fixed dark mode issue for uploads table in admin panel
- Added SMTP connection tester to admin panel email settings
- Added option to admin panel to set frontend date format
- Added option to admin panel to set session expiration timeout

V2.5.1
- Added support for PHP 8.2+
- Fixed admin panel uploads page navigation issue
- Improved authentication
- Updated spanish translation

V2.5.0
- Added thumbnail generation for "large" images when file preview is enabled
- Added improved uploads table to admin panel including the option to filter records
- Added uploads export option to admin panel
- Improved file preview layout
- Increased length of background path and url
- Decreased concurrent uploads to prevent possible server issues and improve performance
- Fixed some styling issues for the premium add-on
- Changes for upcoming premium add-on update

V2.4.9
- Fixed issue that caused upload button to no longer work after clicking it once when the fields are empty
- Updated translation files
                </pre>
                    <p>The full changelog can be found <a href="https://proxibolt.zendesk.com/hc/en-us/articles/360025115111-Droppy-changelog" target="_blank">over here</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>$(document).ready(function() { $('#changelog-modal').modal('show'); });</script>
<?php endif; ?>