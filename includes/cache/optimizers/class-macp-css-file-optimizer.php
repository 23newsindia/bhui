<?php
class MACP_CSS_File_Optimizer {
    private $debug;

    public function __construct() {
        $this->debug = new MACP_Debug();
    }

    public function optimize_files($css_files) {
        $processed_files = [];
        $minifier = new MatthiasMullie\Minify\CSS();

        foreach ($css_files as $css_url) {
            if ($this->should_process_css($css_url)) {
                $this->debug->log("Processing CSS file: {$css_url}");
                $css_content = MACP_CSS_Extractor::get_css_content($css_url);
                if ($css_content) {
                    $minifier->add($css_content);
                    $processed_files[] = $css_url;
                }
            }
        }

        return [
            'content' => $minifier->minify(),
            'processed_files' => $processed_files
        ];
    }

    private function should_process_css($url) {
        if (!get_option('macp_process_external_css', 0) && !MACP_CSS_Extractor::is_local_url($url)) {
            $this->debug->log("Skipping external CSS: {$url}");
            return false;
        }

        foreach (MACP_CSS_Config::get_excluded_patterns() as $pattern) {
            if (strpos($url, $pattern) !== false) {
                $this->debug->log("Skipping excluded CSS: {$url}");
                return false;
            }
        }

        return true;
    }
}