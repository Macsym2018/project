this.wp=this.wp||{},this.wp.a11y=function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=322)}({186:function(e,t){!function(){e.exports=this.wp.domReady}()},322:function(e,t,n){"use strict";n.r(t);var r=n(186),o=n.n(r),i=function(e){e=e||"polite";var t=document.createElement("div");return t.id="a11y-speak-"+e,t.className="a11y-speak-region",t.setAttribute("style","position: absolute;margin: -1px;padding: 0;height: 1px;width: 1px;overflow: hidden;clip: rect(1px, 1px, 1px, 1px);-webkit-clip-path: inset(50%);clip-path: inset(50%);border: 0;word-wrap: normal !important;"),t.setAttribute("aria-live",e),t.setAttribute("aria-relevant","additions text"),t.setAttribute("aria-atomic","true"),document.querySelector("body").appendChild(t),t},a=function(){for(var e=document.querySelectorAll(".a11y-speak-region"),t=0;t<e.length;t++)e[t].textContent=""},u="",l=function(e){return e=e.replace(/<[^<>]+>/g," "),u===e&&(e+=" "),u=e,e};n.d(t,"setup",(function(){return p})),n.d(t,"speak",(function(){return c}));var p=function(){var e=document.getElementById("a11y-speak-polite"),t=document.getElementById("a11y-speak-assertive");null===e&&(e=i("polite")),null===t&&(t=i("assertive"))};o()(p);var c=function(e,t){a(),e=l(e);var n=document.getElementById("a11y-speak-polite"),r=document.getElementById("a11y-speak-assertive");r&&"assertive"===t?r.textContent=e:n&&(n.textContent=e)}}});