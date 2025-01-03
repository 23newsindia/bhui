<?php
class MACP_CSS_Inline_Optimizer {
    private $debug;

    public function __construct() {
        $this->debug = new MACP_Debug();
    }

    public function optimize_inline_styles($styles) {
        if (empty($styles)) {
            return '';
        }

        $minifier = new MatthiasMullie\Minify\CSS();
        foreach ($styles as $style) {
            $minifier->add($style);
        }

        return $minifier->minify();
    }
}