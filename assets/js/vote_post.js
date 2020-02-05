$(document).ready(function () {
    let VoteHelper = {
        param: {
            votePane: $(".js-vote-pane"),
            voteForm: $("#vote_post_form_vote")
        },
        bindHandler: function () {
            let _self = this;
            this.param.votePane.find("a").each(function (idx, el) {
                console.log(idx, el, _self);
                $(el).on('click', () => {
                    _self.setVoteFormValue($(el).data('vote'));
                    _self.voteHandler();
                })
            });
        },
        setVoteFormValue: function(v) {
            this.param.voteForm.val(v);
        },

    voteHandler: function (vote) {
        let _self = this;
        $("#vote_form_id").submit(function (e) {
                e.preventDefault();
                let form = $(e.currentTarget);
                let url = form.attr('action');

                $.ajax({
                    type: form.attr('method'),
                    url: url,
                    data: form.serialize()
                }).done(function (data) {
                    _self.setCounter(data);
                }).fail(function (data) {
                    console.log('An error occurred.', data);
                });
            }).trigger('submit'); // we have to trigger to fire
        },
        setCounter: function (v) {

        },
        init: function () {
            console.log('init', this);
            this.bindHandler();
        }
    };
    VoteHelper.init();
});
