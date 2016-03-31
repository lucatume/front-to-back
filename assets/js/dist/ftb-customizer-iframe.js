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

	var Events = __webpack_require__(10),
	    Attachments = __webpack_require__(12);

	window.FTB = {
		Events: Events,
		Attachments: new Attachments()
	};

/***/ },
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */
/***/ function(module, exports) {

	// provided by WordPress
	module.exports = window.jQuery;

/***/ },
/* 9 */
/***/ function(module, exports) {

	// localized by WordPress
	module.exports = window.ftbData;

/***/ },
/* 10 */
/***/ function(module, exports, __webpack_require__) {

	var Backbone = __webpack_require__(11);

	module.exports = _.extend({}, Backbone.Events);

/***/ },
/* 11 */
/***/ function(module, exports) {

	// provided by WordPress
	module.exports = window.Backbone;

/***/ },
/* 12 */
/***/ function(module, exports, __webpack_require__) {

	var $ = __webpack_require__(8),
	    Backbone = __webpack_require__(11),
	    Events = __webpack_require__(10),
	    Backend = __webpack_require__(13);

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

					$this.replaceWith(html);

					self.events.trigger('ftb.attachment.replace_src.after', element, newSrc, html);
				});
			});
		}
	});

/***/ },
/* 13 */
/***/ function(module, exports, __webpack_require__) {

	var $ = __webpack_require__(8),
	    Backbone = __webpack_require__(11),
	    ftbData = __webpack_require__(9);

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

/***/ }
/******/ ]);