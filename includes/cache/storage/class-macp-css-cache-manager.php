<?php
class MACP_CSS_Cache_Manager {
    private $cache_dir;
    private $debug;

    public function __construct() {
        $this->cache_dir = WP_CONTENT_DIR . '/cache/macp/css/';
        $this->debug = new MACP_Debug();
        $this->ensure_cache_directory();
    }

    private function ensure_cache_directory() {
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
            file_put_contents($this->cache_dir . 'index.php', '<?php // Silence is golden');
            $this->debug->log("Created CSS cache directory: {$this->cache_dir}");
        }
    }

    public function save_optimized_css($css_content) {
        $cache_key = md5($css_content . time());
        $optimized_file = $this->cache_dir . 'optimized_' . $cache_key . '.css';
        
        if (file_put_contents($optimized_file, $css_content) === false) {
            $this->debug->log("Failed to write optimized CSS to file: {$optimized_file}");
            return false;
        }

        $this->debug->log("Successfully wrote optimized CSS to: {$optimized_file}");
        return $cache_key;
    }

    public function get_optimized_url($cache_key) {
        return content_url('cache/macp/css/optimized_' . $cache_key . '.css');
    }

    public function clear_cache() {
        $files = glob($this->cache_dir . '*');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'index.php') {
                    unlink($file);
                }
            }
        }
        $this->debug->log("CSS cache cleared");
    }
}