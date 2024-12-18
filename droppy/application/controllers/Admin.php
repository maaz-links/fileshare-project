<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 * @property  adminlib $adminlib
 * @property  downloads $downloads
 * @property  language $language
 * @property  templates $templates
 * @property  socials $socials
 * @property  backgrounds $backgrounds
 * @property  uploadlib $uploadlib
 * @property  users $users
 * @property  logs $logs
 * @property  themes $themes
 * @property  updates $updates
 * @property  pages $pages
 * @property  logging $logging
 */
class Admin extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        // Load some external models
        $this->load->model('language');
        $this->load->model('uploads');
        $this->load->model('downloads');
        $this->load->model('templates');

        $this->load->library('plugin');
        $this->load->library('AdminLib');
        $this->load->library('session');

        $this->load->helper('cookie');
        $this->load->helper('number');
        $this->load->helper('url');

        if(!in_array($this->uri->segment(2), ['login', 'forgot', 'reset'])) {
            $this->adminlib->authenticated();
        }
    }

    public function index()
    {
        // Data to pass to the views
        $data = array(
            'settings'  => $this->config->config,
            'plugins'   => $this->plugin->_plugins,
            'stats'     => array(
                'total_uploads' => $this->uploads->getTotal(),
                'total_uploads_active' => $this->uploads->getTotal('ready'),
                'total_uploads_destroyed' => $this->uploads->getTotal('destroyed'),
                'total_downloads' => $this->downloads->getTotal(),
                'last_upload' => $this->uploads->getLastUpload(),
                'most_downloaded' => $this->downloads->getMostDownloaded(),
                'total_size' => byte_format($this->adminlib->getDiskUsage()),
                'total_size_download' => byte_format($this->downloads->getTotalSize()),
                'disk_stats' => byte_format($this->adminlib->getDiskStats()),
            ),
            'htaccess_check_2_3_2' => $this->adminlib->checkHtaccess(),
            'check_terms_page' => $this->adminlib->checkTermsPageExists()
        );

        // Load the language helper
        $this->load->helper('language');

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/home', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function login()
    {
        $this->load->helper('recaptcha');

        // Post data
        $post = $this->input->post(NULL, TRUE);

        $data = array(
            'settings'  => $this->config->config
        );

        // If form is being submitted
        if(isset($_POST) && !empty($_POST)) {
            if(empty($this->config->item('recaptcha_secret')) || validate_recaptcha($post['g-recaptcha-response'], $this->config->item('recaptcha_secret'))) {
                if ($this->adminlib->authenticate($this->input->post('email'), $this->input->post('password'))) {
                    header('Location: ' . $this->config->item('site_url') . $this->config->item('admin_route') . '/index');
                    exit;
                } else {
                    $data['login'] = false;
                }
            } else {
                $data['login'] = false;
            }
        }

        $this->load->view('admin/auth/login', $data);
    }

    public function forgot()
    {
        $this->load->helper('recaptcha');

        // Post data
        $post = $this->input->post(NULL, TRUE);

        $data = array(
            'settings'  => $this->config->config
        );

        // If form is being submitted
        if(isset($_POST) && !empty($_POST)) {
            if(empty($this->config->item('recaptcha_secret')) || validate_recaptcha($post['g-recaptcha-response'], $this->config->item('recaptcha_secret'))) {
                $this->adminlib->forgotPassword($this->input->post('email'));
            }
            $data['reset'] = true;
        }

        $this->load->view('admin/auth/forgot', $data);
    }

    public function reset()
    {
        $this->load->helper('recaptcha');

        // Post data
        $post = $this->input->post(NULL, TRUE);

        $data = array(
            'settings'  => $this->config->config,
            'reset_id' => $this->uri->segment(3)
        );

        if(empty($data['reset_id'])) {
            header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/login');
            exit;
        }

        // If form is being submitted
        if(isset($_POST) && !empty($_POST)) {
            $data['reset'] = false;
            if(empty($this->config->item('recaptcha_secret')) || validate_recaptcha($post['g-recaptcha-response'], $this->config->item('recaptcha_secret'))) {
                if($this->adminlib->resetPassword($this->input->post('reset_id'), $this->input->post('password'))) {
                    header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/login');
                    exit;
                }
            }
        }

        $this->load->view('admin/auth/reset', $data);
    }

    public function logout()
    {
        $this->adminlib->logout();

        $this->load->helper('url');

        header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/index');
        exit;
    }

    public function uploads()
    {
        $this->load->library('UploadLib');
        $this->load->helper('url');
        $this->load->library('logging');

        // Handle the delete function
        if($this->uri->segment(5) == 'delete') {
            // Deleting the upload by upload ID
            try {
                $this->uploadlib->deleteUpload($this->uri->segment(6));
            } catch (Exception $e) {
                $this->logging->log('Error deleting upload: ' . $e->getMessage());
                header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/uploads');
            }
            header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/uploads');
        }

        // Get the page number
        $page = $this->uri->segment(4);
        if(empty($page)) {
            $page = 0;
        }
        $start = ($page * 30);

        $data = array(
            'settings'      => $this->config->config,
            'page'          => $page,
            'total_uploads' => $this->uploads->getTotal(),
            'file_status'   => array(
                'ready'         => '<span class="badge bg-success me-1"></span> Ready',
                'destroyed'     => '<span class="badge bg-danger me-1"></span> Destroyed',
                'processing'    => '<span class="badge bg-warning me-1"></span> Processing',
                'inactive'      => '<span class="badge bg-secondary me-1"></span> Inactive (Will be destroyed soon)',
            )
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/uploads', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function downloads()
    {
        // Get the page number
        $page = $this->uri->segment(4);
        if(empty($page) || $this->uri->segment(3) != 'page') {
            $page = 0;
        }
        $start = ($page * 30);

        // View data
        $data = array(
            'settings'  => $this->config->config,
            'page'      => $page,
            'total'     => $this->downloads->getTotal(),
            'downloads' => $this->downloads->getAll($start, 30)
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/downloads', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function pages()
    {
        $this->load->model('pages');
        $this->load->helper('url');

        // POST processing
        if($this->input->post('action') == 'edit_page') {
            $data = $this->input->post();
            unset($data['action']);
            unset($data['id']);

            // Update user by ID
            $this->pages->updateByID($data, $this->input->post('id'));

            header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/pages');
        }
        elseif($this->input->post('action') == 'add_page') {
            $data = $this->input->post();
            unset($data['action']);

            if(isset($data['content_page']) && !empty($data['content_page'])) {
                $data['content'] = $data['content_page'];
            }

            unset($data['content_page']);

            $this->pages->add($data);

            header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/pages');
        }
        if($this->uri->segment(3) == 'delete') {
            $this->pages->deleteByID($this->uri->segment(4));
        }

        // View data
        $data = array(
            'settings'  => $this->config->config,
            'total'     => $this->pages->getTotal(),
            'pages'     => $this->pages->getAll(),
            'languages' => $this->language->getAll()
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        if($this->uri->segment(3) == 'edit') {
            $data['page'] = $this->pages->getByID($this->uri->segment(4));
            $this->load->view('admin/editpage', $data);
        } elseif($this->uri->segment(3) == 'add') {
            $this->load->view('admin/addpage', $data);
        } else {
            $this->load->view('admin/pages', $data);
        }

        $this->load->view('admin/_elem/footer', $data);
    }

    public function users()
    {
        $this->load->model('users');
        $this->load->helper('url');

        // POST processing
        if($this->input->post('action') == 'edit_user') {
            $form_data['email']       = $this->input->post('email');
            $form_data['password']    = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

            // Update user by ID
            $this->users->updateByID($form_data, $this->input->post('id'));

            header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/users');
        }
        elseif($this->input->post('action') == 'add_user') {
            $form_data['email']       = $this->input->post('email');
            $form_data['password']    = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

            $this->users->add($form_data);

            header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/users');
        }
        if($this->uri->segment(5) == 'delete') {
            $this->users->deleteByID($this->uri->segment(6));
        }

        // Get the page number
        $page = $this->uri->segment(4);
        if(empty($page) || $this->uri->segment(3) != 'page') {
            $page = 0;
        }
        $start = ($page * 20);

        // View data
        $data = array(
            'settings'  => $this->config->config,
            'page'      => $page,
            'total'     => $this->users->getTotal(),
            'users'     => $this->users->getAll($start)
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        if($this->uri->segment(3) == 'edit') {
            $data['user'] = $this->users->getByID($this->uri->segment(4));
            $this->load->view('admin/edituser', $data);
        } elseif($this->uri->segment(3) == 'add') {
            $this->load->view('admin/adduser', $data);
        } else {
            $this->load->view('admin/users', $data);
        }

        $this->load->view('admin/_elem/footer', $data);
    }

    public function backgrounds()
    {
        $this->load->model('backgrounds');

        // Handle the POST action
        if(isset($_POST)) {
            if($this->input->post('add') == 1) {
                $this->adminlib->addBackground($_FILES['file'], $_POST);
            }
        }

        // Handle the delete function
        if($this->uri->segment(3) == 'delete') {
            $this->adminlib->deleteBackground($this->uri->segment(4));
        }

        // Data to be sent to the view
        $data = array(
            'settings'      => $this->config->config,
            'backgrounds'   => $this->backgrounds->getAll()
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/backgrounds', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function settings()
    {
        // Load models
        $this->load->model('settings');
        $this->load->library('email');

        // Load helpers
        $this->load->helper('language');
        $this->load->helper('seconds');

        // Get the current page
        $page = $this->uri->segment(3);

        // Just set page to general if it's empty
        if(empty($page)) {
           $page = 'general';
        }

        // Check if a post is being sent
        if(isset($_POST)) {
            if($this->input->post('save') == 1)
            {
                // Start saving data from the page
                $this->adminlib->save($page, $_POST);
                redirect($this->config->item('site_url') . $this->config->item('admin_route') . '/settings/' . $page);
            }
        }

        // Data to be passed to the views
        $data = array(
            'settings' => $this->config->config
        );

        // Init data extra array
        $data_extra = array();

        // Load extra data for specific pages
        switch($page) {
            case 'mailtemplates':
                $data_extra = array(
                    'languages' => $this->language->getAll(),
                    'templates' => $this->templates,
                    'plugintemplates' => $this->plugin->_adminmailtemplates
                );
            break;
            case 'social':
                $this->load->model('socials');

                $data_extra = array(
                    'social' => $this->socials->getAll()
                );
            break;
            case 'language':
                if($this->uri->segment(4) == 'delete') {
                    $this->language->delete($this->uri->segment(5));
                }
                elseif($this->uri->segment(4) == 'default') {
                    $this->language->makedefault($this->uri->segment(5));
                }

                $data_extra = array(
                    'settings' => $this->settings->getAll()[0],
                    'languages' => $this->language->getAll()
                );
                $data_extra['settings']['admin_route'] = $this->config->item('admin_route');
            break;
            case 'mail':
                if($data['settings']['email_server'] == 'SMTP') {
                    $data_extra = array(
                        'smtp_connect' => $this->email->testConnect()
                    );
                }
            break;
        }

        // Merge all data together
        $data = array_merge($data, $data_extra);

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/settings/' . $page, $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function themes()
    {
        $this->load->model('themes');
        $this->load->helper('url');

        // Process POST
        if(isset($_POST) && !empty($_POST)) {
            if(isset($_POST['action'])) {
                if($_POST['action'] == 'color') {
                    $this->adminlib->themeAction('color');
                    redirect($this->config->item('admin_route') . '/themes', 'refresh');
                }
            } else {
                $this->adminlib->addTheme($this->input->post());
            }
        }

        // Different actions
        if($this->uri->segment(3) != '') {
            $this->adminlib->themeAction($this->uri->segment(3), $this->uri->segment(4));
        }

        // View data
        $data = array(
            'settings'  => $this->config->config,
            'themes'    => $this->themes->getAll()
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/themes', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function plugins()
    {
        $this->load->library('plugin');

        $data = array(
            'settings'  => $this->config->config,
            'plugins'   => $this->plugin->_plugins
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/plugins', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function system()
    {
        $this->load->helper('url');
        $this->load->model('updates');

        if(isset($_POST['action'])) {
            if($_POST['action'] == 'update') {
                if($this->adminlib->runUpdate($_POST)) {
                    header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/system?update=done');
                    exit;
                }
            }
            elseif($_POST['action'] == 'manual_update') {
                $data = array(
                    "purchase_code" => $_POST['purchase_code'],
                    "ip" => $_SERVER['SERVER_ADDR']
                );

                header('Location: '.json_decode($this->adminlib->callProxibolt('update', $data))->url);
                exit;
            }
        }

        $latest_version = $this->adminlib->callProxibolt('version');
        $version_message = $this->adminlib->callProxibolt('message');

        $data = array(
            'settings'       => $this->config->config,
            'latest_version' => ($latest_version !== false ? json_decode($latest_version) : false),
            'pb_message'     => json_decode($version_message),
            'updates'        => $this->updates->getAll('0', '5'),
            'htaccess_check_2_3_2' => $this->adminlib->checkHtaccess(),
            'plugins'        => $this->plugin->_plugins
        );

        if(isset($_GET)) {
            if(isset($_GET['action']) && $_GET['action'] == 'debug') {
                $this->load->model('settings');
                if($this->config->item('debug_mode') == 'true') {
                    $this->settings->save(array('debug_mode' => 'false'));
                } else {
                    $this->settings->save(array('debug_mode' => 'true'));
                }
                header('Location: '.$this->config->item('site_url') . $this->config->item('admin_route') . '/system');
            }
            if(isset($_GET['update']) && $_GET['update'] == 'done') {
                $data['updated'] = true;
            }
        }

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/system', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    public function update()
    {
        if(!$this->adminlib->isUpToDate())
        {
            $this->adminlib->runUpdateFiles();

            echo 'Update finished';
        }
        else
        {
            echo 'System is already on the latest version';
        }
    }

    public function logs()
    {
        $this->load->model('logs');

        // Get the page number
        $page = $this->uri->segment(4);
        if(empty($page) || $this->uri->segment(3) != 'page') {
            $page = 0;
        }
        $start = ($page * 20);

        // View data
        $data = array(
            'settings'  => $this->config->config,
            'page'      => $page,
            'total'     => $this->logs->getTotal(),
            'logs'      => $this->logs->getAll($start, 20)
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/logs', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    /**
     *
     */
    function personal()
    {
        $this->load->model('accounts');

        if(isset($_POST)) {
            $data = array(
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
            );

            $this->accounts->updateByID($data, $this->input->post('id'));
        }

        // View data
        $data = array(
            'settings'  => $this->config->config,
            'account' => $this->accounts->getByID($this->session->userdata('admin_id'))
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        $this->load->view('admin/personal', $data);

        $this->load->view('admin/_elem/footer', $data);
    }

    function pluginpage() {
        $this->load->helper('url');
        $this->load->helper('sql');

        // Get the page number
        $page = $this->uri->segment(3);

        $data = array(
            'settings'       => $this->config->config
        );

        // Loading views
        $this->load->view('admin/_elem/header', $data);
        $this->load->view('admin/_elem/navbar', $data);

        if(isset($this->plugin->_adminpages[$page])) {
            //require_once dirname(__FILE__) . '/../plugins/' . $this->plugin->_adminpages[$page]['load'] . '/' . $this->plugin->_adminpages[$page]['view'] . '.php';
            $this->load->view('../plugins/' . $this->plugin->_adminpages[$page]['load'] . '/' . $this->plugin->_adminpages[$page]['view'], $data);
        }

        $this->load->view('admin/_elem/footer', $data);
    }

    function darkmode() {
        if(get_cookie('admin_dark_mode') == 'true') {
            set_cookie('admin_dark_mode', 'false', 10518975);
        } else {
            set_cookie('admin_dark_mode', 'true', 10518975);
        }
        redirect($this->input->get('redirect'));
    }

    function ajax() {
        $page = $this->uri->segment(3);
        $filters = [];

        if($page == 'uploads') {
            $page = $this->input->get('page');
            $size = $this->input->get('size');

            if($size == 'true') {
                $size = 0;
            }

            if(isset($_GET['filter'])) {
                $filters = $_GET['filter'];
            }

            $all_data = $this->uploads->getAllForAdmin(($page - 1), $size, $filters);

            foreach ($all_data as $key => $row) {
                $row['#'] = ($row['Status'] == 'ready' ? '<a href="#" onclick="deleteUpload(\'' . $this->config->config['site_url'] . $this->config->config['admin_route'] . '/uploads/page/' . $page . '/delete/' . $row['ID'] . '\'); return false;"><i class="fa fa-trash"></i> Destroy</a>' : '');
                $row['Size'] = (!empty($row['Size']) ? (($row['Size'] < 1048576) ? round($row['Size'] / 1024, 2) . ' KB' : round($row['Size'] / 1048576, 2) . ' MB') : 'Not available yet');
                $row['ID'] = '<a href="' . $this->config->config['site_url'] . $row['ID'] . '/' . $row['secret_code'] . '" target="_blank">' . $row['ID'] . '</a>';
                $row['Upload date'] = date('F j Y g:i A', $row['Upload date']);
                $row['Expire date'] = date('F j Y g:i A', $row['Expire date']);

                unset($row['secret_code']);

                $all_data[$key] = $row;
            }

            $response = [];

            $response['data'] = $all_data;
            $response['last_page'] = ($size > 0 ? ($this->uploads->getTotal() / $size) : ($this->uploads->getTotal() / 50));

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    function uploadsexport() {
        $all_data = $this->uploads->getAllForAdmin();

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Uploads".".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $handle = fopen('php://output', 'w');

        foreach ($all_data as $data_array) {
            $data_array['Upload date'] = date('F j Y g:i A', $data_array['Upload date']);
            $data_array['Expire date'] = date('F j Y g:i A', $data_array['Expire date']);

            fputcsv($handle, $data_array);
        }
        fclose($handle);
        exit;
    }
}
