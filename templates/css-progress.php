<div class="macp-css-progress">
    <div class="macp-progress-bar-container">
        <div id="macp-css-progress-bar" class="macp-progress-bar"></div>
    </div>
    <div id="macp-css-progress-text">0% Complete</div>
    <div id="macp-css-progress-status"></div>
    <div id="macp-css-failed-urls"></div>
</div>

<style>
.macp-progress-bar-container {
    width: 100%;
    height: 20px;
    background: #f0f0f0;
    border-radius: 10px;
    overflow: hidden;
    margin: 10px 0;
}

.macp-progress-bar {
    width: 0;
    height: 100%;
    background: #2271b1;
    transition: width 0.3s ease;
}

#macp-css-failed-urls {
    margin-top: 15px;
    color: #dc3232;
}
</style>