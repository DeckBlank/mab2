(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["package.ssr-window"],{

/***/ "./node_modules/ssr-window/dist/ssr-window.esm.js":
/*!********************************************************!*\
  !*** ./node_modules/ssr-window/dist/ssr-window.esm.js ***!
  \********************************************************/
/*! exports provided: window, document */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"window\", function() { return win; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"document\", function() { return doc; });\n/**\n * SSR Window 1.0.1\n * Better handling for window object in SSR environment\n * https://github.com/nolimits4web/ssr-window\n *\n * Copyright 2018, Vladimir Kharlampidi\n *\n * Licensed under MIT\n *\n * Released on: July 18, 2018\n */\nvar doc = (typeof document === 'undefined') ? {\n  body: {},\n  addEventListener: function addEventListener() {},\n  removeEventListener: function removeEventListener() {},\n  activeElement: {\n    blur: function blur() {},\n    nodeName: '',\n  },\n  querySelector: function querySelector() {\n    return null;\n  },\n  querySelectorAll: function querySelectorAll() {\n    return [];\n  },\n  getElementById: function getElementById() {\n    return null;\n  },\n  createEvent: function createEvent() {\n    return {\n      initEvent: function initEvent() {},\n    };\n  },\n  createElement: function createElement() {\n    return {\n      children: [],\n      childNodes: [],\n      style: {},\n      setAttribute: function setAttribute() {},\n      getElementsByTagName: function getElementsByTagName() {\n        return [];\n      },\n    };\n  },\n  location: { hash: '' },\n} : document; // eslint-disable-line\n\nvar win = (typeof window === 'undefined') ? {\n  document: doc,\n  navigator: {\n    userAgent: '',\n  },\n  location: {},\n  history: {},\n  CustomEvent: function CustomEvent() {\n    return this;\n  },\n  addEventListener: function addEventListener() {},\n  removeEventListener: function removeEventListener() {},\n  getComputedStyle: function getComputedStyle() {\n    return {\n      getPropertyValue: function getPropertyValue() {\n        return '';\n      },\n    };\n  },\n  Image: function Image() {},\n  Date: function Date() {},\n  screen: {},\n  setTimeout: function setTimeout() {},\n  clearTimeout: function clearTimeout() {},\n} : window; // eslint-disable-line\n\n\n\n\n//# sourceURL=webpack:///./node_modules/ssr-window/dist/ssr-window.esm.js?");

/***/ })

}]);