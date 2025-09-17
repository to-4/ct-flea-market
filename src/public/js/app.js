/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/babel-loader/lib/index.js):\nSyntaxError: /var/www/resources/js/app.js: Support for the experimental syntax 'jsx' isn't currently enabled (7:12):\n\n\u001b[0m \u001b[90m  5 |\u001b[39m\n \u001b[90m  6 |\u001b[39m \u001b[36mfunction\u001b[39m \u001b[33mApp\u001b[39m() {\n\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m  7 |\u001b[39m     \u001b[36mreturn\u001b[39m \u001b[33m<\u001b[39m\u001b[33mh1\u001b[39m\u001b[33m>\u001b[39m\u001b[33mHello\u001b[39m \u001b[33mReact\u001b[39m \u001b[33m+\u001b[39m \u001b[33mLaravel\u001b[39m \u001b[35m12\u001b[39m\u001b[33m!\u001b[39m\u001b[33m<\u001b[39m\u001b[33m/\u001b[39m\u001b[33mh1\u001b[39m\u001b[33m>\u001b[39m\u001b[33m;\u001b[39m\n \u001b[90m    |\u001b[39m            \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\n \u001b[90m  8 |\u001b[39m }\n \u001b[90m  9 |\u001b[39m\n \u001b[90m 10 |\u001b[39m \u001b[33mReactDOM\u001b[39m\u001b[33m.\u001b[39mcreateRoot(document\u001b[33m.\u001b[39mgetElementById(\u001b[32m'app'\u001b[39m))\u001b[33m.\u001b[39mrender(\u001b[33m<\u001b[39m\u001b[33mApp\u001b[39m \u001b[33m/\u001b[39m\u001b[33m>\u001b[39m)\u001b[33m;\u001b[39m\u001b[0m\n\nAdd @babel/preset-react (https://github.com/babel/babel/tree/main/packages/babel-preset-react) to the 'presets' section of your Babel config to enable transformation.\nIf you want to leave it as-is, add @babel/plugin-syntax-jsx (https://github.com/babel/babel/tree/main/packages/babel-plugin-syntax-jsx) to the 'plugins' section to enable parsing.\n\nIf you already added the plugin for this syntax to your config, it's possible that your config isn't being loaded.\nYou can re-run Babel with the BABEL_SHOW_CONFIG_FOR environment variable to show the loaded configuration:\n\tnpx cross-env BABEL_SHOW_CONFIG_FOR=/var/www/resources/js/app.js <your build command>\nSee https://babeljs.io/docs/configuration#print-effective-configs for more info.\n\n    at constructor (/var/www/node_modules/@babel/parser/lib/index.js:367:19)\n    at Parser.raise (/var/www/node_modules/@babel/parser/lib/index.js:6630:19)\n    at Parser.expectOnePlugin (/var/www/node_modules/@babel/parser/lib/index.js:6664:18)\n    at Parser.parseExprAtom (/var/www/node_modules/@babel/parser/lib/index.js:11403:18)\n    at Parser.parseExprSubscripts (/var/www/node_modules/@babel/parser/lib/index.js:11085:23)\n    at Parser.parseUpdate (/var/www/node_modules/@babel/parser/lib/index.js:11070:21)\n    at Parser.parseMaybeUnary (/var/www/node_modules/@babel/parser/lib/index.js:11050:23)\n    at Parser.parseMaybeUnaryOrPrivate (/var/www/node_modules/@babel/parser/lib/index.js:10903:61)\n    at Parser.parseExprOps (/var/www/node_modules/@babel/parser/lib/index.js:10908:23)\n    at Parser.parseMaybeConditional (/var/www/node_modules/@babel/parser/lib/index.js:10885:23)\n    at Parser.parseMaybeAssign (/var/www/node_modules/@babel/parser/lib/index.js:10835:21)\n    at Parser.parseExpressionBase (/var/www/node_modules/@babel/parser/lib/index.js:10788:23)\n    at /var/www/node_modules/@babel/parser/lib/index.js:10784:39\n    at Parser.allowInAnd (/var/www/node_modules/@babel/parser/lib/index.js:12431:16)\n    at Parser.parseExpression (/var/www/node_modules/@babel/parser/lib/index.js:10784:17)\n    at Parser.parseReturnStatement (/var/www/node_modules/@babel/parser/lib/index.js:13151:28)\n    at Parser.parseStatementContent (/var/www/node_modules/@babel/parser/lib/index.js:12807:21)\n    at Parser.parseStatementLike (/var/www/node_modules/@babel/parser/lib/index.js:12776:17)\n    at Parser.parseStatementListItem (/var/www/node_modules/@babel/parser/lib/index.js:12756:17)\n    at Parser.parseBlockOrModuleBlockBody (/var/www/node_modules/@babel/parser/lib/index.js:13325:61)\n    at Parser.parseBlockBody (/var/www/node_modules/@babel/parser/lib/index.js:13318:10)\n    at Parser.parseBlock (/var/www/node_modules/@babel/parser/lib/index.js:13306:10)\n    at Parser.parseFunctionBody (/var/www/node_modules/@babel/parser/lib/index.js:12110:24)\n    at Parser.parseFunctionBodyAndFinish (/var/www/node_modules/@babel/parser/lib/index.js:12096:10)\n    at /var/www/node_modules/@babel/parser/lib/index.js:13454:12\n    at Parser.withSmartMixTopicForbiddingContext (/var/www/node_modules/@babel/parser/lib/index.js:12413:14)\n    at Parser.parseFunction (/var/www/node_modules/@babel/parser/lib/index.js:13453:10)\n    at Parser.parseFunctionStatement (/var/www/node_modules/@babel/parser/lib/index.js:13134:17)\n    at Parser.parseStatementContent (/var/www/node_modules/@babel/parser/lib/index.js:12800:21)\n    at Parser.parseStatementLike (/var/www/node_modules/@babel/parser/lib/index.js:12776:17)\n    at Parser.parseModuleItem (/var/www/node_modules/@babel/parser/lib/index.js:12753:17)\n    at Parser.parseBlockOrModuleBlockBody (/var/www/node_modules/@babel/parser/lib/index.js:13325:36)\n    at Parser.parseBlockBody (/var/www/node_modules/@babel/parser/lib/index.js:13318:10)\n    at Parser.parseProgram (/var/www/node_modules/@babel/parser/lib/index.js:12634:10)\n    at Parser.parseTopLevel (/var/www/node_modules/@babel/parser/lib/index.js:12624:25)\n    at Parser.parse (/var/www/node_modules/@babel/parser/lib/index.js:14501:10)\n    at parse (/var/www/node_modules/@babel/parser/lib/index.js:14535:38)\n    at parser (/var/www/node_modules/@babel/core/lib/parser/index.js:41:34)\n    at parser.next (<anonymous>)\n    at normalizeFile (/var/www/node_modules/@babel/core/lib/transformation/normalize-file.js:64:37)\n    at normalizeFile.next (<anonymous>)\n    at run (/var/www/node_modules/@babel/core/lib/transformation/index.js:22:50)\n    at run.next (<anonymous>)\n    at transform (/var/www/node_modules/@babel/core/lib/transform.js:22:33)\n    at transform.next (<anonymous>)\n    at step (/var/www/node_modules/gensync/index.js:261:32)\n    at /var/www/node_modules/gensync/index.js:273:13\n    at async.call.result.err.err (/var/www/node_modules/gensync/index.js:223:11)\n    at /var/www/node_modules/gensync/index.js:189:28\n    at /var/www/node_modules/@babel/core/lib/gensync-utils/async.js:67:7");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/app": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/js/app.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/css/app.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;