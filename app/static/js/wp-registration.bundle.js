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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/core/wp-registration.js":
/*!****************************************!*\
  !*** ./src/js/core/wp-registration.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var metabox = document.querySelector('#side-sortables');\nmetabox.innerHTML += \"\\n  <div class=\\\"postbox \\\">\\n    <button type=\\\"button\\\" class=\\\"handlediv\\\" aria-expanded=\\\"true\\\"><span class=\\\"screen-reader-text\\\">Alternar panel: Exportar</span><span class=\\\"toggle-indicator\\\" aria-hidden=\\\"true\\\"></span></button>\\n    <h2 class=\\\"hndle ui-sortable-handle\\\"><span>Registos vencidos <b style=\\\"color: red\\\">12</b></span></h2>\\n    <div class=\\\"inside\\\">\\n      <div>\\n        <div style=\\\"display: flex; justify-content: flex-end\\\">\\n          <span id=\\\"export-spinner\\\" class=\\\"spinner\\\"></span>\\n          <button class=\\\"button button-primary button-large\\\" id=\\\"export\\\">Exportar (.xls)</button>\\n        </div>\\n        <div class=\\\"clear\\\"></div>\\n      </div>\\n    </div>\\n  </div>\\n\";\n/**\n * @exportRegistrations\n */\n\nfunction exportRegistrations() {\n  alert();\n}\n\nfunction spinnerLoading(state) {\n  if (state) {\n    document.querySelector('#export-spinner').style = \"visibility: visible\";\n  } else {\n    document.querySelector('#export-spinner').style = \"visibility: hidden\";\n  }\n}\n/**\n * DOM\n */\n\n\ndocument.querySelector('#export').onclick = function () {\n  spinnerLoading(true);\n  event.preventDefault();\n  exportRegistrations();\n  spinnerLoading(false);\n};\n\n//# sourceURL=webpack:///./src/js/core/wp-registration.js?");

/***/ }),

/***/ 1:
/*!**********************************************!*\
  !*** multi ./src/js/core/wp-registration.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! C:\\xampp\\htdocs\\mab\\wp-content\\themes\\mab-theme\\src\\js\\core\\wp-registration.js */\"./src/js/core/wp-registration.js\");\n\n\n//# sourceURL=webpack:///multi_./src/js/core/wp-registration.js?");

/***/ })

/******/ });