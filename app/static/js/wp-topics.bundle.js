!function(t){function e(e){for(var r,u,a=e[0],i=e[1],p=e[2],s=0,f=[];s<a.length;s++)u=a[s],Object.prototype.hasOwnProperty.call(o,u)&&o[u]&&f.push(o[u][0]),o[u]=0;for(r in i)Object.prototype.hasOwnProperty.call(i,r)&&(t[r]=i[r]);for(l&&l(e);f.length;)f.shift()();return c.push.apply(c,p||[]),n()}function n(){for(var t,e=0;e<c.length;e++){for(var n=c[e],r=!0,a=1;a<n.length;a++){var i=n[a];0!==o[i]&&(r=!1)}r&&(c.splice(e--,1),t=u(u.s=n[0]))}return t}var r={},o={45:0},c=[];function u(e){if(r[e])return r[e].exports;var n=r[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,u),n.l=!0,n.exports}u.m=t,u.c=r,u.d=function(t,e,n){u.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},u.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},u.t=function(t,e){if(1&e&&(t=u(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(u.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)u.d(n,r,function(e){return t[e]}.bind(null,r));return n},u.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return u.d(e,"a",e),e},u.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},u.p="";var a=window.webpackJsonp=window.webpackJsonp||[],i=a.push.bind(a);a.push=e,a=a.slice();for(var p=0;p<a.length;p++)e(a[p]);var l=i;c.push([117,2]),n()}({117:function(t,e,n){t.exports=n(118)},118:function(t,e,n){"use strict";n.r(e);var r=n(4),o=n.n(r);({nav:document.querySelector(".tablenav.top > .bulkactions")}).nav.innerHTML+='\n  <button id="export-topics" class="button button-primary ml-2">Exportar temas</button>\n',document.querySelector("#export-topics").onclick=function(t){var e,n,r,c;t.preventDefault(),t.target.disabled=!0,e=t.target,n=new URLSearchParams(window.location.search).get("paged"),r=o()(document.querySelectorAll(".type-topic > .check-column > input")).filter((function(t){return t.checked})).map((function(t){return t.value})),c=n?"".concat(mab.site,"/wp-json/custom/v1/topics/export?page=").concat(n):"".concat(mab.site,"/wp-json/custom/v1/topics/export?page=1"),c=r.length?"".concat(c,"&ids=").concat(r.join(",")):c,fetch(c).then((function(t){if(t.status>=200&&t.status<300)return t.json();throw t})).then((function(t){var n="data:text/json;charset=utf-8,"+encodeURIComponent(JSON.stringify(t)),r=document.createElement("a");r.setAttribute("href",n),r.setAttribute("download","topics.json"),r.click(),e.disabled=!1})).catch((function(t){throw t}))}}});