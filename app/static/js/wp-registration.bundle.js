!function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=106)}({106:function(t,e,n){t.exports=n(107)},107:function(t,e){var n="localhost"==window.location.hostname?"http://localhost/mab/wp-json/custom/v1":"https://mabclick.com/wp-json/custom/v1";function o(t){document.querySelector("#export-spinner").style=t?"visibility: visible":"visibility: hidden"}function r(){fetch("".concat(n,"/course/categories")).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){!function(t,e){document.querySelectorAll(".select2-results__option[role='treeitem']").forEach((function(e,n){t[n].categories.forEach((function(t){e.innerHTML+=" - ".concat(t.name)}))}))}(t)})).catch((function(t){throw t}))}document.querySelector("#side-sortables").innerHTML+='\n  <div class="postbox ">\n    <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Alternar panel: Exportar</span><span class="toggle-indicator" aria-hidden="true"></span></button>\n    <h2 class="hndle ui-sortable-handle"><span>Registos vencidos <b id="expired-counter" style="color: red">0</b></span></h2>\n    <div class="inside">\n      <div>\n        <div style="display: flex; justify-content: flex-end">\n          <span id="export-spinner" class="spinner"></span>\n          <button class="button button-primary button-large" id="export">Exportar (.xls)</button>\n        </div>\n        <div class="clear"></div>\n      </div>\n    </div>\n  </div>\n',document.querySelector("#export").onclick=function(){event.preventDefault(),o(!0),fetch("".concat(n,"/courses/expired_registrations/download")).then((function(t){if(!(t.status>=200&&t.status<300))throw t;o(!1),window.location="".concat(n,"/courses/expired_registrations/download")})).catch((function(t){throw t}))},document.querySelector('[data-name="course"] .acf-input').onclick=function(){r()},fetch("".concat(n,"/courses/expired_registrations")).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){document.querySelector("#expired-counter").innerHTML=t.length})).catch((function(t){throw document.querySelector("#export").setAttribute("disabled",!0),t}))}});