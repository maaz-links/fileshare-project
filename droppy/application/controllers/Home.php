<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property authlib $authlib
 */
class Home extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        // Failure in database connection
        if($this->db->conn_id == NULL) {
            echo "<h3>Database connection failed, make sure you've filled your database settings in the application/config/database.php file!</h3>";
        }

        // Load some external models
        $this->load->model('language');
        $this->load->model('themes');
        $this->load->model('socials');
        $this->load->model('backgrounds');
        $this->load->model('pages');

        // Load the helpers
        $this->load->helper('language');
        $this->load->helper('url');
        $this->load->helper('number');

        // Load libraries
        $this->load->library('AuthLib');
        $this->load->library('Plugin');
        $this->load->library('Mobile_Detect');
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->helper('cookie');
        $this->load->helper('seconds');

        if(!$this->authlib->pageAllowed('upload')) {
            redirect('/login');
        }

        // Data to pass through to views
        $data = array(
            'page'          => 'upload',
            'settings'      => $this->config->config,
            'socials'       => $this->socials->getAll(),
            'language_list' => $this->language->getAll(),
            'extra_pages'   => $this->pages->getAll(0, 0, $this->session->userdata('language')),
            'custom_tabs'   => $this->plugin->_tabs,
            'custom_css'    => $this->plugin->_css,
            'session'       => $this->session,
            'mobile'        => false,
            'sender_cookie' => get_cookie('sender')
        );

        if ($this->plugin->pluginLoaded('premium') && !empty($this->config->item('custom_backgrounds'))) {
            $data['backgrounds'] = $this->config->item('custom_backgrounds');
        } else {
            $data['backgrounds'] = $this->backgrounds->getAllOrderID();
        }

        // Mobile Detect library
        $detect = new Mobile_Detect();
        // Iframe tag
        $iframe = $this->input->get('iframe');

        // Loading views
        if(!empty($iframe) && $this->config->item('allow_iframe') == 'true') {
            if(!empty($this->input->get('to')) && empty($data['settings']['default_email_to'])) {
                $data['settings']['default_email_to'] = $this->input->get('to');
            }

            $this->load->view('themes/' . $this->config->item('theme') . '/_elem/iframe_header', $data);
            $this->load->view('themes/' . $this->config->item('theme') . '/upload', $data);
            $this->load->view('themes/' . $this->config->item('theme') . '/_elem/footer', $data);
        } else {
            if (file_exists(FCPATH . 'application/views/themes/' . $this->config->item('theme') . '/_elem/header-mobile.php') && ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS())) {
                $data['mobile'] = true;
                $this->load->view('themes/' . $this->config->item('theme') . '/_elem/header-mobile', $data);
            } else {
                $this->load->view('themes/' . $this->config->item('theme') . '/_elem/header', $data);
            }
            $this->load->view('themes/' . $this->config->item('theme') . '/_elem/socials', $data);

            if ($this->config->item('accept_terms') == 'yes' && empty(get_cookie('terms'))) {
                $this->load->view('themes/' . $this->config->item('theme') . '/terms', $data);
            } else {
                $this->load->view('themes/' . $this->config->item('theme') . '/upload', $data);
            }

            $this->load->view('themes/' . $this->config->item('theme') . '/_elem/modals', $data);
            $this->load->view('themes/' . $this->config->item('theme') . '/_elem/footer', $data);
        }
    }

    public function download()
    {
        if(!$this->authlib->pageAllowed('download')) {
            redirect('/login?redirect='.urlencode(current_url()));
        }

        // Load helpers
        $this->load->helper('cookie');

        // Load models
        $this->load->model('uploads');
        $this->load->model('files');
        $this->load->model('receivers');

        // Get upload id and unique id from URL
        $upload_id  = $this->uri->segment(1, 0);
        $unique_id  = $this->uri->segment(2, 0);

        // Fetch upload data based on download id
        $upload_data = $this->uploads->getByUploadID($upload_id);

        $data = array(
            'page'          => 'download',
            'settings'      => $this->config->config,
            'socials'       => $this->socials->getAll(),
            'language_list' => $this->language->getAll(),
            'upload_id'     => $upload_id,
            'unique_id'     => $unique_id,
            'backgrounds'   => $this->backgrounds->getAllOrderID(),
            'extra_pages'   => $this->pages->getAll(0, 0, $this->session->userdata('language')),
            'custom_tabs'   => $this->plugin->_tabs,
            'custom_css'    => $this->plugin->_css,
            'mobile'        => false,
            'session'       => $this->session
        );

        if ($this->plugin->pluginLoaded('premium') && $this->plugin->_plugins['premium']['version'] >= '2.1.6' && isset($upload_data['pm_email']) && !empty($upload_data['pm_email'])) {
            $clsPremiumUser = new PremiumUser();
            $clsPremiumBackgrounds = new PremiumBackgrounds();

            $usr = $clsPremiumUser->getByID($upload_data['pm_email']);

            $premium_user_id = (!empty($usr['parent_id']) ? $usr['parent_id'] : $usr['id']);

            $custom_backgrounds = $clsPremiumBackgrounds->getByUserID($premium_user_id);

            if($custom_backgrounds !== false && count($custom_backgrounds) > 0) {
                $data['backgrounds'] = $custom_backgrounds;
            }
        }

        $allowed = false;

        // Check if there's any upload data returned
        if($upload_data) {
            $data['data'] = $upload_data;
            $data['files'] = $this->files->getByUploadID($upload_id);

            // Check if upload has share type mail
            if(isset($upload_data['share']) && $upload_data['share'] == 'mail') {
                // Share type is mail, check if unique ID exists
                if(!empty($unique_id) && (is_array($this->receivers->getByUploadAndPrivateID($upload_id, $unique_id)) || $upload_data['secret_code'] == $unique_id)) {
                    $allowed = true;
                }

                // Do not allow downloading file twice when the immediate file destruction is enabled
                if($upload_data['destruct'] == 'yes' && $this->receivers->checkAlreadyDownloaded($upload_id, $unique_id)) {
                    $allowed = false;
                }
            }
            else
            {
                $allowed = true;
            }

            // Download not allowed
            if(!$allowed) {
                unset($data['data']);
            }
        }

        // Mobile Detect library
        $detect = new Mobile_Detect();

        // Loading views
        if (file_exists(FCPATH . 'application/views/themes/' . $this->config->item('theme') . '/_elem/header-mobile.php') && ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS())) {
            $data['mobile'] = true;
            $this->load->view('themes/' . $this->config->item('theme') . '/_elem/header-mobile', $data);
        } else {
            $this->load->view('themes/' . $this->config->item('theme') . '/_elem/header', $data);
        }
        $this->load->view('themes/' . $this->config->item('theme') . '/_elem/socials', $data);

        if ($this->config->item('accept_terms') == 'yes' && empty(get_cookie('terms'))) {
            $this->load->view('themes/' . $this->config->item('theme') . '/terms', $data);
        } else {
            $this->load->view('themes/' . $this->config->item('theme') . '/download', $data);
        }

        $this->load->view('themes/' . $this->config->item('theme') . '/_elem/modals', $data);
        $this->load->view('themes/' . $this->config->item('theme') . '/_elem/footer', $data);
    }
}
