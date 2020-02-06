$(document).ready(function () {
    const isAuth = $("#vote_form_id").length;
    if (isAuth) {
        const VoteHelper = {
            votePane: $(".js-vote-pane"),
            formVote: $("#vote_form_id"),
            formVoteField: $("#vote_post_form_vote"),
            currCount: 0,

            bindHandler: function () {
                let _self = this;
                this.votePane.find("a.vote").each(function (idx, el) {
                    $(el).on('click', function (e) {
                        if ($(el).hasClass('disabled')) {
                            e.preventDefault();
                        } else {
                            $(el).toggleClass('disabled', true);
                            _self.setVoteFormValue($(el).data('vote'));
                            _self.voteHandler(el);
                        }
                    })
                });
            },

            setDisableClick: function (key) {
                this.votePane.find("a.vote").each(function (idx, el) {
                    $(el).toggleClass('disabled', key); // just via BS
                })
            },

            setVoteFormValue: function (v) {
                this.formVoteField.val(v);
            },

            bindFormHandler: function () {
                let _self = this;

                this.formVote.on('submit', function (e) {
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
                    }).always(function () {
                        _self.setDisableClick(false);
                    });
                    return false;
                });
            },

            voteHandler: function (vote) {
                this.formVote.trigger('submit');
            },

            setCounter: function (data) {
                let className = data.vote ? 'badge badge-success' : 'badge badge-danger';
                let counterFiled = this.votePane.find('span.badge').removeClass();
                let count = parseInt(data.vote)
                    ? this.currCount + 1
                    : this.currCount - 1;
                counterFiled.addClass(className)
                    .text(count);
            },

            init: function () {
                this.bindHandler();
                this.bindFormHandler();
                this.currCount = parseInt(this.votePane.find('span.badge').text());
            }
        };

        VoteHelper.init();
    }
});
