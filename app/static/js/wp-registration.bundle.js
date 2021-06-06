<<<<<<< HEAD
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/core/wp-registration.js":
/*!****************************************!*\
  !*** ./src/js/core/wp-registration.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * Global\r\n */\nvar API = window.location.hostname == 'localhost' ? 'http://localhost/mab/wp-json/custom/v1' : 'https://mabclick.com/wp-json/custom/v1';\n/**\r\n * @exportRegistrations\r\n * @spinnerLoading\r\n * @getExpiredRegistrations\r\n * @mountNewCourses\r\n * @refactCourses\r\n */\n\nfunction exportRegistrations() {\n  spinnerLoading(true);\n  fetch(\"\".concat(API, \"/courses/expired_registrations/download\")).then(function (res) {\n    if (res.status >= 200 && res.status < 300) {\n      spinnerLoading(false);\n      window.location = \"\".concat(API, \"/courses/expired_registrations/download\");\n    } else {\n      throw res;\n    }\n  }).catch(function (err) {\n    throw err;\n  });\n}\n\nfunction spinnerLoading(state) {\n  if (state) {\n    document.querySelector('#export-spinner').style = \"visibility: visible\";\n  } else {\n    document.querySelector('#export-spinner').style = \"visibility: hidden\";\n  }\n}\n\nfunction getExpiredRegistrations() {\n  fetch(\"\".concat(API, \"/courses/expired_registrations\")).then(function (res) {\n    if (res.status >= 200 && res.status < 300) {\n      return res.json();\n    } else {\n      throw res;\n    }\n  }).then(function (expireds) {\n    document.querySelector('#expired-counter').innerHTML = expireds.length;\n  }).catch(function (err) {\n    document.querySelector('#export').setAttribute('disabled', true);\n    throw err;\n  });\n}\n\nfunction mountNewCourses(categories, index) {\n  var __courses = document.querySelectorAll(\".select2-results__option[role='treeitem']\");\n\n  __courses.forEach(function (course, index) {\n    categories[index].categories.forEach(function (cat) {\n      course.innerHTML += \" - \".concat(cat.name);\n    });\n  });\n}\n\nfunction refactCourses() {\n  fetch(\"\".concat(API, \"/course/categories\")).then(function (res) {\n    if (res.status >= 200 && res.status < 300) {\n      return res.json();\n    } else {\n      throw res;\n    }\n  }).then(function (categories) {\n    mountNewCourses(categories);\n  }).catch(function (err) {\n    throw err;\n  });\n}\n/**\r\n * View\r\n */\n\n\nvar metabox = document.querySelector('#side-sortables');\nmetabox.innerHTML += \"\\n  <div class=\\\"postbox \\\">\\n    <button type=\\\"button\\\" class=\\\"handlediv\\\" aria-expanded=\\\"true\\\"><span class=\\\"screen-reader-text\\\">Alternar panel: Exportar</span><span class=\\\"toggle-indicator\\\" aria-hidden=\\\"true\\\"></span></button>\\n    <h2 class=\\\"hndle ui-sortable-handle\\\"><span>Registos vencidos <b id=\\\"expired-counter\\\" style=\\\"color: red\\\">0</b></span></h2>\\n    <div class=\\\"inside\\\">\\n      <div>\\n        <div style=\\\"display: flex; justify-content: flex-end\\\">\\n          <span id=\\\"export-spinner\\\" class=\\\"spinner\\\"></span>\\n          <button class=\\\"button button-primary button-large\\\" id=\\\"export\\\">Exportar (.xls)</button>\\n        </div>\\n        <div class=\\\"clear\\\"></div>\\n      </div>\\n    </div>\\n  </div>\\n\";\n/**\r\n * DOM\r\n */\n\ndocument.querySelector('#export').onclick = function () {\n  event.preventDefault();\n  exportRegistrations();\n};\n\ndocument.querySelector('[data-name=\"course\"] .acf-input').onclick = function () {\n  refactCourses();\n};\n\ngetExpiredRegistrations();\n\n//# sourceURL=webpack:///./src/js/core/wp-registration.js?");

/***/ }),

/***/ 3:
/*!**********************************************!*\
  !*** multi ./src/js/core/wp-registration.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! C:\\laragon\\www\\mab\\wp-content\\themes\\mab-theme\\src\\js\\core\\wp-registration.js */\"./src/js/core/wp-registration.js\");\n\n\n//# sourceURL=webpack:///multi_./src/js/core/wp-registration.js?");

