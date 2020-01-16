import $ from 'jquery'
// window.jQuery = $;
$(function() {
    const dashboardPublications = $('#admin-publications-dashboard');
    if (dashboardPublications.length) {
            console.log(dashboardPublications, $);
            $('.datepicker').datepicker({
                format: "dd.mm.yyyy"
            });
    }
});