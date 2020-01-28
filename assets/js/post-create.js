$(document).ready(function () {
    const elem = $('.js-tags-select2entity');
    const autocomplete_url = elem.data('data-autocomplete-url');
    if (elem.length) {
        $('.select2entity').select2entity();
        console.log('b ', $('.select2entity'));
    }
    // $('.js-tags-autocomplete').select2({
    //     ajax: {
    //         url: autocomplete_url,
    //         type: 'POST',
    //         dataType: "json",
    //         delay: 250,
    //         placeholder: "Type a tags",
    //         data: (params) => ({tag: params.term}),
    //         processResults: (data) => {
    //             return {
    //                 results: data.map((tag) => {
    //                     return {id: tag.id, text: tag.name}
    //                 })
    //             }
    //         }
    //     }
    // });


});
