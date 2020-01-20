// import $ from 'jquery'
// window.jQuery = $;
$(function () {
    $(document).ready(function () {
        const dashboardPublications = $('#admin-publications-dashboard');
        /*$.fn.datepicker.defaults.format = "yyyy-mm-dd";*/
        if (dashboardPublications.length) {
            console.log(dashboardPublications, $);
            $('.datepicker').datepicker({
                format: "dd.mm.yyyy"
            });
        }
    });
});