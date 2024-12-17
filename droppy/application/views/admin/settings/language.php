<?php require_once dirname(__FILE__) . '/header.php'; ?>
<div class="page-body margins">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <?php require_once dirname(__FILE__) . '/tabs.php'; ?>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-condensed sortable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Directory</th>
                                <th>Locale(s)</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($languages as $lang) {
                                echo '<tr>';
                                echo '<td>' . $lang->id . '</td>';
                                echo '<td>' . $lang->name . ' ' . ($lang->path == $settings['language'] ? '<span class="badge badge-primary">Default</span>' : '') . '</td>';
                                echo '<td>' . $lang->path . '</td>';
                                echo '<td style="max-width: 750px;">' . $lang->locale . '</td>';
                                echo '<td>
                                        <a href="#" class="edit-language" data-id="' . $lang->id . '" data-name="' . $lang->name . '" data-path="' . $lang->path . '" data-locale="' . $lang->locale . '">
                                            <li class="fa fa-edit"></li> Edit
                                        </a>'. ($lang->path == $settings['language'] ? '' : ' | 
                                        <a href="settings/language/default/' . $lang->path . '">
                                            <li class="fa fa-check"></li> Set default
                                        </a>') . ' | 
                                        <a href="settings/language/delete/' . $lang->id . '">
                                            <li class="fa fa-trash"></li> Remove
                                        </a>
                                      </td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                        <p><i>Language directories are located in the directory <code>/application/language/</code>, new language directories need to be added there.</i></p>
                        <form method="POST">
                            <input type="hidden" name="save" value="1">
                            <div style="margin-top: 20px; width: 500px;">
                                <div style="">
                                    <input type="text" class="form-control input-sm" name="name" placeholder="Language name">
                                    <i>The name that will be shown in the language dropdown</i>
                                </div>
                                <br>
                                <div style="">
                                    <input type="text" class="form-control input-sm" name="path" placeholder="Directory name">
                                    <i>The directory name where the language files are in.</i>
                                </div>
                                <br>
                                <div style="">
                                    <input type="text" class="form-control input-sm" name="locale" placeholder="Locale">
                                    <i>The locale of the language, locales are returned by the browser. Droppy will use this to map the browser language to the language file in Droppy. You can find the available locale codes <a href="https://www.ibm.com/docs/en/rational-soft-arch/9.6.1?topic=overview-locales-code-pages-supported" target="_blank">over here</a>. You may also add multiple locales to one language if they are somewhat the same. Like for English you can use <code>en-GB,en-US</code>, this will map both the US en British version of English to one language in Droppy.</i>
                                </div>
                                <br>
                                <div style="">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Add language">
                                </div>
                            </div>
                        </form>

                        <!-- General Edit Language Modal -->
                        <div class="modal fade" id="editLanguageModal" tabindex="-1" role="dialog" aria-labelledby="editLanguageModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editLanguageModalLabel">Edit Language</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Edit Language Form -->
                                        <form method="POST" id="editLanguageForm">
                                            <input type="hidden" name="save" value="1">
                                            <input type="hidden" name="id" id="edit_language_id" value="">
                                            <div class="form-group">
                                                <label for="edit_name">Language Name</label>
                                                <input type="text" class="form-control" id="edit_name" name="name">
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label for="edit_path">Directory Name</label>
                                                <input type="text" class="form-control" id="edit_path" name="path">
                                                <i>You should <b>NOT</b> edit this value, it's used by Droppy and stored in the user's session. Changing the folder would break their sessions so make sure you know what you're doing when changing this.</i>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label for="edit_locale">Locale</label>
                                                <input type="text" class="form-control" id="edit_locale" name="locale">
                                                <i>The locale of the language, locales are returned by the browser. Droppy will use this to map the browser language to the language file in Droppy. You can find the available locale codes <a href="https://www.ibm.com/docs/en/rational-soft-arch/9.6.1?topic=overview-locales-code-pages-supported" target="_blank">over here</a>. You may also add multiple locales to one language if they are somewhat the same. Like for English you can use <code>en-GB,en-US</code>, this will map both the US en British version of English to one language in Droppy.</i>
                                            </div>
                                            <br>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                        <!-- End Edit Language Form -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End General Edit Language Modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle dynamic loading of the modal content
    $(document).ready(function() {
        $('.edit-language').click(function(ev) {
            ev.preventDefault();

            var languageId = $(this).data('id');
            var name = $(this).data('name');
            var path = $(this).data('path');
            var locale = $(this).data('locale');

            // Populate the modal form inputs with values from the table
            $('#edit_language_id').val(languageId);
            $('#edit_name').val(name);
            $('#edit_path').val(path);
            $('#edit_locale').val(locale);

            // Show the modal
            $('#editLanguageModal').modal('show');
        });
    });
</script>
