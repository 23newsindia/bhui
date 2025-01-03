<?php
class MACP_HTML_CSS_Processor {
    public function remove_processed_files($html, $processed_files) {
        foreach ($processed_files as $original_file) {
            $html = preg_replace(
                '/<link[^>]+href=[\'"]' . preg_quote($original_file, '/') . '[\'"][^>]*>/i',
                '',
                $html
            );
        }
        return $html;
    }

    public function remove_inline_styles($html) {
        return preg_replace('/<style[^>]*>.*?<\/style>/is', '', $html);
    }

    public function add_optimized_css_link($html, $optimized_url) {
        $optimized_link = '<link rel="stylesheet" href="' . esc_attr($optimized_url) . '" />';
        return preg_replace('/<head([^>]*)>/i', '<head$1>' . PHP_EOL . $optimized_link, $html);
    }
}