<?php
class MACP_CSS_Progress {
    private $option_key = 'macp_css_optimization_progress';
    
    public function start_optimization($total_files) {
        update_option($this->option_key, [
            'status' => 'running',
            'total' => $total_files,
            'processed' => 0,
            'failed_urls' => [],
            'start_time' => time()
        ]);
    }

    public function update_progress($processed_count, $failed_url = null) {
        $progress = get_option($this->option_key, []);
        $progress['processed'] = $processed_count;
        if ($failed_url) {
            $progress['failed_urls'][] = $failed_url;
        }
        update_option($this->option_key, $progress);
    }

    public function complete_optimization() {
        $progress = get_option($this->option_key, []);
        $progress['status'] = 'completed';
        $progress['end_time'] = time();
        update_option($this->option_key, $progress);
    }

    public function get_progress() {
        return get_option($this->option_key, []);
    }
}