/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/assets/dev/ts/models/IC_AJAX.ts
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var IC_AJAX = function () {
  function IC_AJAX() {
    _classCallCheck(this, IC_AJAX);
  }

  _createClass(IC_AJAX, [{
    key: "make_request",
    value: function make_request(method, action, data) {
      var _window$wp$hooks;

      var callbacks = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};

      for (var _len = arguments.length, filter_args = new Array(_len > 4 ? _len - 4 : 0), _key = 4; _key < _len; _key++) {
        filter_args[_key - 4] = arguments[_key];
      }

      jQuery.ajax(window.ajaxurl, {
        method: method,
        data: (_window$wp$hooks = window.wp.hooks).applyFilters.apply(_window$wp$hooks, [action.replace('dws_ic_', 'dws_ic.ajax.') + '.data', _objectSpread({
          action: action
        }, data)].concat(filter_args))
      }).done(function (data) {
        if (data.success) {
          if (undefined !== callbacks.success) {
            callbacks.success(data.data);
          }
        } else {
          if (undefined === callbacks.error) {
            alert(data.data);
          } else {
            callbacks.error(data.data);
          }
        }
      }).fail(function (jqXHR, textStatus) {
        console.log(jqXHR, textStatus);

        if (undefined === callbacks.fail) {
          var error_message = jqXHR.status + ': ' + jqXHR.statusText;
          alert(window.dws_ic_ajax_vars.ajax_fail_msg.replace('%error_msg%', error_message));
        } else {
          callbacks.fail(jqXHR, textStatus);
        }
      }).always(function () {
        if (undefined !== callbacks.always) {
          callbacks.always();
        }
      });
    }
  }, {
    key: "insert_comment",
    value: function insert_comment(post_id, nonce, data, callbacks) {
      for (var _len2 = arguments.length, filter_args = new Array(_len2 > 4 ? _len2 - 4 : 0), _key2 = 4; _key2 < _len2; _key2++) {
        filter_args[_key2 - 4] = arguments[_key2];
      }

      this.make_request.apply(this, ['POST', 'dws_ic_insert_comment', _objectSpread({
        post_id: post_id,
        _wpnonce: nonce
      }, data), callbacks].concat(filter_args));
    }
  }, {
    key: "get_comments",
    value: function get_comments(post_id, callbacks) {
      for (var _len3 = arguments.length, filter_args = new Array(_len3 > 2 ? _len3 - 2 : 0), _key3 = 2; _key3 < _len3; _key3++) {
        filter_args[_key3 - 2] = arguments[_key3];
      }

      this.make_request.apply(this, ['GET', 'dws_ic_get_comments', {
        post_id: post_id,
        _wpnonce: window.dws_ic_ajax_vars.get_comments_nonce
      }, callbacks].concat(filter_args));
    }
  }, {
    key: "get_comment",
    value: function get_comment(comment_id, callbacks) {
      for (var _len4 = arguments.length, filter_args = new Array(_len4 > 2 ? _len4 - 2 : 0), _key4 = 2; _key4 < _len4; _key4++) {
        filter_args[_key4 - 2] = arguments[_key4];
      }

      this.make_request.apply(this, ['GET', 'dws_ic_get_comment', {
        comment_id: comment_id,
        _wpnonce: window.dws_ic_ajax_vars.get_comments_nonce
      }, callbacks].concat(filter_args));
    }
  }, {
    key: "update_comment",
    value: function update_comment(comment_id, nonce, data, callbacks) {
      for (var _len5 = arguments.length, filter_args = new Array(_len5 > 4 ? _len5 - 4 : 0), _key5 = 4; _key5 < _len5; _key5++) {
        filter_args[_key5 - 4] = arguments[_key5];
      }

      this.make_request.apply(this, ['POST', 'dws_ic_update_comment', _objectSpread({
        comment_id: comment_id,
        _wpnonce: nonce
      }, data), callbacks].concat(filter_args));
    }
  }, {
    key: "trash_comment",
    value: function trash_comment(comment_id, nonce, callbacks) {
      for (var _len6 = arguments.length, filter_args = new Array(_len6 > 3 ? _len6 - 3 : 0), _key6 = 3; _key6 < _len6; _key6++) {
        filter_args[_key6 - 3] = arguments[_key6];
      }

      this.make_request.apply(this, ['POST', 'dws_ic_trash_comment', {
        comment_id: comment_id,
        _wpnonce: nonce
      }, callbacks].concat(filter_args));
    }
  }], [{
    key: "get_instance",
    value: function get_instance() {
      if (!IC_AJAX.instance) {
        IC_AJAX.instance = new IC_AJAX();
      }

      return IC_AJAX.instance;
    }
  }]);

  return IC_AJAX;
}();
;// CONCATENATED MODULE: ./src/assets/dev/ts/ajax.ts

jQuery(function () {
  window.dws_ic_ajax = IC_AJAX.get_instance();
});
/******/ })()
;
//# sourceMappingURL=ajax.js.map