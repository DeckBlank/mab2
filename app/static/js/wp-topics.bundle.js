!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=111)}({11:function(t,e){t.exports=function(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}},111:function(t,e,n){t.exports=n(112)},112:function(t,e,n){"use strict";n.r(e);var r=n(9),o=n.n(r);({nav:document.querySelector(".tablenav.top > .bulkactions")}).nav.innerHTML+='\n  <button id="export-topics" class="button button-primary ml-2">Exportar temas</button>\n',document.querySelector("#export-topics").onclick=function(t){var e,n,r,c;t.preventDefault(),t.target.disabled=!0,e=t.target,n=new URLSearchParams(window.location.search).get("paged"),r=o()(document.querySelectorAll(".type-topic > .check-column > input")).filter((function(t){return t.checked})).map((function(t){return t.value})),c=n?"".concat(mab.site,"/wp-json/custom/v1/topics/export?page=").concat(n):"".concat(mab.site,"/wp-json/custom/v1/topics/export?page=1"),c=r.length?"".concat(c,"&ids=").concat(r.join(",")):c,fetch(c).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){var n="data:text/json;charset=utf-8,"+encodeURIComponent(JSON.stringify(t)),r=document.createElement("a");r.setAttribute("href",n),r.setAttribute("download","topics.json"),r.click(),e.disabled=!1})).catch((function(t){throw t}))}},14:function(t,e,n){var r=n(11);t.exports=function(t){if(Array.isArray(t))return r(t)}},15:function(t,e){t.exports=function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}},16:function(t,e,n){var r=n(11);t.exports=function(t,e){if(t){if("string"==typeof t)return r(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?r(t,e):void 0}}},17:function(t,e){t.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},9:function(t,e,n){var r=n(14),o=n(15),c=n(16),u=n(17);t.exports=function(t){return r(t)||o(t)||c(t)||u()}}});