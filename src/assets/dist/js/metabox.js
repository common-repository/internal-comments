/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/assets/dev/ts/models/IC_MetaBox.ts
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var IC_MetaBox = function () {
  function IC_MetaBox() {
    _classCallCheck(this, IC_MetaBox);

    this.$metabox = jQuery('#dws-internal-comments');
    this.$comments_wrapper = this.$metabox.find('.dws-internal-comments');
    this.$textarea = this.$metabox.find('#new-internal-comment');
    this.$overlay = this.$metabox.find('.dws-internal-comments-overlay');
    this.init();
  }

  _createClass(IC_MetaBox, [{
    key: "init",
    value: function init() {
      var instance = this;
      this.$metabox.on('click', 'button.save-insert-internal-comment', function (e) {
        var comment = instance.$textarea.val();

        if ('string' !== typeof comment || comment.trim().length === 0) {
          alert(window.dws_ic_metabox_vars.empty_comment_msg);
          return;
        }

        e.preventDefault();
        instance.insert_comment(comment);
      });
      this.$metabox.on('click', '.dws-internal-comment__actions .internal-comment-action ', function (e) {
        var $target = jQuery(e.target),
            action = $target.data('action'),
            nonce = $target.data('nonce'),
            $comment_wrapper = $target.parents('li.dws-internal-comment'),
            comment_id = parseInt($comment_wrapper.attr('rel'));

        if ('string' === typeof action && action.trim().length > 0) {
          window.wp.hooks.doAction('dws_ic.metabox.action', action, comment_id, nonce, e, $target, $comment_wrapper, instance);
          window.wp.hooks.doAction('dws_ic.metabox.action.' + action, comment_id, nonce, e, $target, $comment_wrapper, instance);
        }
      });
      window.wp.hooks.addAction('dws_ic.metabox.action', 'dws/internal-comments/metabox', this.comment_action_click_handler, 10);
    }
  }, {
    key: "comment_action_click_handler",
    value: function comment_action_click_handler(action, comment_id, nonce, e) {
      switch (action) {
        case 'trash':
          e.preventDefault();
          IC_MetaBox.get_instance().trash_comment(comment_id, nonce);
          break;
      }
    }
  }, {
    key: "refresh_comments",
    value: function refresh_comments() {
      var instance = this;
      instance.$overlay.show();
      window.dws_ic_ajax.make_request('GET', 'dws_ic_get_comments_list_output', {
        post_id: window.dws_ic_metabox_vars.post_id
      }, {
        success: function success(data) {
          instance.$comments_wrapper.html(data);
        },
        always: function always() {
          instance.$overlay.hide();
        }
      });
    }
  }, {
    key: "insert_comment",
    value: function insert_comment(content) {
      var instance = this;
      instance.$overlay.show();
      window.dws_ic_ajax.insert_comment(window.dws_ic_metabox_vars.post_id, window.dws_ic_metabox_vars.insert_comment_nonce, {
        content: content
      }, {
        success: function success() {
          instance.$textarea.val('');
          instance.refresh_comments();
        },
        always: function always() {
          instance.$overlay.hide();
        }
      }, instance);
    }
  }, {
    key: "trash_comment",
    value: function trash_comment(comment_id, nonce) {
      var instance = this;
      instance.$overlay.show();
      window.dws_ic_ajax.trash_comment(comment_id, nonce, {
        success: function success() {
          instance.refresh_comments();
        },
        always: function always() {
          instance.$overlay.hide();
        }
      }, instance);
    }
  }], [{
    key: "get_instance",
    value: function get_instance() {
      if (!IC_MetaBox.instance) {
        IC_MetaBox.instance = new IC_MetaBox();
      }

      return IC_MetaBox.instance;
    }
  }]);

  return IC_MetaBox;
}();
;// CONCATENATED MODULE: ./src/assets/dev/ts/metabox.ts

jQuery(function ($) {
  window.dws_ic_metabox = IC_MetaBox.get_instance();
});
/******/ })()
;
//# sourceMappingURL=metabox.js.map