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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/core/wp-session.js":
/*!***********************************!*\
  !*** ./src/js/core/wp-session.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * @generatePassword\r\n * @createRoom\r\n */\nfunction generatePassword() {\n  var length = 8,\n      charset = \"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\",\n      retVal = \"\";\n\n  for (var i = 0, n = charset.length; i < length; ++i) {\n    retVal += charset.charAt(Math.floor(Math.random() * n));\n  }\n\n  return retVal;\n}\n\nfunction createRoom(button, api) {\n  var roomInputEditor = document.querySelector('.acf-fields .acf-field:last-child input');\n  fetch(\"\".concat(api, \"/session\"), {\n    method: 'POST'\n  }).then(function (res) {\n    if (res.status >= 200 && res.status < 300) {\n      return res.json();\n    } else {\n      throw res;\n    }\n  }).then(function (session) {\n    roomInputEditor.value = session.id;\n    button.value = \"Crear sala\";\n  }).catch(function (err) {\n    throw err;\n  });\n}\n/**\r\n * DOM\r\n */\n\n\ndocument.getElementById('generate-key').onclick = function () {\n  var codeInputEditor = document.querySelector('.acf-fields .acf-field:first-child input');\n  codeInputEditor.value = generatePassword();\n};\n\ndocument.getElementById('create-room').onclick = function () {\n  this.value = 'Creando...';\n  createRoom(this, \"\".concat(this.getAttribute('data-site'), \"/wp-json/custom/v1\"));\n};\n\n//# sourceURL=webpack:///./src/js/core/wp-session.js?");

/***/ }),

/***/ 4:
/*!*****************************************!*\
  !*** multi ./src/js/core/wp-session.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! C:\\laragon\\www\\mab\\wp-content\\themes\\mab-theme\\src\\js\\core\\wp-session.js */\"./src/js/core/wp-session.js\");\n\n\n//# sourceURL=webpack:///multi_./src/js/core/wp-session.js?");

/***/ })

/******/ });
=======
<<<<<<< HEAD
!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=105)}({105:function(e,t,n){e.exports=n(106)},106:function(e,t){document.getElementById("generate-key").onclick=function(){document.querySelector(".acf-fields .acf-field:first-child input").value=function(){for(var e="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",t="",n=0,r=e.length;n<8;++n)t+=e.charAt(Math.floor(Math.random()*r));return t}()},document.getElementById("create-room").onclick=function(){var e,t,n;this.value="Creando...",e=this,t="".concat(this.getAttribute("data-site"),"/wp-json/custom/v1"),n=document.querySelector(".acf-fields .acf-field:last-child input"),fetch("".concat(t,"/session"),{method:"POST"}).then((function(e){if(e.status>=200&&e.status<300)return e.json();throw e})).then((function(t){n.value=t.id,e.value="Crear sala"})).catch((function(e){throw e}))}}});
=======
!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=110)}({110:function(e,t,n){e.exports=n(111)},111:function(e,t){document.getElementById("generate-key").onclick=function(){document.querySelector(".acf-fields .acf-field:first-child input").value=function(){for(var e="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",t="",n=0,r=e.length;n<8;++n)t+=e.charAt(Math.floor(Math.random()*r));return t}()},document.getElementById("create-room").onclick=function(){var e,t,n;this.value="Creando...",e=this,t="".concat(this.getAttribute("data-site"),"/wp-json/custom/v1"),n=document.querySelector(".acf-fields .acf-field:last-child input"),fetch("".concat(t,"/session"),{method:"POST"}).then((function(e){if(e.status>=200&&e.status<300)return e.json();throw e})).then((function(t){n.value=t.id,e.value="Crear sala"})).catch((function(e){throw e}))}}});
>>>>>>> 50b1615be3326bb0afd083aea1da59edf6ee8acf
>>>>>>> 8ccbc6343fd1371b3173cd15f4e6e7a327d8edab
