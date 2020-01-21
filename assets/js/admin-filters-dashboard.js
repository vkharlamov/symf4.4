// import $ from 'jquery'
// window.jQuery = $;
    $(document).ready(function () {
        const dashboardPublications = $('#admin-publications-dashboard');
        if (dashboardPublications.length) {
            console.log(dashboardPublications, $);
            $('.datepicker').datepicker({
                format: "dd.mm.yyyy",
                autoclose: true
            });
        }
    });
