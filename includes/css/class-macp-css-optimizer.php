<?php
require_once MACP_PLUGIN_DIR . 'includes/css/class-macp-css-config.php';
require_once MACP_PLUGIN_DIR . 'includes/css/class-macp-css-extractor.php';
require_once MACP_PLUGIN_DIR . 'includes/css/optimizers/class-macp-css-file-optimizer.php';
require_once MACP_PLUGIN_DIR . 'includes/css/optimizers/class-macp-css-inline-optimizer.php';
require_once MACP_PLUGIN_DIR . 'includes/css/storage/class-macp-css-cache-manager.php';
require_once MACP_PLUGIN_DIR . 'includes/css/processors/class-macp-html-css-processor.php';

class MACP_CSS_Optimizer {
    private $file_optimizer;
    private $inline_optimizer;
    private $cache_manager;
    private $html_processor;
    private $debug;

    public function __construct() {
        $this->file_optimizer = new MACP_CSS_File_Optimizer();
        $this->inline_optimizer = new MACP_CSS_Inline_Optimizer();
        $this->cache_manager = new MACP_CSS_Cache_Manager();
        $this->html_processor = new MACP_HTML_CSS_Processor();
        $this->debug = new MACP_Debug();
    }

    public function optimize_css($html) {
        if (!get_option('macp_remove_unused_css', 0)) {
            return $html;
        }

        try {
            $this->debug->log("Starting CSS optimization");

            // Extract CSS files and inline styles
            $css_files = MACP_CSS_Extractor::extract_css_files($html);
            $inline_styles = MACP_CSS_Extractor::extract_inline_styles($html);
            
            if (empty($css_files) && empty($inline_styles)) {
                $this->debug->log("No CSS found to optimize");
                return $html;
            }

            // Optimize CSS files
            $file_result = $this->file_optimizer->optimize_files($css_files);
            
            // Optimize inline styles
            $inline_css = $this->inline_optimizer->optimize_inline_styles($inline_styles);

            // Combine optimized CSS
            $optimized_css = $file_result['content'] . "\n" . $inline_css;
            
            if (empty($optimized_css)) {
                $this->debug->log("No CSS content after optimization");
                return $html;
            }

            // Save optimized CSS
            $cache_key = $this->cache_manager->save_optimized_css($optimized_css);
            if (!$cache_key) {
                return $html;
            }

            // Process HTML
            $html = $this->html_processor->remove_processed_files($html, $file_result['processed_files']);
            $html = $this->html_processor->remove_inline_styles($html);
            $html = $this->html_processor->add_optimized_css_link($html, $this->cache_manager->get_optimized_url($cache_key));

            $this->debug->log("CSS optimization completed successfully");
            return $html;

        } catch (Exception $e) {
            $this->debug->log("CSS optimization error: " . $e->getMessage());
            return $html;
        }
    }

    public function clear_css_cache() {
        $this->cache_manager->clear_cache();
    }
}