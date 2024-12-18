<div class="container-xl">
    <div class="page-body margins">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col">
                            <h4 class="card-title">Edit page <?php echo $page['title'] ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="edit_page">
                            <input type="hidden" name="id" value="<?php echo $page['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Page title</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="title" value="<?php echo $page['title'] ?>" placeholder="Page title (shown in navbar)" required>
                                    </div>
                                </div>
                            </div>

                            <?php if($page['type'] != 'link'): ?>
                                <div class="mb-3">
                                    <label class="form-label">Page type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="type">
                                            <option value="page">Page</option>
                                            <option value="terms_page">Terms of service page</option>
                                        </select>
                                        <i>The terms of service page has a different type so it can be mapped to the "View terms" button on the accept terms screen</i>
                                    </div>
                                </div>
                                <script>$('select[name="type"]').val("<?php echo $page['type'] ?>");</script>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Page language</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="lang">
                                        <?php foreach ($languages as $language): ?>
                                            <option value="<?= $language->path ?>" <?= ($page['lang'] == $language->path ? 'selected="selected"' : '') ?>><?= $language->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3" id="type-page">
                                <label class="form-label">Tab order</label>
                                <div class="col-sm-10">
                                    <div class="form-group" id="page">
                                        <input type="number" class="form-control" name="order" placeholder="The order number of the tab" value="<?= $page['order'] ?>">
                                        <i>You can provide an order number here of how you want to menu tab to be ordered relative to the other tabs. So if you enter 1 here and 2 on another page then this one will be shown before the other page in the menu.<br>
                                            The order number you enter here is only for the language you specify, so each language has its own order series.</i>
                                    </div>
                                </div>
                            </div>

                            <?php if($page['type'] == 'link'): ?>
                                <div class="mb-3" id="type-link">
                                    <label class="form-label">Page URL</label>
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="content" value="<?php echo $page['content'] ?>" placeholder="URL to go to">
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mb-3" id="type-page">
                                    <label class="form-label">Page content</label>
                                    <div class="col-sm-10">
                                        <div class="form-group" id="page">
                                            <textarea id="page-content" name="content"><?php echo $page['content'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Edit page">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if($page['type'] != 'link'): ?>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'page-content' );
    </script>
<?php endif; ?>