$(document).ready(function () {
    let VoteHelper = {
        votePane: $(".js-vote-pane"),
        formVote: $("#vote_form_id"),
        formVoteField: $("#vote_post_form_vote"),
        currCount: 0,

        bindHandler: function () {
            let _self = this;
            this.votePane.find("a.vote").each(function (idx, el) {
                $(el).one('click', function (e) {
                    _self.setVoteFormValue($(el).data('vote'));
                    _self.voteHandler(el);
                })
            });
        },

        disableClick: function (key) {
            key ?
                this.votePane.find("a.vote").each(function (idx, el) {
                    $(el).off('click');
                })
                : this.bindHandler();
        },

        setVoteFormValue: function (v) {
            this.formVoteField.val(v);
        }
        ,

        bindFormHandler: function () {
            let _self = this;

            this.formVote.on('submit', function (e) {
                e.preventDefault();
                _self.disableClick(true);

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
                _self.disableClick(false);
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
});
