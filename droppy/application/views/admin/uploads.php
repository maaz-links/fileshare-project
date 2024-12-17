<div class="page-body margins">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Uploads</h4>

                    <div class="col-auto ms-auto">
                        <a href="/uploadsexport" class="btn btn-primary" download><i class="fa fa-download"></i>&nbsp;Export</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    if($total_uploads == 0) :
                        ?>
                        <h4>No uploads have been found</h4>
                        <?php
                    else:
                        ?>

                        <div id="uploads-table" class="table" style="width: 100%; position: relative;"></div>

                        <script>
                            var table = new Tabulator("#uploads-table", {
                                layout:"fitColumns",
                                autoColumns:true,
                                autoColumnsDefinitions:function(definitions) {
                                    const filter_columns = ['ID', 'Owner', 'Status', 'Share type', 'IP', 'Destruct']

                                    definitions.forEach((column) => {
                                        column.widthGrow = 1;
                                        column.headerWordWrap = true;
                                        column.minWidth = 100;
                                        if(filter_columns.includes(column.field)) {
                                            column.headerFilter = true;
                                        }
                                        if(column.field == 'ID' || column.field == '#') {
                                            column.formatter = 'html';
                                        }
                                        if(column.field == 'Message') {
                                            column.formatter = 'textarea';
                                            column.widthGrow = 3;
                                            column.minWidth = 300;
                                        }
                                        if(column.field == 'Upload date' || column.field == 'Expire date') {
                                            column.widthGrow = 2;
                                            column.minWidth = 200;
                                        }
                                    });

                                    return definitions;
                                },
                                pagination: true,
                                paginationSize: 50,
                                paginationMode:"remote",
                                filterMode: "remote",
                                ajaxURL: 'ajax/uploads',
                                downloadRowRange:"all"
                            });

                            function deleteUpload(url) {
                                if(confirm('Are you sure you want to delete this upload ?')) {
                                    fetch(url);
                                    table.replaceData();
                                }
                            }
                        </script>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
