window.showSection = function(sectionName) {
    if (!['home', 'products', 'about'].includes(sectionName)) return;
    if (sectionName !== 'products' && window.stopProductAudio) window.stopProductAudio();
    $('.content-section').stop(true, true).hide();
    $('#' + sectionName + '-section').fadeIn(300, function() {
        if (sectionName === 'products' && window.resizeViewer) window.resizeViewer();
    });

    if (sectionName === 'about' && !window.aboutLoaded && !window.aboutLoading) {
        loadAboutPage();
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
};

function loadAboutPage() {
    window.aboutLoading = true;
    $.get('application/view/viewAbout.php')
        .done(function(data) {
            $('#about-content').html(data);
            window.aboutLoaded = true;
            window.aboutLoading = false;
        })
        .fail(function() {
            window.aboutLoading = false;
            // about content needs PHP. show a hint if the page is opened directly without a server.
            $('#about-content').html(
                '<div class="alert alert-warning">' +
                'About content requires a PHP server. ' +
                'Run the project with PHP, then select About again to retry.' +
                '</div>'
            );
        });
}
