<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo $settings['site_name']; ?> - Admin panel">
        <meta name="author" content="Proxibolt">
        <meta name="keyword" content="">

        <title><?php echo $settings['site_name']; ?> - Admin Panel</title>

        <base href="<?php echo $settings['site_url'] . $settings['admin_route'] ?>/">

        <link rel="shortcut icon" type="image/png" href="../<?php echo $settings['favicon_path']; ?>"/>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
        <link rel="stylesheet" href="https://unpkg.com/@tabler/core@1.0.0-beta3/dist/css/tabler.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/@tabler/core@1.0.0-beta3/dist/css/tabler-flags.min.css">
        <link rel="stylesheet" href="https://unpkg.com/@tabler/core@1.0.0-beta3/dist/css/tabler-payments.min.css">
        <link rel="stylesheet" href="https://unpkg.com/@tabler/core@1.0.0-beta3/dist/css/tabler-vendors.min.css">
        <link href="../assets/admin/assets/css/dashboard.css?v=<?php echo $settings['version'] ?>" rel="stylesheet" />
        <link href="../assets/admin/assets/plugins/charts-c3/plugin.css" rel="stylesheet" />
        <link href="../assets/admin/assets/plugins/maps-google/plugin.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">

        <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/3.3.0/luxon.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.4.4/js/tabulator.min.js"></script>

        <link rel="stylesheet" href="../assets/admin/assets/css/tabulator_bootstrap5.css" />


        <style>
            .tabulator {
                font-size: 14px;
            }

            body.theme-dark .tabulator {
                background: #232e3c;
                color: #fff;
            }

            body.theme-dark .tabulator .tabulator-header {
                background-color: #232e3c !important;
                color: #fff !important;
            }

            body.theme-dark .tabulator .tabulator-col {
                color: #fff !important;
                background-color: #232e3c !important;
            }

            body.theme-dark .tabulator .tabulator-row {
                color: #fff !important;
                background-color: #232e3c !important;
            }

            body.theme-dark .tabulator .tabulator-row:hover {
                background-color: #2f3e4d !important;
            }

            body.theme-dark .tabulator .tabulator-row:hover .tabulator-cell {
                background: inherit !important;
            }

            body.theme-dark .tabulator .tabulator-footer {
                background-color: #2f3e4d !important;
                color: white !important;
            }
        </style>
    </head>

    <body class="antialiased <?php echo (get_cookie('admin_dark_mode') == 'true' ? 'theme-dark' : '') ?>">
        <div class="wrapper">