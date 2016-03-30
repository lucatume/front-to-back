var ftb =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	var Events = __webpack_require__(1),
	    Attachments = __webpack_require__(3);

	window.FTB = {
		Events: Events,
		Attachments: new Attachments()
	};

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	var Backbone = __webpack_require__(2);

	module.exports = _.extend({}, Backbone.Events);

/***/ },
/* 2 */
/***/ function(module, exports) {

	// provided by WordPress
	module.exports = window.Backbone;

/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	var $ = __webpack_require__(4),
	    Backbone = __webpack_require__(2),
	    Events = __webpack_require__(1),
	    Backend = __webpack_require__(5),
	    utils = __webpack_require__(7);

	module.exports = Backbone.Model.extend({

		events: Events,

		backend: new Backend(),

		replace: function (element, newSrc) {
			var $element = $(element),
			    size,
			    attr,
			    html,
			    self = this;

			$element.each(function () {
				$this = $(this);
				self.events.trigger('ftb.attachment.replace_src.before', element, newSrc);

				size = $this.data('ftb-size');
				attr = $this.data('ftb-attr');
				html = self.backend.get_attachment_image_from(newSrc, size, attr).success(function (html) {
					if (html === false) {
						return;
					}

					//var escaped_html = utils.json_unescape( html );
					$this.replaceWith(html);

					self.events.trigger('ftb.attachment.replace_src.after', element, newSrc, html);
				});
			});
		}
	});

/***/ },
/* 4 */
/***/ function(module, exports) {

	// provided by WordPress
	module.exports = window.jQuery;

/***/ },
/* 5 */
/***/ function(module, exports, __webpack_require__) {

	var $ = __webpack_require__(4),
	    Backbone = __webpack_require__(2),
	    ftbData = __webpack_require__(6);

	module.exports = Backbone.Model.extend({
		get_attachment_image_from: function (newSrc, size, attr) {
			var settings = {
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-NONCE', ftbData.nonce);
				},
				url: ftbData.rest_url_prefix + '/ftb/v1/markup/attachment',
				data: {
					newSrc: newSrc,
					size: size,
					attr: attr
				},
				dataType: 'json'
			};

			return $.get(settings);
		}
	});

/***/ },
/* 6 */
/***/ function(module, exports) {

	// provided by WordPress
	module.exports = window.ftbData;

/***/ },
/* 7 */
/***/ function(module, exports) {

	module.exports = {
		json_unescape: function (escaped) {
			escaped = escaped.replace(/\\"/g, "\"");
			escaped = escaped.replace(/\\\//g, "/");

			return escaped.replace(/(^")|("$)/g, '');
		}
	};

/***/ }
/******/ ]);