jQuery(document).ready(function($) {
    function updateProgress() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'macp_get_css_progress',
                nonce: macp_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    const progress = response.data;
                    const percent = Math.round((progress.processed / progress.total) * 100);
                    
                    $('#macp-css-progress-bar').css('width', percent + '%');
                    $('#macp-css-progress-text').text(percent + '% Complete');
                    
                    if (progress.status === 'running') {
                        setTimeout(updateProgress, 2000);
                    } else if (progress.status === 'completed') {
                        $('#macp-css-progress-status').html('Optimization completed!');
                        if (progress.failed_urls.length > 0) {
                            $('#macp-css-failed-urls').html(
                                '<h4>Failed URLs:</h4><ul>' + 
                                progress.failed_urls.map(url => '<li>' + url + '</li>').join('') +
                                '</ul>'
                            );
                        }
                    }
                }
            }
        });
    }

    // Start progress monitoring when optimization starts
    $('#macp-start-css-optimization').on('click', function() {
        updateProgress();
    });
});