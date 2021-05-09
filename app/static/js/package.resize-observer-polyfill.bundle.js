(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["package.resize-observer-polyfill"],{

/***/ "./node_modules/resize-observer-polyfill/dist/ResizeObserver.es.js":
/*!*************************************************************************!*\
  !*** ./node_modules/resize-observer-polyfill/dist/ResizeObserver.es.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(global) {/**\r\n * A collection of shims that provide minimal functionality of the ES6 collections.\r\n *\r\n * These implementations are not meant to be used outside of the ResizeObserver\r\n * modules as they cover only a limited range of use cases.\r\n */\r\n/* eslint-disable require-jsdoc, valid-jsdoc */\r\nvar MapShim = (function () {\r\n    if (typeof Map !== 'undefined') {\r\n        return Map;\r\n    }\r\n    /**\r\n     * Returns index in provided array that matches the specified key.\r\n     *\r\n     * @param {Array<Array>} arr\r\n     * @param {*} key\r\n     * @returns {number}\r\n     */\r\n    function getIndex(arr, key) {\r\n        var result = -1;\r\n        arr.some(function (entry, index) {\r\n            if (entry[0] === key) {\r\n                result = index;\r\n                return true;\r\n            }\r\n            return false;\r\n        });\r\n        return result;\r\n    }\r\n    return /** @class */ (function () {\r\n        function class_1() {\r\n            this.__entries__ = [];\r\n        }\r\n        Object.defineProperty(class_1.prototype, \"size\", {\r\n            /**\r\n             * @returns {boolean}\r\n             */\r\n            get: function () {\r\n                return this.__entries__.length;\r\n            },\r\n            enumerable: true,\r\n            configurable: true\r\n        });\r\n        /**\r\n         * @param {*} key\r\n         * @returns {*}\r\n         */\r\n        class_1.prototype.get = function (key) {\r\n            var index = getIndex(this.__entries__, key);\r\n            var entry = this.__entries__[index];\r\n            return entry && entry[1];\r\n        };\r\n        /**\r\n         * @param {*} key\r\n         * @param {*} value\r\n         * @returns {void}\r\n         */\r\n        class_1.prototype.set = function (key, value) {\r\n            var index = getIndex(this.__entries__, key);\r\n            if (~index) {\r\n                this.__entries__[index][1] = value;\r\n            }\r\n            else {\r\n                this.__entries__.push([key, value]);\r\n            }\r\n        };\r\n        /**\r\n         * @param {*} key\r\n         * @returns {void}\r\n         */\r\n        class_1.prototype.delete = function (key) {\r\n            var entries = this.__entries__;\r\n            var index = getIndex(entries, key);\r\n            if (~index) {\r\n                entries.splice(index, 1);\r\n            }\r\n        };\r\n        /**\r\n         * @param {*} key\r\n         * @returns {void}\r\n         */\r\n        class_1.prototype.has = function (key) {\r\n            return !!~getIndex(this.__entries__, key);\r\n        };\r\n        /**\r\n         * @returns {void}\r\n         */\r\n        class_1.prototype.clear = function () {\r\n            this.__entries__.splice(0);\r\n        };\r\n        /**\r\n         * @param {Function} callback\r\n         * @param {*} [ctx=null]\r\n         * @returns {void}\r\n         */\r\n        class_1.prototype.forEach = function (callback, ctx) {\r\n            if (ctx === void 0) { ctx = null; }\r\n            for (var _i = 0, _a = this.__entries__; _i < _a.length; _i++) {\r\n                var entry = _a[_i];\r\n                callback.call(ctx, entry[1], entry[0]);\r\n            }\r\n        };\r\n        return class_1;\r\n    }());\r\n})();\n\n/**\r\n * Detects whether window and document objects are available in current environment.\r\n */\r\nvar isBrowser = typeof window !== 'undefined' && typeof document !== 'undefined' && window.document === document;\n\n// Returns global object of a current environment.\r\nvar global$1 = (function () {\r\n    if (typeof global !== 'undefined' && global.Math === Math) {\r\n        return global;\r\n    }\r\n    if (typeof self !== 'undefined' && self.Math === Math) {\r\n        return self;\r\n    }\r\n    if (typeof window !== 'undefined' && window.Math === Math) {\r\n        return window;\r\n    }\r\n    // eslint-disable-next-line no-new-func\r\n    return Function('return this')();\r\n})();\n\n/**\r\n * A shim for the requestAnimationFrame which falls back to the setTimeout if\r\n * first one is not supported.\r\n *\r\n * @returns {number} Requests' identifier.\r\n */\r\nvar requestAnimationFrame$1 = (function () {\r\n    if (typeof requestAnimationFrame === 'function') {\r\n        // It's required to use a bounded function because IE sometimes throws\r\n        // an \"Invalid calling object\" error if rAF is invoked without the global\r\n        // object on the left hand side.\r\n        return requestAnimationFrame.bind(global$1);\r\n    }\r\n    return function (callback) { return setTimeout(function () { return callback(Date.now()); }, 1000 / 60); };\r\n})();\n\n// Defines minimum timeout before adding a trailing call.\r\nvar trailingTimeout = 2;\r\n/**\r\n * Creates a wrapper function which ensures that provided callback will be\r\n * invoked only once during the specified delay period.\r\n *\r\n * @param {Function} callback - Function to be invoked after the delay period.\r\n * @param {number} delay - Delay after which to invoke callback.\r\n * @returns {Function}\r\n */\r\nfunction throttle (callback, delay) {\r\n    var leadingCall = false, trailingCall = false, lastCallTime = 0;\r\n    /**\r\n     * Invokes the original callback function and schedules new invocation if\r\n     * the \"proxy\" was called during current request.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    function resolvePending() {\r\n        if (leadingCall) {\r\n            leadingCall = false;\r\n            callback();\r\n        }\r\n        if (trailingCall) {\r\n            proxy();\r\n        }\r\n    }\r\n    /**\r\n     * Callback invoked after the specified delay. It will further postpone\r\n     * invocation of the original function delegating it to the\r\n     * requestAnimationFrame.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    function timeoutCallback() {\r\n        requestAnimationFrame$1(resolvePending);\r\n    }\r\n    /**\r\n     * Schedules invocation of the original function.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    function proxy() {\r\n        var timeStamp = Date.now();\r\n        if (leadingCall) {\r\n            // Reject immediately following calls.\r\n            if (timeStamp - lastCallTime < trailingTimeout) {\r\n                return;\r\n            }\r\n            // Schedule new call to be in invoked when the pending one is resolved.\r\n            // This is important for \"transitions\" which never actually start\r\n            // immediately so there is a chance that we might miss one if change\r\n            // happens amids the pending invocation.\r\n            trailingCall = true;\r\n        }\r\n        else {\r\n            leadingCall = true;\r\n            trailingCall = false;\r\n            setTimeout(timeoutCallback, delay);\r\n        }\r\n        lastCallTime = timeStamp;\r\n    }\r\n    return proxy;\r\n}\n\n// Minimum delay before invoking the update of observers.\r\nvar REFRESH_DELAY = 20;\r\n// A list of substrings of CSS properties used to find transition events that\r\n// might affect dimensions of observed elements.\r\nvar transitionKeys = ['top', 'right', 'bottom', 'left', 'width', 'height', 'size', 'weight'];\r\n// Check if MutationObserver is available.\r\nvar mutationObserverSupported = typeof MutationObserver !== 'undefined';\r\n/**\r\n * Singleton controller class which handles updates of ResizeObserver instances.\r\n */\r\nvar ResizeObserverController = /** @class */ (function () {\r\n    /**\r\n     * Creates a new instance of ResizeObserverController.\r\n     *\r\n     * @private\r\n     */\r\n    function ResizeObserverController() {\r\n        /**\r\n         * Indicates whether DOM listeners have been added.\r\n         *\r\n         * @private {boolean}\r\n         */\r\n        this.connected_ = false;\r\n        /**\r\n         * Tells that controller has subscribed for Mutation Events.\r\n         *\r\n         * @private {boolean}\r\n         */\r\n        this.mutationEventsAdded_ = false;\r\n        /**\r\n         * Keeps reference to the instance of MutationObserver.\r\n         *\r\n         * @private {MutationObserver}\r\n         */\r\n        this.mutationsObserver_ = null;\r\n        /**\r\n         * A list of connected observers.\r\n         *\r\n         * @private {Array<ResizeObserverSPI>}\r\n         */\r\n        this.observers_ = [];\r\n        this.onTransitionEnd_ = this.onTransitionEnd_.bind(this);\r\n        this.refresh = throttle(this.refresh.bind(this), REFRESH_DELAY);\r\n    }\r\n    /**\r\n     * Adds observer to observers list.\r\n     *\r\n     * @param {ResizeObserverSPI} observer - Observer to be added.\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverController.prototype.addObserver = function (observer) {\r\n        if (!~this.observers_.indexOf(observer)) {\r\n            this.observers_.push(observer);\r\n        }\r\n        // Add listeners if they haven't been added yet.\r\n        if (!this.connected_) {\r\n            this.connect_();\r\n        }\r\n    };\r\n    /**\r\n     * Removes observer from observers list.\r\n     *\r\n     * @param {ResizeObserverSPI} observer - Observer to be removed.\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverController.prototype.removeObserver = function (observer) {\r\n        var observers = this.observers_;\r\n        var index = observers.indexOf(observer);\r\n        // Remove observer if it's present in registry.\r\n        if (~index) {\r\n            observers.splice(index, 1);\r\n        }\r\n        // Remove listeners if controller has no connected observers.\r\n        if (!observers.length && this.connected_) {\r\n            this.disconnect_();\r\n        }\r\n    };\r\n    /**\r\n     * Invokes the update of observers. It will continue running updates insofar\r\n     * it detects changes.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverController.prototype.refresh = function () {\r\n        var changesDetected = this.updateObservers_();\r\n        // Continue running updates if changes have been detected as there might\r\n        // be future ones caused by CSS transitions.\r\n        if (changesDetected) {\r\n            this.refresh();\r\n        }\r\n    };\r\n    /**\r\n     * Updates every observer from observers list and notifies them of queued\r\n     * entries.\r\n     *\r\n     * @private\r\n     * @returns {boolean} Returns \"true\" if any observer has detected changes in\r\n     *      dimensions of it's elements.\r\n     */\r\n    ResizeObserverController.prototype.updateObservers_ = function () {\r\n        // Collect observers that have active observations.\r\n        var activeObservers = this.observers_.filter(function (observer) {\r\n            return observer.gatherActive(), observer.hasActive();\r\n        });\r\n        // Deliver notifications in a separate cycle in order to avoid any\r\n        // collisions between observers, e.g. when multiple instances of\r\n        // ResizeObserver are tracking the same element and the callback of one\r\n        // of them changes content dimensions of the observed target. Sometimes\r\n        // this may result in notifications being blocked for the rest of observers.\r\n        activeObservers.forEach(function (observer) { return observer.broadcastActive(); });\r\n        return activeObservers.length > 0;\r\n    };\r\n    /**\r\n     * Initializes DOM listeners.\r\n     *\r\n     * @private\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverController.prototype.connect_ = function () {\r\n        // Do nothing if running in a non-browser environment or if listeners\r\n        // have been already added.\r\n        if (!isBrowser || this.connected_) {\r\n            return;\r\n        }\r\n        // Subscription to the \"Transitionend\" event is used as a workaround for\r\n        // delayed transitions. This way it's possible to capture at least the\r\n        // final state of an element.\r\n        document.addEventListener('transitionend', this.onTransitionEnd_);\r\n        window.addEventListener('resize', this.refresh);\r\n        if (mutationObserverSupported) {\r\n            this.mutationsObserver_ = new MutationObserver(this.refresh);\r\n            this.mutationsObserver_.observe(document, {\r\n                attributes: true,\r\n                childList: true,\r\n                characterData: true,\r\n                subtree: true\r\n            });\r\n        }\r\n        else {\r\n            document.addEventListener('DOMSubtreeModified', this.refresh);\r\n            this.mutationEventsAdded_ = true;\r\n        }\r\n        this.connected_ = true;\r\n    };\r\n    /**\r\n     * Removes DOM listeners.\r\n     *\r\n     * @private\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverController.prototype.disconnect_ = function () {\r\n        // Do nothing if running in a non-browser environment or if listeners\r\n        // have been already removed.\r\n        if (!isBrowser || !this.connected_) {\r\n            return;\r\n        }\r\n        document.removeEventListener('transitionend', this.onTransitionEnd_);\r\n        window.removeEventListener('resize', this.refresh);\r\n        if (this.mutationsObserver_) {\r\n            this.mutationsObserver_.disconnect();\r\n        }\r\n        if (this.mutationEventsAdded_) {\r\n            document.removeEventListener('DOMSubtreeModified', this.refresh);\r\n        }\r\n        this.mutationsObserver_ = null;\r\n        this.mutationEventsAdded_ = false;\r\n        this.connected_ = false;\r\n    };\r\n    /**\r\n     * \"Transitionend\" event handler.\r\n     *\r\n     * @private\r\n     * @param {TransitionEvent} event\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverController.prototype.onTransitionEnd_ = function (_a) {\r\n        var _b = _a.propertyName, propertyName = _b === void 0 ? '' : _b;\r\n        // Detect whether transition may affect dimensions of an element.\r\n        var isReflowProperty = transitionKeys.some(function (key) {\r\n            return !!~propertyName.indexOf(key);\r\n        });\r\n        if (isReflowProperty) {\r\n            this.refresh();\r\n        }\r\n    };\r\n    /**\r\n     * Returns instance of the ResizeObserverController.\r\n     *\r\n     * @returns {ResizeObserverController}\r\n     */\r\n    ResizeObserverController.getInstance = function () {\r\n        if (!this.instance_) {\r\n            this.instance_ = new ResizeObserverController();\r\n        }\r\n        return this.instance_;\r\n    };\r\n    /**\r\n     * Holds reference to the controller's instance.\r\n     *\r\n     * @private {ResizeObserverController}\r\n     */\r\n    ResizeObserverController.instance_ = null;\r\n    return ResizeObserverController;\r\n}());\n\n/**\r\n * Defines non-writable/enumerable properties of the provided target object.\r\n *\r\n * @param {Object} target - Object for which to define properties.\r\n * @param {Object} props - Properties to be defined.\r\n * @returns {Object} Target object.\r\n */\r\nvar defineConfigurable = (function (target, props) {\r\n    for (var _i = 0, _a = Object.keys(props); _i < _a.length; _i++) {\r\n        var key = _a[_i];\r\n        Object.defineProperty(target, key, {\r\n            value: props[key],\r\n            enumerable: false,\r\n            writable: false,\r\n            configurable: true\r\n        });\r\n    }\r\n    return target;\r\n});\n\n/**\r\n * Returns the global object associated with provided element.\r\n *\r\n * @param {Object} target\r\n * @returns {Object}\r\n */\r\nvar getWindowOf = (function (target) {\r\n    // Assume that the element is an instance of Node, which means that it\r\n    // has the \"ownerDocument\" property from which we can retrieve a\r\n    // corresponding global object.\r\n    var ownerGlobal = target && target.ownerDocument && target.ownerDocument.defaultView;\r\n    // Return the local global object if it's not possible extract one from\r\n    // provided element.\r\n    return ownerGlobal || global$1;\r\n});\n\n// Placeholder of an empty content rectangle.\r\nvar emptyRect = createRectInit(0, 0, 0, 0);\r\n/**\r\n * Converts provided string to a number.\r\n *\r\n * @param {number|string} value\r\n * @returns {number}\r\n */\r\nfunction toFloat(value) {\r\n    return parseFloat(value) || 0;\r\n}\r\n/**\r\n * Extracts borders size from provided styles.\r\n *\r\n * @param {CSSStyleDeclaration} styles\r\n * @param {...string} positions - Borders positions (top, right, ...)\r\n * @returns {number}\r\n */\r\nfunction getBordersSize(styles) {\r\n    var positions = [];\r\n    for (var _i = 1; _i < arguments.length; _i++) {\r\n        positions[_i - 1] = arguments[_i];\r\n    }\r\n    return positions.reduce(function (size, position) {\r\n        var value = styles['border-' + position + '-width'];\r\n        return size + toFloat(value);\r\n    }, 0);\r\n}\r\n/**\r\n * Extracts paddings sizes from provided styles.\r\n *\r\n * @param {CSSStyleDeclaration} styles\r\n * @returns {Object} Paddings box.\r\n */\r\nfunction getPaddings(styles) {\r\n    var positions = ['top', 'right', 'bottom', 'left'];\r\n    var paddings = {};\r\n    for (var _i = 0, positions_1 = positions; _i < positions_1.length; _i++) {\r\n        var position = positions_1[_i];\r\n        var value = styles['padding-' + position];\r\n        paddings[position] = toFloat(value);\r\n    }\r\n    return paddings;\r\n}\r\n/**\r\n * Calculates content rectangle of provided SVG element.\r\n *\r\n * @param {SVGGraphicsElement} target - Element content rectangle of which needs\r\n *      to be calculated.\r\n * @returns {DOMRectInit}\r\n */\r\nfunction getSVGContentRect(target) {\r\n    var bbox = target.getBBox();\r\n    return createRectInit(0, 0, bbox.width, bbox.height);\r\n}\r\n/**\r\n * Calculates content rectangle of provided HTMLElement.\r\n *\r\n * @param {HTMLElement} target - Element for which to calculate the content rectangle.\r\n * @returns {DOMRectInit}\r\n */\r\nfunction getHTMLElementContentRect(target) {\r\n    // Client width & height properties can't be\r\n    // used exclusively as they provide rounded values.\r\n    var clientWidth = target.clientWidth, clientHeight = target.clientHeight;\r\n    // By this condition we can catch all non-replaced inline, hidden and\r\n    // detached elements. Though elements with width & height properties less\r\n    // than 0.5 will be discarded as well.\r\n    //\r\n    // Without it we would need to implement separate methods for each of\r\n    // those cases and it's not possible to perform a precise and performance\r\n    // effective test for hidden elements. E.g. even jQuery's ':visible' filter\r\n    // gives wrong results for elements with width & height less than 0.5.\r\n    if (!clientWidth && !clientHeight) {\r\n        return emptyRect;\r\n    }\r\n    var styles = getWindowOf(target).getComputedStyle(target);\r\n    var paddings = getPaddings(styles);\r\n    var horizPad = paddings.left + paddings.right;\r\n    var vertPad = paddings.top + paddings.bottom;\r\n    // Computed styles of width & height are being used because they are the\r\n    // only dimensions available to JS that contain non-rounded values. It could\r\n    // be possible to utilize the getBoundingClientRect if only it's data wasn't\r\n    // affected by CSS transformations let alone paddings, borders and scroll bars.\r\n    var width = toFloat(styles.width), height = toFloat(styles.height);\r\n    // Width & height include paddings and borders when the 'border-box' box\r\n    // model is applied (except for IE).\r\n    if (styles.boxSizing === 'border-box') {\r\n        // Following conditions are required to handle Internet Explorer which\r\n        // doesn't include paddings and borders to computed CSS dimensions.\r\n        //\r\n        // We can say that if CSS dimensions + paddings are equal to the \"client\"\r\n        // properties then it's either IE, and thus we don't need to subtract\r\n        // anything, or an element merely doesn't have paddings/borders styles.\r\n        if (Math.round(width + horizPad) !== clientWidth) {\r\n            width -= getBordersSize(styles, 'left', 'right') + horizPad;\r\n        }\r\n        if (Math.round(height + vertPad) !== clientHeight) {\r\n            height -= getBordersSize(styles, 'top', 'bottom') + vertPad;\r\n        }\r\n    }\r\n    // Following steps can't be applied to the document's root element as its\r\n    // client[Width/Height] properties represent viewport area of the window.\r\n    // Besides, it's as well not necessary as the <html> itself neither has\r\n    // rendered scroll bars nor it can be clipped.\r\n    if (!isDocumentElement(target)) {\r\n        // In some browsers (only in Firefox, actually) CSS width & height\r\n        // include scroll bars size which can be removed at this step as scroll\r\n        // bars are the only difference between rounded dimensions + paddings\r\n        // and \"client\" properties, though that is not always true in Chrome.\r\n        var vertScrollbar = Math.round(width + horizPad) - clientWidth;\r\n        var horizScrollbar = Math.round(height + vertPad) - clientHeight;\r\n        // Chrome has a rather weird rounding of \"client\" properties.\r\n        // E.g. for an element with content width of 314.2px it sometimes gives\r\n        // the client width of 315px and for the width of 314.7px it may give\r\n        // 314px. And it doesn't happen all the time. So just ignore this delta\r\n        // as a non-relevant.\r\n        if (Math.abs(vertScrollbar) !== 1) {\r\n            width -= vertScrollbar;\r\n        }\r\n        if (Math.abs(horizScrollbar) !== 1) {\r\n            height -= horizScrollbar;\r\n        }\r\n    }\r\n    return createRectInit(paddings.left, paddings.top, width, height);\r\n}\r\n/**\r\n * Checks whether provided element is an instance of the SVGGraphicsElement.\r\n *\r\n * @param {Element} target - Element to be checked.\r\n * @returns {boolean}\r\n */\r\nvar isSVGGraphicsElement = (function () {\r\n    // Some browsers, namely IE and Edge, don't have the SVGGraphicsElement\r\n    // interface.\r\n    if (typeof SVGGraphicsElement !== 'undefined') {\r\n        return function (target) { return target instanceof getWindowOf(target).SVGGraphicsElement; };\r\n    }\r\n    // If it's so, then check that element is at least an instance of the\r\n    // SVGElement and that it has the \"getBBox\" method.\r\n    // eslint-disable-next-line no-extra-parens\r\n    return function (target) { return (target instanceof getWindowOf(target).SVGElement &&\r\n        typeof target.getBBox === 'function'); };\r\n})();\r\n/**\r\n * Checks whether provided element is a document element (<html>).\r\n *\r\n * @param {Element} target - Element to be checked.\r\n * @returns {boolean}\r\n */\r\nfunction isDocumentElement(target) {\r\n    return target === getWindowOf(target).document.documentElement;\r\n}\r\n/**\r\n * Calculates an appropriate content rectangle for provided html or svg element.\r\n *\r\n * @param {Element} target - Element content rectangle of which needs to be calculated.\r\n * @returns {DOMRectInit}\r\n */\r\nfunction getContentRect(target) {\r\n    if (!isBrowser) {\r\n        return emptyRect;\r\n    }\r\n    if (isSVGGraphicsElement(target)) {\r\n        return getSVGContentRect(target);\r\n    }\r\n    return getHTMLElementContentRect(target);\r\n}\r\n/**\r\n * Creates rectangle with an interface of the DOMRectReadOnly.\r\n * Spec: https://drafts.fxtf.org/geometry/#domrectreadonly\r\n *\r\n * @param {DOMRectInit} rectInit - Object with rectangle's x/y coordinates and dimensions.\r\n * @returns {DOMRectReadOnly}\r\n */\r\nfunction createReadOnlyRect(_a) {\r\n    var x = _a.x, y = _a.y, width = _a.width, height = _a.height;\r\n    // If DOMRectReadOnly is available use it as a prototype for the rectangle.\r\n    var Constr = typeof DOMRectReadOnly !== 'undefined' ? DOMRectReadOnly : Object;\r\n    var rect = Object.create(Constr.prototype);\r\n    // Rectangle's properties are not writable and non-enumerable.\r\n    defineConfigurable(rect, {\r\n        x: x, y: y, width: width, height: height,\r\n        top: y,\r\n        right: x + width,\r\n        bottom: height + y,\r\n        left: x\r\n    });\r\n    return rect;\r\n}\r\n/**\r\n * Creates DOMRectInit object based on the provided dimensions and the x/y coordinates.\r\n * Spec: https://drafts.fxtf.org/geometry/#dictdef-domrectinit\r\n *\r\n * @param {number} x - X coordinate.\r\n * @param {number} y - Y coordinate.\r\n * @param {number} width - Rectangle's width.\r\n * @param {number} height - Rectangle's height.\r\n * @returns {DOMRectInit}\r\n */\r\nfunction createRectInit(x, y, width, height) {\r\n    return { x: x, y: y, width: width, height: height };\r\n}\n\n/**\r\n * Class that is responsible for computations of the content rectangle of\r\n * provided DOM element and for keeping track of it's changes.\r\n */\r\nvar ResizeObservation = /** @class */ (function () {\r\n    /**\r\n     * Creates an instance of ResizeObservation.\r\n     *\r\n     * @param {Element} target - Element to be observed.\r\n     */\r\n    function ResizeObservation(target) {\r\n        /**\r\n         * Broadcasted width of content rectangle.\r\n         *\r\n         * @type {number}\r\n         */\r\n        this.broadcastWidth = 0;\r\n        /**\r\n         * Broadcasted height of content rectangle.\r\n         *\r\n         * @type {number}\r\n         */\r\n        this.broadcastHeight = 0;\r\n        /**\r\n         * Reference to the last observed content rectangle.\r\n         *\r\n         * @private {DOMRectInit}\r\n         */\r\n        this.contentRect_ = createRectInit(0, 0, 0, 0);\r\n        this.target = target;\r\n    }\r\n    /**\r\n     * Updates content rectangle and tells whether it's width or height properties\r\n     * have changed since the last broadcast.\r\n     *\r\n     * @returns {boolean}\r\n     */\r\n    ResizeObservation.prototype.isActive = function () {\r\n        var rect = getContentRect(this.target);\r\n        this.contentRect_ = rect;\r\n        return (rect.width !== this.broadcastWidth ||\r\n            rect.height !== this.broadcastHeight);\r\n    };\r\n    /**\r\n     * Updates 'broadcastWidth' and 'broadcastHeight' properties with a data\r\n     * from the corresponding properties of the last observed content rectangle.\r\n     *\r\n     * @returns {DOMRectInit} Last observed content rectangle.\r\n     */\r\n    ResizeObservation.prototype.broadcastRect = function () {\r\n        var rect = this.contentRect_;\r\n        this.broadcastWidth = rect.width;\r\n        this.broadcastHeight = rect.height;\r\n        return rect;\r\n    };\r\n    return ResizeObservation;\r\n}());\n\nvar ResizeObserverEntry = /** @class */ (function () {\r\n    /**\r\n     * Creates an instance of ResizeObserverEntry.\r\n     *\r\n     * @param {Element} target - Element that is being observed.\r\n     * @param {DOMRectInit} rectInit - Data of the element's content rectangle.\r\n     */\r\n    function ResizeObserverEntry(target, rectInit) {\r\n        var contentRect = createReadOnlyRect(rectInit);\r\n        // According to the specification following properties are not writable\r\n        // and are also not enumerable in the native implementation.\r\n        //\r\n        // Property accessors are not being used as they'd require to define a\r\n        // private WeakMap storage which may cause memory leaks in browsers that\r\n        // don't support this type of collections.\r\n        defineConfigurable(this, { target: target, contentRect: contentRect });\r\n    }\r\n    return ResizeObserverEntry;\r\n}());\n\nvar ResizeObserverSPI = /** @class */ (function () {\r\n    /**\r\n     * Creates a new instance of ResizeObserver.\r\n     *\r\n     * @param {ResizeObserverCallback} callback - Callback function that is invoked\r\n     *      when one of the observed elements changes it's content dimensions.\r\n     * @param {ResizeObserverController} controller - Controller instance which\r\n     *      is responsible for the updates of observer.\r\n     * @param {ResizeObserver} callbackCtx - Reference to the public\r\n     *      ResizeObserver instance which will be passed to callback function.\r\n     */\r\n    function ResizeObserverSPI(callback, controller, callbackCtx) {\r\n        /**\r\n         * Collection of resize observations that have detected changes in dimensions\r\n         * of elements.\r\n         *\r\n         * @private {Array<ResizeObservation>}\r\n         */\r\n        this.activeObservations_ = [];\r\n        /**\r\n         * Registry of the ResizeObservation instances.\r\n         *\r\n         * @private {Map<Element, ResizeObservation>}\r\n         */\r\n        this.observations_ = new MapShim();\r\n        if (typeof callback !== 'function') {\r\n            throw new TypeError('The callback provided as parameter 1 is not a function.');\r\n        }\r\n        this.callback_ = callback;\r\n        this.controller_ = controller;\r\n        this.callbackCtx_ = callbackCtx;\r\n    }\r\n    /**\r\n     * Starts observing provided element.\r\n     *\r\n     * @param {Element} target - Element to be observed.\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverSPI.prototype.observe = function (target) {\r\n        if (!arguments.length) {\r\n            throw new TypeError('1 argument required, but only 0 present.');\r\n        }\r\n        // Do nothing if current environment doesn't have the Element interface.\r\n        if (typeof Element === 'undefined' || !(Element instanceof Object)) {\r\n            return;\r\n        }\r\n        if (!(target instanceof getWindowOf(target).Element)) {\r\n            throw new TypeError('parameter 1 is not of type \"Element\".');\r\n        }\r\n        var observations = this.observations_;\r\n        // Do nothing if element is already being observed.\r\n        if (observations.has(target)) {\r\n            return;\r\n        }\r\n        observations.set(target, new ResizeObservation(target));\r\n        this.controller_.addObserver(this);\r\n        // Force the update of observations.\r\n        this.controller_.refresh();\r\n    };\r\n    /**\r\n     * Stops observing provided element.\r\n     *\r\n     * @param {Element} target - Element to stop observing.\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverSPI.prototype.unobserve = function (target) {\r\n        if (!arguments.length) {\r\n            throw new TypeError('1 argument required, but only 0 present.');\r\n        }\r\n        // Do nothing if current environment doesn't have the Element interface.\r\n        if (typeof Element === 'undefined' || !(Element instanceof Object)) {\r\n            return;\r\n        }\r\n        if (!(target instanceof getWindowOf(target).Element)) {\r\n            throw new TypeError('parameter 1 is not of type \"Element\".');\r\n        }\r\n        var observations = this.observations_;\r\n        // Do nothing if element is not being observed.\r\n        if (!observations.has(target)) {\r\n            return;\r\n        }\r\n        observations.delete(target);\r\n        if (!observations.size) {\r\n            this.controller_.removeObserver(this);\r\n        }\r\n    };\r\n    /**\r\n     * Stops observing all elements.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverSPI.prototype.disconnect = function () {\r\n        this.clearActive();\r\n        this.observations_.clear();\r\n        this.controller_.removeObserver(this);\r\n    };\r\n    /**\r\n     * Collects observation instances the associated element of which has changed\r\n     * it's content rectangle.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverSPI.prototype.gatherActive = function () {\r\n        var _this = this;\r\n        this.clearActive();\r\n        this.observations_.forEach(function (observation) {\r\n            if (observation.isActive()) {\r\n                _this.activeObservations_.push(observation);\r\n            }\r\n        });\r\n    };\r\n    /**\r\n     * Invokes initial callback function with a list of ResizeObserverEntry\r\n     * instances collected from active resize observations.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverSPI.prototype.broadcastActive = function () {\r\n        // Do nothing if observer doesn't have active observations.\r\n        if (!this.hasActive()) {\r\n            return;\r\n        }\r\n        var ctx = this.callbackCtx_;\r\n        // Create ResizeObserverEntry instance for every active observation.\r\n        var entries = this.activeObservations_.map(function (observation) {\r\n            return new ResizeObserverEntry(observation.target, observation.broadcastRect());\r\n        });\r\n        this.callback_.call(ctx, entries, ctx);\r\n        this.clearActive();\r\n    };\r\n    /**\r\n     * Clears the collection of active observations.\r\n     *\r\n     * @returns {void}\r\n     */\r\n    ResizeObserverSPI.prototype.clearActive = function () {\r\n        this.activeObservations_.splice(0);\r\n    };\r\n    /**\r\n     * Tells whether observer has active observations.\r\n     *\r\n     * @returns {boolean}\r\n     */\r\n    ResizeObserverSPI.prototype.hasActive = function () {\r\n        return this.activeObservations_.length > 0;\r\n    };\r\n    return ResizeObserverSPI;\r\n}());\n\n// Registry of internal observers. If WeakMap is not available use current shim\r\n// for the Map collection as it has all required methods and because WeakMap\r\n// can't be fully polyfilled anyway.\r\nvar observers = typeof WeakMap !== 'undefined' ? new WeakMap() : new MapShim();\r\n/**\r\n * ResizeObserver API. Encapsulates the ResizeObserver SPI implementation\r\n * exposing only those methods and properties that are defined in the spec.\r\n */\r\nvar ResizeObserver = /** @class */ (function () {\r\n    /**\r\n     * Creates a new instance of ResizeObserver.\r\n     *\r\n     * @param {ResizeObserverCallback} callback - Callback that is invoked when\r\n     *      dimensions of the observed elements change.\r\n     */\r\n    function ResizeObserver(callback) {\r\n        if (!(this instanceof ResizeObserver)) {\r\n            throw new TypeError('Cannot call a class as a function.');\r\n        }\r\n        if (!arguments.length) {\r\n            throw new TypeError('1 argument required, but only 0 present.');\r\n        }\r\n        var controller = ResizeObserverController.getInstance();\r\n        var observer = new ResizeObserverSPI(callback, controller, this);\r\n        observers.set(this, observer);\r\n    }\r\n    return ResizeObserver;\r\n}());\r\n// Expose public methods of ResizeObserver.\r\n[\r\n    'observe',\r\n    'unobserve',\r\n    'disconnect'\r\n].forEach(function (method) {\r\n    ResizeObserver.prototype[method] = function () {\r\n        var _a;\r\n        return (_a = observers.get(this))[method].apply(_a, arguments);\r\n    };\r\n});\n\nvar index = (function () {\r\n    // Export existing implementation if available.\r\n    if (typeof global$1.ResizeObserver !== 'undefined') {\r\n        return global$1.ResizeObserver;\r\n    }\r\n    return ResizeObserver;\r\n})();\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (index);\n\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../webpack/buildin/global.js */ \"./node_modules/webpack/buildin/global.js\")))\n\n//# sourceURL=webpack:///./node_modules/resize-observer-polyfill/dist/ResizeObserver.es.js?");

/***/ })

}]);