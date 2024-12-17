<div class="container-xl">
    <div class="page-body margins">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col">
                            <h4 class="card-title">Add page</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="add_page">

                            <div class="mb-3">
                                <label class="form-label">Page type</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="type">
                                        <option value="page">Page</option>
                                        <option value="terms_page">Terms of service page</option>
                                        <option value="link">Link</option>
                                    </select>
                                    <i>The terms of service page has a different type so it can be mapped to the "View terms" button on the accept terms screen</i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Page title</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="title" placeholder="Page title (shown in navbar)" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Page language</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="lang">
                                        <?php foreach ($languages as $language): ?>
                                            <option value="<?= $language->path ?>"><?= $language->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3" id="type-page">
                                <label class="form-label">Tab order</label>
                                <div class="col-sm-10">
                                    <div class="form-group" id="page">
                                        <input type="number" class="form-control" name="order" placeholder="The order number of the tab">
                                        <i>You can provide an order number here of how you want to menu tab to be ordered relative to the other tabs. So if you enter 1 here and 2 on another page then this one will be shown before the other page in the menu.<br>
                                            The order number you enter here is only for the language you specify, so each language has its own order series.</i>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="type-link" style="display: none">
                                <label class="form-label">Page URL</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="content" placeholder="URL to go to">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3" id="type-page">
                                <label class="form-label">Page content</label>
                                <div class="col-sm-10">
                                    <div class="form-group" id="page">
                                        <textarea id="page-content" name="content_page"></textarea>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Add page">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    $('select[name="type"]').on('change', function() {
        $('#type-link').toggle();
        $('#type-page').toggle();
    })

    CKEDITOR.replace( 'page-content' );
</script>