/***/ })

/******/ });
=======
<<<<<<< HEAD
!function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=103)}({103:function(t,e,n){t.exports=n(104)},104:function(t,e){var n="localhost"==window.location.hostname?"http://localhost/mab/wp-json/custom/v1":"https://mabclick.com/wp-json/custom/v1";function o(t){document.querySelector("#export-spinner").style=t?"visibility: visible":"visibility: hidden"}function r(){fetch("".concat(n,"/course/categories")).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){!function(t,e){document.querySelectorAll(".select2-results__option[role='treeitem']").forEach((function(e,n){t[n].categories.forEach((function(t){e.innerHTML+=" - ".concat(t.name)}))}))}(t)})).catch((function(t){throw t}))}document.querySelector("#side-sortables").innerHTML+='\n  <div class="postbox ">\n    <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Alternar panel: Exportar</span><span class="toggle-indicator" aria-hidden="true"></span></button>\n    <h2 class="hndle ui-sortable-handle"><span>Registos vencidos <b id="expired-counter" style="color: red">0</b></span></h2>\n    <div class="inside">\n      <div>\n        <div style="display: flex; justify-content: flex-end">\n          <span id="export-spinner" class="spinner"></span>\n          <button class="button button-primary button-large" id="export">Exportar (.xls)</button>\n        </div>\n        <div class="clear"></div>\n      </div>\n    </div>\n  </div>\n',document.querySelector("#export").onclick=function(){event.preventDefault(),o(!0),fetch("".concat(n,"/courses/expired_registrations/download")).then((function(t){if(!(t.status>=200&&t.status<300))throw t;o(!1),window.location="".concat(n,"/courses/expired_registrations/download")})).catch((function(t){throw t}))},document.querySelector('[data-name="course"] .acf-input').onclick=function(){r()},fetch("".concat(n,"/courses/expired_registrations")).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){document.querySelector("#expired-counter").innerHTML=t.length})).catch((function(t){throw document.querySelector("#export").setAttribute("disabled",!0),t}))}});
=======
!function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=108)}({108:function(t,e,n){t.exports=n(109)},109:function(t,e){var n="localhost"==window.location.hostname?"http://localhost/mab/wp-json/custom/v1":"https://mabclick.com/wp-json/custom/v1";function o(t){document.querySelector("#export-spinner").style=t?"visibility: visible":"visibility: hidden"}function r(){fetch("".concat(n,"/course/categories")).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){!function(t,e){document.querySelectorAll(".select2-results__option[role='treeitem']").forEach((function(e,n){t[n].categories.forEach((function(t){e.innerHTML+=" - ".concat(t.name)}))}))}(t)})).catch((function(t){throw t}))}document.querySelector("#side-sortables").innerHTML+='\n  <div class="postbox ">\n    <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Alternar panel: Exportar</span><span class="toggle-indicator" aria-hidden="true"></span></button>\n    <h2 class="hndle ui-sortable-handle"><span>Registos vencidos <b id="expired-counter" style="color: red">0</b></span></h2>\n    <div class="inside">\n      <div>\n        <div style="display: flex; justify-content: flex-end">\n          <span id="export-spinner" class="spinner"></span>\n          <button class="button button-primary button-large" id="export">Exportar (.xls)</button>\n        </div>\n        <div class="clear"></div>\n      </div>\n    </div>\n  </div>\n',document.querySelector("#export").onclick=function(){event.preventDefault(),o(!0),fetch("".concat(n,"/courses/expired_registrations/download")).then((function(t){if(!(t.status>=200&&t.status<300))throw t;o(!1),window.location="".concat(n,"/courses/expired_registrations/download")})).catch((function(t){throw t}))},document.querySelector('[data-name="course"] .acf-input').onclick=function(){r()},fetch("".concat(n,"/courses/expired_registrations")).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){document.querySelector("#expired-counter").innerHTML=t.length})).catch((function(t){throw document.querySelector("#export").setAttribute("disabled",!0),t}))}});
>>>>>>> 50b1615be3326bb0afd083aea1da59edf6ee8acf
>>>>>>> 8ccbc6343fd1371b3173cd15f4e6e7a327d8edab
