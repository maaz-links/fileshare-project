<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class ConfigHook
 */
class ConfigHook {
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Get settings from DB
     */
    function getSettings() {
        if($this->CI->router->class != 'install')
        {
            $query = $this->CI->db->query("SELECT * FROM droppy_settings LIMIT 1");
            $settings = $query->row_array();
            // Store Droppy settings in codeigniter
            foreach ($settings as $key => $value)
            {
                if ($key == 'id')
                    continue;
                if($key == 'language')
                    $key = 'droppy_language';

                $this->CI->config->set_item($key, $value);
            }

            // Set the site URl from the database as CodeIgniter base URL
            $this->CI->config->set_item('base_url', $this->CI->config->item('site_url'));

            // Set the server time
            if(!empty($settings['timezone'])) {
                date_default_timezone_set($settings['timezone']);
            }

            if(!empty($settings['session_expiration'])) {
                $this->CI->config->set_item('sess_expiration', $settings['session_expiration']);
                ini_set('session.gc_maxlifetime', $settings['session_expiration']);
            }

            // Get active theme
            $query = $this->CI->db->query("SELECT * FROM droppy_themes WHERE status='ready' LIMIT 1");
            $theme = $query->row_object()->path;

            // Set active theme in global config
            $this->CI->config->set_item('theme', $theme);

            // Load custom admin route if set
            $adminRoute = 'admin';
            foreach ($this->CI->router->routes as $key => $value) {
                if ($value === 'admin') {
                    $adminRoute = $key;
                }
            }
            $this->CI->config->set_item('admin_route', $adminRoute);
        }
    }

    /**
     * Load the language
     */
    function loadLanguage()
    {
        if($this->CI->router->class != 'install')
        {
            $this->CI->load->library('session');

            // Get language from session
            $language = $this->CI->session->userdata('language');

            // Check if language is already set
            if (empty($language) || !isset($language)) {
                $this->CI->load->model('language');

                // Language not set, try to get from browser language
                $browser_language = $this->CI->input->server('HTTP_ACCEPT_LANGUAGE');

                // Extract the first language from the browser's list
                $browser_language = strtok($browser_language, ',');

                $language = $this->CI->language->getByLocale($browser_language);

                if(!empty($language)) {
                    $language = $language[0]->path;
                } else {
                    $language = $this->CI->config->item('droppy_language');
                }

                // Store in session
                $this->CI->session->set_userdata('language', $language);
            }

            // Check if the directory actually exists
            if(!is_dir(APPPATH . 'language/' . $language)) {
                $language = $this->CI->config->item('droppy_language');
            }

            // Load the set language
            $this->CI->lang->load('main_lang', $language);
        }
    }

    /**
     * Load all the plugins
     */
    function loadPlugins() {
        if($this->CI->router->class != 'install')
        {
            $this->CI->load->library('plugin');

            $this->CI->plugin->loadPlugins();
        }
    }
}