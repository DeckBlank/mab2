!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=110)}({110:function(e,t,n){e.exports=n(111)},111:function(e,t){document.getElementById("generate-key").onclick=function(){document.querySelector(".acf-fields .acf-field:first-child input").value=function(){for(var e="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",t="",n=0,r=e.length;n<8;++n)t+=e.charAt(Math.floor(Math.random()*r));return t}()},document.getElementById("create-room").onclick=function(){var e,t,n;this.value="Creando...",e=this,t="".concat(this.getAttribute("data-site"),"/wp-json/custom/v1"),n=document.querySelector(".acf-fields .acf-field:last-child input"),fetch("".concat(t,"/session"),{method:"POST"}).then((function(e){if(e.status>=200&&e.status<300)return e.json();throw e})).then((function(t){n.value=t.id,e.value="Crear sala"})).catch((function(e){throw e}))}}});