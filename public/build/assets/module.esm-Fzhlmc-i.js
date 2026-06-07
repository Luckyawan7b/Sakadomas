function ei(e,t){return function(){return e.apply(t,arguments)}}const{toString:So}=Object.prototype,{getPrototypeOf:vn}=Object,{iterator:ft,toStringTag:ti}=Symbol,dt=(e=>t=>{const n=So.call(t);return e[n]||(e[n]=n.slice(8,-1).toLowerCase())})(Object.create(null)),F=e=>(e=e.toLowerCase(),t=>dt(t)===e),pt=e=>t=>typeof t===e,{isArray:we}=Array,Me=pt("undefined");function Le(e){return e!==null&&!Me(e)&&e.constructor!==null&&!Me(e.constructor)&&N(e.constructor.isBuffer)&&e.constructor.isBuffer(e)}const ni=F("ArrayBuffer");function Ao(e){let t;return typeof ArrayBuffer<"u"&&ArrayBuffer.isView?t=ArrayBuffer.isView(e):t=e&&e.buffer&&ni(e.buffer),t}const xo=pt("string"),N=pt("function"),ri=pt("number"),je=e=>e!==null&&typeof e=="object",vo=e=>e===!0||e===!1,Ze=e=>{if(dt(e)!=="object")return!1;const t=vn(e);return(t===null||t===Object.prototype||Object.getPrototypeOf(t)===null)&&!(ti in e)&&!(ft in e)},To=e=>{if(!je(e)||Le(e))return!1;try{return Object.keys(e).length===0&&Object.getPrototypeOf(e)===Object.prototype}catch{return!1}},Co=F("Date"),Oo=F("File"),Io=F("Blob"),Ro=F("FileList"),Do=e=>je(e)&&N(e.pipe),ko=e=>{let t;return e&&(typeof FormData=="function"&&e instanceof FormData||N(e.append)&&((t=dt(e))==="formdata"||t==="object"&&N(e.toString)&&e.toString()==="[object FormData]"))},No=F("URLSearchParams"),[Po,Mo,Bo,Fo]=["ReadableStream","Request","Response","Headers"].map(F),$o=e=>e.trim?e.trim():e.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,"");function He(e,t,{allOwnKeys:n=!1}={}){if(e===null||typeof e>"u")return;let r,i;if(typeof e!="object"&&(e=[e]),we(e))for(r=0,i=e.length;r<i;r++)t.call(null,e[r],r,e);else{if(Le(e))return;const s=n?Object.getOwnPropertyNames(e):Object.keys(e),o=s.length;let a;for(r=0;r<o;r++)a=s[r],t.call(null,e[a],a,e)}}function ii(e,t){if(Le(e))return null;t=t.toLowerCase();const n=Object.keys(e);let r=n.length,i;for(;r-- >0;)if(i=n[r],t===i.toLowerCase())return i;return null}const ne=typeof globalThis<"u"?globalThis:typeof self<"u"?self:typeof window<"u"?window:global,si=e=>!Me(e)&&e!==ne;function Jt(){const{caseless:e}=si(this)&&this||{},t={},n=(r,i)=>{const s=e&&ii(t,i)||i;Ze(t[s])&&Ze(r)?t[s]=Jt(t[s],r):Ze(r)?t[s]=Jt({},r):we(r)?t[s]=r.slice():t[s]=r};for(let r=0,i=arguments.length;r<i;r++)arguments[r]&&He(arguments[r],n);return t}const Lo=(e,t,n,{allOwnKeys:r}={})=>(He(t,(i,s)=>{n&&N(i)?e[s]=ei(i,n):e[s]=i},{allOwnKeys:r}),e),jo=e=>(e.charCodeAt(0)===65279&&(e=e.slice(1)),e),Ho=(e,t,n,r)=>{e.prototype=Object.create(t.prototype,r),e.prototype.constructor=e,Object.defineProperty(e,"super",{value:t.prototype}),n&&Object.assign(e.prototype,n)},Uo=(e,t,n,r)=>{let i,s,o;const a={};if(t=t||{},e==null)return t;do{for(i=Object.getOwnPropertyNames(e),s=i.length;s-- >0;)o=i[s],(!r||r(o,e,t))&&!a[o]&&(t[o]=e[o],a[o]=!0);e=n!==!1&&vn(e)}while(e&&(!n||n(e,t))&&e!==Object.prototype);return t},qo=(e,t,n)=>{e=String(e),(n===void 0||n>e.length)&&(n=e.length),n-=t.length;const r=e.indexOf(t,n);return r!==-1&&r===n},Ko=e=>{if(!e)return null;if(we(e))return e;let t=e.length;if(!ri(t))return null;const n=new Array(t);for(;t-- >0;)n[t]=e[t];return n},Vo=(e=>t=>e&&t instanceof e)(typeof Uint8Array<"u"&&vn(Uint8Array)),zo=(e,t)=>{const r=(e&&e[ft]).call(e);let i;for(;(i=r.next())&&!i.done;){const s=i.value;t.call(e,s[0],s[1])}},Wo=(e,t)=>{let n;const r=[];for(;(n=e.exec(t))!==null;)r.push(n);return r},Jo=F("HTMLFormElement"),Go=e=>e.toLowerCase().replace(/[-_\s]([a-z\d])(\w*)/g,function(n,r,i){return r.toUpperCase()+i}),or=(({hasOwnProperty:e})=>(t,n)=>e.call(t,n))(Object.prototype),Xo=F("RegExp"),oi=(e,t)=>{const n=Object.getOwnPropertyDescriptors(e),r={};He(n,(i,s)=>{let o;(o=t(i,s,e))!==!1&&(r[s]=o||i)}),Object.defineProperties(e,r)},Yo=e=>{oi(e,(t,n)=>{if(N(e)&&["arguments","caller","callee"].indexOf(n)!==-1)return!1;const r=e[n];if(N(r)){if(t.enumerable=!1,"writable"in t){t.writable=!1;return}t.set||(t.set=()=>{throw Error("Can not rewrite read-only method '"+n+"'")})}})},Zo=(e,t)=>{const n={},r=i=>{i.forEach(s=>{n[s]=!0})};return we(e)?r(e):r(String(e).split(t)),n},Qo=()=>{},ea=(e,t)=>e!=null&&Number.isFinite(e=+e)?e:t;function ta(e){return!!(e&&N(e.append)&&e[ti]==="FormData"&&e[ft])}const na=e=>{const t=new Array(10),n=(r,i)=>{if(je(r)){if(t.indexOf(r)>=0)return;if(Le(r))return r;if(!("toJSON"in r)){t[i]=r;const s=we(r)?[]:{};return He(r,(o,a)=>{const c=n(o,i+1);!Me(c)&&(s[a]=c)}),t[i]=void 0,s}}return r};return n(e,0)},ra=F("AsyncFunction"),ia=e=>e&&(je(e)||N(e))&&N(e.then)&&N(e.catch),ai=((e,t)=>e?setImmediate:t?((n,r)=>(ne.addEventListener("message",({source:i,data:s})=>{i===ne&&s===n&&r.length&&r.shift()()},!1),i=>{r.push(i),ne.postMessage(n,"*")}))(`axios@${Math.random()}`,[]):n=>setTimeout(n))(typeof setImmediate=="function",N(ne.postMessage)),sa=typeof queueMicrotask<"u"?queueMicrotask.bind(ne):typeof process<"u"&&process.nextTick||ai,oa=e=>e!=null&&N(e[ft]),f={isArray:we,isArrayBuffer:ni,isBuffer:Le,isFormData:ko,isArrayBufferView:Ao,isString:xo,isNumber:ri,isBoolean:vo,isObject:je,isPlainObject:Ze,isEmptyObject:To,isReadableStream:Po,isRequest:Mo,isResponse:Bo,isHeaders:Fo,isUndefined:Me,isDate:Co,isFile:Oo,isBlob:Io,isRegExp:Xo,isFunction:N,isStream:Do,isURLSearchParams:No,isTypedArray:Vo,isFileList:Ro,forEach:He,merge:Jt,extend:Lo,trim:$o,stripBOM:jo,inherits:Ho,toFlatObject:Uo,kindOf:dt,kindOfTest:F,endsWith:qo,toArray:Ko,forEachEntry:zo,matchAll:Wo,isHTMLForm:Jo,hasOwnProperty:or,hasOwnProp:or,reduceDescriptors:oi,freezeMethods:Yo,toObjectSet:Zo,toCamelCase:Go,noop:Qo,toFiniteNumber:ea,findKey:ii,global:ne,isContextDefined:si,isSpecCompliantForm:ta,toJSONObject:na,isAsyncFn:ra,isThenable:ia,setImmediate:ai,asap:sa,isIterable:oa};function y(e,t,n,r,i){Error.call(this),Error.captureStackTrace?Error.captureStackTrace(this,this.constructor):this.stack=new Error().stack,this.message=e,this.name="AxiosError",t&&(this.code=t),n&&(this.config=n),r&&(this.request=r),i&&(this.response=i,this.status=i.status?i.status:null)}f.inherits(y,Error,{toJSON:function(){return{message:this.message,name:this.name,description:this.description,number:this.number,fileName:this.fileName,lineNumber:this.lineNumber,columnNumber:this.columnNumber,stack:this.stack,config:f.toJSONObject(this.config),code:this.code,status:this.status}}});const ci=y.prototype,ui={};["ERR_BAD_OPTION_VALUE","ERR_BAD_OPTION","ECONNABORTED","ETIMEDOUT","ERR_NETWORK","ERR_FR_TOO_MANY_REDIRECTS","ERR_DEPRECATED","ERR_BAD_RESPONSE","ERR_BAD_REQUEST","ERR_CANCELED","ERR_NOT_SUPPORT","ERR_INVALID_URL"].forEach(e=>{ui[e]={value:e}});Object.defineProperties(y,ui);Object.defineProperty(ci,"isAxiosError",{value:!0});y.from=(e,t,n,r,i,s)=>{const o=Object.create(ci);return f.toFlatObject(e,o,function(c){return c!==Error.prototype},a=>a!=="isAxiosError"),y.call(o,e.message,t,n,r,i),o.cause=e,o.name=e.name,s&&Object.assign(o,s),o};const aa=null;function Gt(e){return f.isPlainObject(e)||f.isArray(e)}function li(e){return f.endsWith(e,"[]")?e.slice(0,-2):e}function ar(e,t,n){return e?e.concat(t).map(function(i,s){return i=li(i),!n&&s?"["+i+"]":i}).join(n?".":""):t}function ca(e){return f.isArray(e)&&!e.some(Gt)}const ua=f.toFlatObject(f,{},null,function(t){return/^is[A-Z]/.test(t)});function ht(e,t,n){if(!f.isObject(e))throw new TypeError("target must be an object");t=t||new FormData,n=f.toFlatObject(n,{metaTokens:!0,dots:!1,indexes:!1},!1,function(b,p){return!f.isUndefined(p[b])});const r=n.metaTokens,i=n.visitor||l,s=n.dots,o=n.indexes,c=(n.Blob||typeof Blob<"u"&&Blob)&&f.isSpecCompliantForm(t);if(!f.isFunction(i))throw new TypeError("visitor must be a function");function u(g){if(g===null)return"";if(f.isDate(g))return g.toISOString();if(f.isBoolean(g))return g.toString();if(!c&&f.isBlob(g))throw new y("Blob is not supported. Use a Buffer instead.");return f.isArrayBuffer(g)||f.isTypedArray(g)?c&&typeof Blob=="function"?new Blob([g]):Buffer.from(g):g}function l(g,b,p){let m=g;if(g&&!p&&typeof g=="object"){if(f.endsWith(b,"{}"))b=r?b:b.slice(0,-2),g=JSON.stringify(g);else if(f.isArray(g)&&ca(g)||(f.isFileList(g)||f.endsWith(b,"[]"))&&(m=f.toArray(g)))return b=li(b),m.forEach(function(E,T){!(f.isUndefined(E)||E===null)&&t.append(o===!0?ar([b],T,s):o===null?b:b+"[]",u(E))}),!1}return Gt(g)?!0:(t.append(ar(p,b,s),u(g)),!1)}const d=[],h=Object.assign(ua,{defaultVisitor:l,convertValue:u,isVisitable:Gt});function _(g,b){if(!f.isUndefined(g)){if(d.indexOf(g)!==-1)throw Error("Circular reference detected in "+b.join("."));d.push(g),f.forEach(g,function(m,w){(!(f.isUndefined(m)||m===null)&&i.call(t,m,f.isString(w)?w.trim():w,b,h))===!0&&_(m,b?b.concat(w):[w])}),d.pop()}}if(!f.isObject(e))throw new TypeError("data must be an object");return _(e),t}function cr(e){const t={"!":"%21","'":"%27","(":"%28",")":"%29","~":"%7E","%20":"+","%00":"\0"};return encodeURIComponent(e).replace(/[!'()~]|%20|%00/g,function(r){return t[r]})}function Tn(e,t){this._pairs=[],e&&ht(e,this,t)}const fi=Tn.prototype;fi.append=function(t,n){this._pairs.push([t,n])};fi.toString=function(t){const n=t?function(r){return t.call(this,r,cr)}:cr;return this._pairs.map(function(i){return n(i[0])+"="+n(i[1])},"").join("&")};function la(e){return encodeURIComponent(e).replace(/%3A/gi,":").replace(/%24/g,"$").replace(/%2C/gi,",").replace(/%20/g,"+").replace(/%5B/gi,"[").replace(/%5D/gi,"]")}function di(e,t,n){if(!t)return e;const r=n&&n.encode||la;f.isFunction(n)&&(n={serialize:n});const i=n&&n.serialize;let s;if(i?s=i(t,n):s=f.isURLSearchParams(t)?t.toString():new Tn(t,n).toString(r),s){const o=e.indexOf("#");o!==-1&&(e=e.slice(0,o)),e+=(e.indexOf("?")===-1?"?":"&")+s}return e}class ur{constructor(){this.handlers=[]}use(t,n,r){return this.handlers.push({fulfilled:t,rejected:n,synchronous:r?r.synchronous:!1,runWhen:r?r.runWhen:null}),this.handlers.length-1}eject(t){this.handlers[t]&&(this.handlers[t]=null)}clear(){this.handlers&&(this.handlers=[])}forEach(t){f.forEach(this.handlers,function(r){r!==null&&t(r)})}}const pi={silentJSONParsing:!0,forcedJSONParsing:!0,clarifyTimeoutError:!1},fa=typeof URLSearchParams<"u"?URLSearchParams:Tn,da=typeof FormData<"u"?FormData:null,pa=typeof Blob<"u"?Blob:null,ha={isBrowser:!0,classes:{URLSearchParams:fa,FormData:da,Blob:pa},protocols:["http","https","file","blob","url","data"]},Cn=typeof window<"u"&&typeof document<"u",Xt=typeof navigator=="object"&&navigator||void 0,ga=Cn&&(!Xt||["ReactNative","NativeScript","NS"].indexOf(Xt.product)<0),ma=typeof WorkerGlobalScope<"u"&&self instanceof WorkerGlobalScope&&typeof self.importScripts=="function",ba=Cn&&window.location.href||"http://localhost",_a=Object.freeze(Object.defineProperty({__proto__:null,hasBrowserEnv:Cn,hasStandardBrowserEnv:ga,hasStandardBrowserWebWorkerEnv:ma,navigator:Xt,origin:ba},Symbol.toStringTag,{value:"Module"})),R={..._a,...ha};function ya(e,t){return ht(e,new R.classes.URLSearchParams,{visitor:function(n,r,i,s){return R.isNode&&f.isBuffer(n)?(this.append(r,n.toString("base64")),!1):s.defaultVisitor.apply(this,arguments)},...t})}function wa(e){return f.matchAll(/\w+|\[(\w*)]/g,e).map(t=>t[0]==="[]"?"":t[1]||t[0])}function Ea(e){const t={},n=Object.keys(e);let r;const i=n.length;let s;for(r=0;r<i;r++)s=n[r],t[s]=e[s];return t}function hi(e){function t(n,r,i,s){let o=n[s++];if(o==="__proto__")return!0;const a=Number.isFinite(+o),c=s>=n.length;return o=!o&&f.isArray(i)?i.length:o,c?(f.hasOwnProp(i,o)?i[o]=[i[o],r]:i[o]=r,!a):((!i[o]||!f.isObject(i[o]))&&(i[o]=[]),t(n,r,i[o],s)&&f.isArray(i[o])&&(i[o]=Ea(i[o])),!a)}if(f.isFormData(e)&&f.isFunction(e.entries)){const n={};return f.forEachEntry(e,(r,i)=>{t(wa(r),i,n,0)}),n}return null}function Sa(e,t,n){if(f.isString(e))try{return(t||JSON.parse)(e),f.trim(e)}catch(r){if(r.name!=="SyntaxError")throw r}return(n||JSON.stringify)(e)}const Ue={transitional:pi,adapter:["xhr","http","fetch"],transformRequest:[function(t,n){const r=n.getContentType()||"",i=r.indexOf("application/json")>-1,s=f.isObject(t);if(s&&f.isHTMLForm(t)&&(t=new FormData(t)),f.isFormData(t))return i?JSON.stringify(hi(t)):t;if(f.isArrayBuffer(t)||f.isBuffer(t)||f.isStream(t)||f.isFile(t)||f.isBlob(t)||f.isReadableStream(t))return t;if(f.isArrayBufferView(t))return t.buffer;if(f.isURLSearchParams(t))return n.setContentType("application/x-www-form-urlencoded;charset=utf-8",!1),t.toString();let a;if(s){if(r.indexOf("application/x-www-form-urlencoded")>-1)return ya(t,this.formSerializer).toString();if((a=f.isFileList(t))||r.indexOf("multipart/form-data")>-1){const c=this.env&&this.env.FormData;return ht(a?{"files[]":t}:t,c&&new c,this.formSerializer)}}return s||i?(n.setContentType("application/json",!1),Sa(t)):t}],transformResponse:[function(t){const n=this.transitional||Ue.transitional,r=n&&n.forcedJSONParsing,i=this.responseType==="json";if(f.isResponse(t)||f.isReadableStream(t))return t;if(t&&f.isString(t)&&(r&&!this.responseType||i)){const o=!(n&&n.silentJSONParsing)&&i;try{return JSON.parse(t)}catch(a){if(o)throw a.name==="SyntaxError"?y.from(a,y.ERR_BAD_RESPONSE,this,null,this.response):a}}return t}],timeout:0,xsrfCookieName:"XSRF-TOKEN",xsrfHeaderName:"X-XSRF-TOKEN",maxContentLength:-1,maxBodyLength:-1,env:{FormData:R.classes.FormData,Blob:R.classes.Blob},validateStatus:function(t){return t>=200&&t<300},headers:{common:{Accept:"application/json, text/plain, */*","Content-Type":void 0}}};f.forEach(["delete","get","head","post","put","patch"],e=>{Ue.headers[e]={}});const Aa=f.toObjectSet(["age","authorization","content-length","content-type","etag","expires","from","host","if-modified-since","if-unmodified-since","last-modified","location","max-forwards","proxy-authorization","referer","retry-after","user-agent"]),xa=e=>{const t={};let n,r,i;return e&&e.split(`
`).forEach(function(o){i=o.indexOf(":"),n=o.substring(0,i).trim().toLowerCase(),r=o.substring(i+1).trim(),!(!n||t[n]&&Aa[n])&&(n==="set-cookie"?t[n]?t[n].push(r):t[n]=[r]:t[n]=t[n]?t[n]+", "+r:r)}),t},lr=Symbol("internals");function Oe(e){return e&&String(e).trim().toLowerCase()}function Qe(e){return e===!1||e==null?e:f.isArray(e)?e.map(Qe):String(e)}function va(e){const t=Object.create(null),n=/([^\s,;=]+)\s*(?:=\s*([^,;]+))?/g;let r;for(;r=n.exec(e);)t[r[1]]=r[2];return t}const Ta=e=>/^[-_a-zA-Z0-9^`|~,!#$%&'*+.]+$/.test(e.trim());function It(e,t,n,r,i){if(f.isFunction(r))return r.call(this,t,n);if(i&&(t=n),!!f.isString(t)){if(f.isString(r))return t.indexOf(r)!==-1;if(f.isRegExp(r))return r.test(t)}}function Ca(e){return e.trim().toLowerCase().replace(/([a-z\d])(\w*)/g,(t,n,r)=>n.toUpperCase()+r)}function Oa(e,t){const n=f.toCamelCase(" "+t);["get","set","has"].forEach(r=>{Object.defineProperty(e,r+n,{value:function(i,s,o){return this[r].call(this,t,i,s,o)},configurable:!0})})}let P=class{constructor(t){t&&this.set(t)}set(t,n,r){const i=this;function s(a,c,u){const l=Oe(c);if(!l)throw new Error("header name must be a non-empty string");const d=f.findKey(i,l);(!d||i[d]===void 0||u===!0||u===void 0&&i[d]!==!1)&&(i[d||c]=Qe(a))}const o=(a,c)=>f.forEach(a,(u,l)=>s(u,l,c));if(f.isPlainObject(t)||t instanceof this.constructor)o(t,n);else if(f.isString(t)&&(t=t.trim())&&!Ta(t))o(xa(t),n);else if(f.isObject(t)&&f.isIterable(t)){let a={},c,u;for(const l of t){if(!f.isArray(l))throw TypeError("Object iterator must return a key-value pair");a[u=l[0]]=(c=a[u])?f.isArray(c)?[...c,l[1]]:[c,l[1]]:l[1]}o(a,n)}else t!=null&&s(n,t,r);return this}get(t,n){if(t=Oe(t),t){const r=f.findKey(this,t);if(r){const i=this[r];if(!n)return i;if(n===!0)return va(i);if(f.isFunction(n))return n.call(this,i,r);if(f.isRegExp(n))return n.exec(i);throw new TypeError("parser must be boolean|regexp|function")}}}has(t,n){if(t=Oe(t),t){const r=f.findKey(this,t);return!!(r&&this[r]!==void 0&&(!n||It(this,this[r],r,n)))}return!1}delete(t,n){const r=this;let i=!1;function s(o){if(o=Oe(o),o){const a=f.findKey(r,o);a&&(!n||It(r,r[a],a,n))&&(delete r[a],i=!0)}}return f.isArray(t)?t.forEach(s):s(t),i}clear(t){const n=Object.keys(this);let r=n.length,i=!1;for(;r--;){const s=n[r];(!t||It(this,this[s],s,t,!0))&&(delete this[s],i=!0)}return i}normalize(t){const n=this,r={};return f.forEach(this,(i,s)=>{const o=f.findKey(r,s);if(o){n[o]=Qe(i),delete n[s];return}const a=t?Ca(s):String(s).trim();a!==s&&delete n[s],n[a]=Qe(i),r[a]=!0}),this}concat(...t){return this.constructor.concat(this,...t)}toJSON(t){const n=Object.create(null);return f.forEach(this,(r,i)=>{r!=null&&r!==!1&&(n[i]=t&&f.isArray(r)?r.join(", "):r)}),n}[Symbol.iterator](){return Object.entries(this.toJSON())[Symbol.iterator]()}toString(){return Object.entries(this.toJSON()).map(([t,n])=>t+": "+n).join(`
`)}getSetCookie(){return this.get("set-cookie")||[]}get[Symbol.toStringTag](){return"AxiosHeaders"}static from(t){return t instanceof this?t:new this(t)}static concat(t,...n){const r=new this(t);return n.forEach(i=>r.set(i)),r}static accessor(t){const r=(this[lr]=this[lr]={accessors:{}}).accessors,i=this.prototype;function s(o){const a=Oe(o);r[a]||(Oa(i,o),r[a]=!0)}return f.isArray(t)?t.forEach(s):s(t),this}};P.accessor(["Content-Type","Content-Length","Accept","Accept-Encoding","User-Agent","Authorization"]);f.reduceDescriptors(P.prototype,({value:e},t)=>{let n=t[0].toUpperCase()+t.slice(1);return{get:()=>e,set(r){this[n]=r}}});f.freezeMethods(P);function Rt(e,t){const n=this||Ue,r=t||n,i=P.from(r.headers);let s=r.data;return f.forEach(e,function(a){s=a.call(n,s,i.normalize(),t?t.status:void 0)}),i.normalize(),s}function gi(e){return!!(e&&e.__CANCEL__)}function Ee(e,t,n){y.call(this,e??"canceled",y.ERR_CANCELED,t,n),this.name="CanceledError"}f.inherits(Ee,y,{__CANCEL__:!0});function mi(e,t,n){const r=n.config.validateStatus;!n.status||!r||r(n.status)?e(n):t(new y("Request failed with status code "+n.status,[y.ERR_BAD_REQUEST,y.ERR_BAD_RESPONSE][Math.floor(n.status/100)-4],n.config,n.request,n))}function Ia(e){const t=/^([-+\w]{1,25})(:?\/\/|:)/.exec(e);return t&&t[1]||""}function Ra(e,t){e=e||10;const n=new Array(e),r=new Array(e);let i=0,s=0,o;return t=t!==void 0?t:1e3,function(c){const u=Date.now(),l=r[s];o||(o=u),n[i]=c,r[i]=u;let d=s,h=0;for(;d!==i;)h+=n[d++],d=d%e;if(i=(i+1)%e,i===s&&(s=(s+1)%e),u-o<t)return;const _=l&&u-l;return _?Math.round(h*1e3/_):void 0}}function Da(e,t){let n=0,r=1e3/t,i,s;const o=(u,l=Date.now())=>{n=l,i=null,s&&(clearTimeout(s),s=null),e(...u)};return[(...u)=>{const l=Date.now(),d=l-n;d>=r?o(u,l):(i=u,s||(s=setTimeout(()=>{s=null,o(i)},r-d)))},()=>i&&o(i)]}const rt=(e,t,n=3)=>{let r=0;const i=Ra(50,250);return Da(s=>{const o=s.loaded,a=s.lengthComputable?s.total:void 0,c=o-r,u=i(c),l=o<=a;r=o;const d={loaded:o,total:a,progress:a?o/a:void 0,bytes:c,rate:u||void 0,estimated:u&&a&&l?(a-o)/u:void 0,event:s,lengthComputable:a!=null,[t?"download":"upload"]:!0};e(d)},n)},fr=(e,t)=>{const n=e!=null;return[r=>t[0]({lengthComputable:n,total:e,loaded:r}),t[1]]},dr=e=>(...t)=>f.asap(()=>e(...t)),ka=R.hasStandardBrowserEnv?((e,t)=>n=>(n=new URL(n,R.origin),e.protocol===n.protocol&&e.host===n.host&&(t||e.port===n.port)))(new URL(R.origin),R.navigator&&/(msie|trident)/i.test(R.navigator.userAgent)):()=>!0,Na=R.hasStandardBrowserEnv?{write(e,t,n,r,i,s){const o=[e+"="+encodeURIComponent(t)];f.isNumber(n)&&o.push("expires="+new Date(n).toGMTString()),f.isString(r)&&o.push("path="+r),f.isString(i)&&o.push("domain="+i),s===!0&&o.push("secure"),document.cookie=o.join("; ")},read(e){const t=document.cookie.match(new RegExp("(^|;\\s*)("+e+")=([^;]*)"));return t?decodeURIComponent(t[3]):null},remove(e){this.write(e,"",Date.now()-864e5)}}:{write(){},read(){return null},remove(){}};function Pa(e){return/^([a-z][a-z\d+\-.]*:)?\/\//i.test(e)}function Ma(e,t){return t?e.replace(/\/?\/$/,"")+"/"+t.replace(/^\/+/,""):e}function bi(e,t,n){let r=!Pa(t);return e&&(r||n==!1)?Ma(e,t):t}const pr=e=>e instanceof P?{...e}:e;function le(e,t){t=t||{};const n={};function r(u,l,d,h){return f.isPlainObject(u)&&f.isPlainObject(l)?f.merge.call({caseless:h},u,l):f.isPlainObject(l)?f.merge({},l):f.isArray(l)?l.slice():l}function i(u,l,d,h){if(f.isUndefined(l)){if(!f.isUndefined(u))return r(void 0,u,d,h)}else return r(u,l,d,h)}function s(u,l){if(!f.isUndefined(l))return r(void 0,l)}function o(u,l){if(f.isUndefined(l)){if(!f.isUndefined(u))return r(void 0,u)}else return r(void 0,l)}function a(u,l,d){if(d in t)return r(u,l);if(d in e)return r(void 0,u)}const c={url:s,method:s,data:s,baseURL:o,transformRequest:o,transformResponse:o,paramsSerializer:o,timeout:o,timeoutMessage:o,withCredentials:o,withXSRFToken:o,adapter:o,responseType:o,xsrfCookieName:o,xsrfHeaderName:o,onUploadProgress:o,onDownloadProgress:o,decompress:o,maxContentLength:o,maxBodyLength:o,beforeRedirect:o,transport:o,httpAgent:o,httpsAgent:o,cancelToken:o,socketPath:o,responseEncoding:o,validateStatus:a,headers:(u,l,d)=>i(pr(u),pr(l),d,!0)};return f.forEach(Object.keys({...e,...t}),function(l){const d=c[l]||i,h=d(e[l],t[l],l);f.isUndefined(h)&&d!==a||(n[l]=h)}),n}const _i=e=>{const t=le({},e);let{data:n,withXSRFToken:r,xsrfHeaderName:i,xsrfCookieName:s,headers:o,auth:a}=t;t.headers=o=P.from(o),t.url=di(bi(t.baseURL,t.url,t.allowAbsoluteUrls),e.params,e.paramsSerializer),a&&o.set("Authorization","Basic "+btoa((a.username||"")+":"+(a.password?unescape(encodeURIComponent(a.password)):"")));let c;if(f.isFormData(n)){if(R.hasStandardBrowserEnv||R.hasStandardBrowserWebWorkerEnv)o.setContentType(void 0);else if((c=o.getContentType())!==!1){const[u,...l]=c?c.split(";").map(d=>d.trim()).filter(Boolean):[];o.setContentType([u||"multipart/form-data",...l].join("; "))}}if(R.hasStandardBrowserEnv&&(r&&f.isFunction(r)&&(r=r(t)),r||r!==!1&&ka(t.url))){const u=i&&s&&Na.read(s);u&&o.set(i,u)}return t},Ba=typeof XMLHttpRequest<"u",Fa=Ba&&function(e){return new Promise(function(n,r){const i=_i(e);let s=i.data;const o=P.from(i.headers).normalize();let{responseType:a,onUploadProgress:c,onDownloadProgress:u}=i,l,d,h,_,g;function b(){_&&_(),g&&g(),i.cancelToken&&i.cancelToken.unsubscribe(l),i.signal&&i.signal.removeEventListener("abort",l)}let p=new XMLHttpRequest;p.open(i.method.toUpperCase(),i.url,!0),p.timeout=i.timeout;function m(){if(!p)return;const E=P.from("getAllResponseHeaders"in p&&p.getAllResponseHeaders()),C={data:!a||a==="text"||a==="json"?p.responseText:p.response,status:p.status,statusText:p.statusText,headers:E,config:e,request:p};mi(function(L){n(L),b()},function(L){r(L),b()},C),p=null}"onloadend"in p?p.onloadend=m:p.onreadystatechange=function(){!p||p.readyState!==4||p.status===0&&!(p.responseURL&&p.responseURL.indexOf("file:")===0)||setTimeout(m)},p.onabort=function(){p&&(r(new y("Request aborted",y.ECONNABORTED,e,p)),p=null)},p.onerror=function(){r(new y("Network Error",y.ERR_NETWORK,e,p)),p=null},p.ontimeout=function(){let T=i.timeout?"timeout of "+i.timeout+"ms exceeded":"timeout exceeded";const C=i.transitional||pi;i.timeoutErrorMessage&&(T=i.timeoutErrorMessage),r(new y(T,C.clarifyTimeoutError?y.ETIMEDOUT:y.ECONNABORTED,e,p)),p=null},s===void 0&&o.setContentType(null),"setRequestHeader"in p&&f.forEach(o.toJSON(),function(T,C){p.setRequestHeader(C,T)}),f.isUndefined(i.withCredentials)||(p.withCredentials=!!i.withCredentials),a&&a!=="json"&&(p.responseType=i.responseType),u&&([h,g]=rt(u,!0),p.addEventListener("progress",h)),c&&p.upload&&([d,_]=rt(c),p.upload.addEventListener("progress",d),p.upload.addEventListener("loadend",_)),(i.cancelToken||i.signal)&&(l=E=>{p&&(r(!E||E.type?new Ee(null,e,p):E),p.abort(),p=null)},i.cancelToken&&i.cancelToken.subscribe(l),i.signal&&(i.signal.aborted?l():i.signal.addEventListener("abort",l)));const w=Ia(i.url);if(w&&R.protocols.indexOf(w)===-1){r(new y("Unsupported protocol "+w+":",y.ERR_BAD_REQUEST,e));return}p.send(s||null)})},$a=(e,t)=>{const{length:n}=e=e?e.filter(Boolean):[];if(t||n){let r=new AbortController,i;const s=function(u){if(!i){i=!0,a();const l=u instanceof Error?u:this.reason;r.abort(l instanceof y?l:new Ee(l instanceof Error?l.message:l))}};let o=t&&setTimeout(()=>{o=null,s(new y(`timeout ${t} of ms exceeded`,y.ETIMEDOUT))},t);const a=()=>{e&&(o&&clearTimeout(o),o=null,e.forEach(u=>{u.unsubscribe?u.unsubscribe(s):u.removeEventListener("abort",s)}),e=null)};e.forEach(u=>u.addEventListener("abort",s));const{signal:c}=r;return c.unsubscribe=()=>f.asap(a),c}},La=function*(e,t){let n=e.byteLength;if(n<t){yield e;return}let r=0,i;for(;r<n;)i=r+t,yield e.slice(r,i),r=i},ja=async function*(e,t){for await(const n of Ha(e))yield*La(n,t)},Ha=async function*(e){if(e[Symbol.asyncIterator]){yield*e;return}const t=e.getReader();try{for(;;){const{done:n,value:r}=await t.read();if(n)break;yield r}}finally{await t.cancel()}},hr=(e,t,n,r)=>{const i=ja(e,t);let s=0,o,a=c=>{o||(o=!0,r&&r(c))};return new ReadableStream({async pull(c){try{const{done:u,value:l}=await i.next();if(u){a(),c.close();return}let d=l.byteLength;if(n){let h=s+=d;n(h)}c.enqueue(new Uint8Array(l))}catch(u){throw a(u),u}},cancel(c){return a(c),i.return()}},{highWaterMark:2})},gt=typeof fetch=="function"&&typeof Request=="function"&&typeof Response=="function",yi=gt&&typeof ReadableStream=="function",Ua=gt&&(typeof TextEncoder=="function"?(e=>t=>e.encode(t))(new TextEncoder):async e=>new Uint8Array(await new Response(e).arrayBuffer())),wi=(e,...t)=>{try{return!!e(...t)}catch{return!1}},qa=yi&&wi(()=>{let e=!1;const t=new Request(R.origin,{body:new ReadableStream,method:"POST",get duplex(){return e=!0,"half"}}).headers.has("Content-Type");return e&&!t}),gr=64*1024,Yt=yi&&wi(()=>f.isReadableStream(new Response("").body)),it={stream:Yt&&(e=>e.body)};gt&&(e=>{["text","arrayBuffer","blob","formData","stream"].forEach(t=>{!it[t]&&(it[t]=f.isFunction(e[t])?n=>n[t]():(n,r)=>{throw new y(`Response type '${t}' is not supported`,y.ERR_NOT_SUPPORT,r)})})})(new Response);const Ka=async e=>{if(e==null)return 0;if(f.isBlob(e))return e.size;if(f.isSpecCompliantForm(e))return(await new Request(R.origin,{method:"POST",body:e}).arrayBuffer()).byteLength;if(f.isArrayBufferView(e)||f.isArrayBuffer(e))return e.byteLength;if(f.isURLSearchParams(e)&&(e=e+""),f.isString(e))return(await Ua(e)).byteLength},Va=async(e,t)=>{const n=f.toFiniteNumber(e.getContentLength());return n??Ka(t)},za=gt&&(async e=>{let{url:t,method:n,data:r,signal:i,cancelToken:s,timeout:o,onDownloadProgress:a,onUploadProgress:c,responseType:u,headers:l,withCredentials:d="same-origin",fetchOptions:h}=_i(e);u=u?(u+"").toLowerCase():"text";let _=$a([i,s&&s.toAbortSignal()],o),g;const b=_&&_.unsubscribe&&(()=>{_.unsubscribe()});let p;try{if(c&&qa&&n!=="get"&&n!=="head"&&(p=await Va(l,r))!==0){let C=new Request(t,{method:"POST",body:r,duplex:"half"}),k;if(f.isFormData(r)&&(k=C.headers.get("content-type"))&&l.setContentType(k),C.body){const[L,be]=fr(p,rt(dr(c)));r=hr(C.body,gr,L,be)}}f.isString(d)||(d=d?"include":"omit");const m="credentials"in Request.prototype;g=new Request(t,{...h,signal:_,method:n.toUpperCase(),headers:l.normalize().toJSON(),body:r,duplex:"half",credentials:m?d:void 0});let w=await fetch(g,h);const E=Yt&&(u==="stream"||u==="response");if(Yt&&(a||E&&b)){const C={};["status","statusText","headers"].forEach(ze=>{C[ze]=w[ze]});const k=f.toFiniteNumber(w.headers.get("content-length")),[L,be]=a&&fr(k,rt(dr(a),!0))||[];w=new Response(hr(w.body,gr,L,()=>{be&&be(),b&&b()}),C)}u=u||"text";let T=await it[f.findKey(it,u)||"text"](w,e);return!E&&b&&b(),await new Promise((C,k)=>{mi(C,k,{data:T,headers:P.from(w.headers),status:w.status,statusText:w.statusText,config:e,request:g})})}catch(m){throw b&&b(),m&&m.name==="TypeError"&&/Load failed|fetch/i.test(m.message)?Object.assign(new y("Network Error",y.ERR_NETWORK,e,g),{cause:m.cause||m}):y.from(m,m&&m.code,e,g)}}),Zt={http:aa,xhr:Fa,fetch:za};f.forEach(Zt,(e,t)=>{if(e){try{Object.defineProperty(e,"name",{value:t})}catch{}Object.defineProperty(e,"adapterName",{value:t})}});const mr=e=>`- ${e}`,Wa=e=>f.isFunction(e)||e===null||e===!1,Ei={getAdapter:e=>{e=f.isArray(e)?e:[e];const{length:t}=e;let n,r;const i={};for(let s=0;s<t;s++){n=e[s];let o;if(r=n,!Wa(n)&&(r=Zt[(o=String(n)).toLowerCase()],r===void 0))throw new y(`Unknown adapter '${o}'`);if(r)break;i[o||"#"+s]=r}if(!r){const s=Object.entries(i).map(([a,c])=>`adapter ${a} `+(c===!1?"is not supported by the environment":"is not available in the build"));let o=t?s.length>1?`since :
`+s.map(mr).join(`
`):" "+mr(s[0]):"as no adapter specified";throw new y("There is no suitable adapter to dispatch the request "+o,"ERR_NOT_SUPPORT")}return r},adapters:Zt};function Dt(e){if(e.cancelToken&&e.cancelToken.throwIfRequested(),e.signal&&e.signal.aborted)throw new Ee(null,e)}function br(e){return Dt(e),e.headers=P.from(e.headers),e.data=Rt.call(e,e.transformRequest),["post","put","patch"].indexOf(e.method)!==-1&&e.headers.setContentType("application/x-www-form-urlencoded",!1),Ei.getAdapter(e.adapter||Ue.adapter)(e).then(function(r){return Dt(e),r.data=Rt.call(e,e.transformResponse,r),r.headers=P.from(r.headers),r},function(r){return gi(r)||(Dt(e),r&&r.response&&(r.response.data=Rt.call(e,e.transformResponse,r.response),r.response.headers=P.from(r.response.headers))),Promise.reject(r)})}const Si="1.11.0",mt={};["object","boolean","number","function","string","symbol"].forEach((e,t)=>{mt[e]=function(r){return typeof r===e||"a"+(t<1?"n ":" ")+e}});const _r={};mt.transitional=function(t,n,r){function i(s,o){return"[Axios v"+Si+"] Transitional option '"+s+"'"+o+(r?". "+r:"")}return(s,o,a)=>{if(t===!1)throw new y(i(o," has been removed"+(n?" in "+n:"")),y.ERR_DEPRECATED);return n&&!_r[o]&&(_r[o]=!0,console.warn(i(o," has been deprecated since v"+n+" and will be removed in the near future"))),t?t(s,o,a):!0}};mt.spelling=function(t){return(n,r)=>(console.warn(`${r} is likely a misspelling of ${t}`),!0)};function Ja(e,t,n){if(typeof e!="object")throw new y("options must be an object",y.ERR_BAD_OPTION_VALUE);const r=Object.keys(e);let i=r.length;for(;i-- >0;){const s=r[i],o=t[s];if(o){const a=e[s],c=a===void 0||o(a,s,e);if(c!==!0)throw new y("option "+s+" must be "+c,y.ERR_BAD_OPTION_VALUE);continue}if(n!==!0)throw new y("Unknown option "+s,y.ERR_BAD_OPTION)}}const et={assertOptions:Ja,validators:mt},j=et.validators;let se=class{constructor(t){this.defaults=t||{},this.interceptors={request:new ur,response:new ur}}async request(t,n){try{return await this._request(t,n)}catch(r){if(r instanceof Error){let i={};Error.captureStackTrace?Error.captureStackTrace(i):i=new Error;const s=i.stack?i.stack.replace(/^.+\n/,""):"";try{r.stack?s&&!String(r.stack).endsWith(s.replace(/^.+\n.+\n/,""))&&(r.stack+=`
`+s):r.stack=s}catch{}}throw r}}_request(t,n){typeof t=="string"?(n=n||{},n.url=t):n=t||{},n=le(this.defaults,n);const{transitional:r,paramsSerializer:i,headers:s}=n;r!==void 0&&et.assertOptions(r,{silentJSONParsing:j.transitional(j.boolean),forcedJSONParsing:j.transitional(j.boolean),clarifyTimeoutError:j.transitional(j.boolean)},!1),i!=null&&(f.isFunction(i)?n.paramsSerializer={serialize:i}:et.assertOptions(i,{encode:j.function,serialize:j.function},!0)),n.allowAbsoluteUrls!==void 0||(this.defaults.allowAbsoluteUrls!==void 0?n.allowAbsoluteUrls=this.defaults.allowAbsoluteUrls:n.allowAbsoluteUrls=!0),et.assertOptions(n,{baseUrl:j.spelling("baseURL"),withXsrfToken:j.spelling("withXSRFToken")},!0),n.method=(n.method||this.defaults.method||"get").toLowerCase();let o=s&&f.merge(s.common,s[n.method]);s&&f.forEach(["delete","get","head","post","put","patch","common"],g=>{delete s[g]}),n.headers=P.concat(o,s);const a=[];let c=!0;this.interceptors.request.forEach(function(b){typeof b.runWhen=="function"&&b.runWhen(n)===!1||(c=c&&b.synchronous,a.unshift(b.fulfilled,b.rejected))});const u=[];this.interceptors.response.forEach(function(b){u.push(b.fulfilled,b.rejected)});let l,d=0,h;if(!c){const g=[br.bind(this),void 0];for(g.unshift(...a),g.push(...u),h=g.length,l=Promise.resolve(n);d<h;)l=l.then(g[d++],g[d++]);return l}h=a.length;let _=n;for(d=0;d<h;){const g=a[d++],b=a[d++];try{_=g(_)}catch(p){b.call(this,p);break}}try{l=br.call(this,_)}catch(g){return Promise.reject(g)}for(d=0,h=u.length;d<h;)l=l.then(u[d++],u[d++]);return l}getUri(t){t=le(this.defaults,t);const n=bi(t.baseURL,t.url,t.allowAbsoluteUrls);return di(n,t.params,t.paramsSerializer)}};f.forEach(["delete","get","head","options"],function(t){se.prototype[t]=function(n,r){return this.request(le(r||{},{method:t,url:n,data:(r||{}).data}))}});f.forEach(["post","put","patch"],function(t){function n(r){return function(s,o,a){return this.request(le(a||{},{method:t,headers:r?{"Content-Type":"multipart/form-data"}:{},url:s,data:o}))}}se.prototype[t]=n(),se.prototype[t+"Form"]=n(!0)});let Ga=class Ai{constructor(t){if(typeof t!="function")throw new TypeError("executor must be a function.");let n;this.promise=new Promise(function(s){n=s});const r=this;this.promise.then(i=>{if(!r._listeners)return;let s=r._listeners.length;for(;s-- >0;)r._listeners[s](i);r._listeners=null}),this.promise.then=i=>{let s;const o=new Promise(a=>{r.subscribe(a),s=a}).then(i);return o.cancel=function(){r.unsubscribe(s)},o},t(function(s,o,a){r.reason||(r.reason=new Ee(s,o,a),n(r.reason))})}throwIfRequested(){if(this.reason)throw this.reason}subscribe(t){if(this.reason){t(this.reason);return}this._listeners?this._listeners.push(t):this._listeners=[t]}unsubscribe(t){if(!this._listeners)return;const n=this._listeners.indexOf(t);n!==-1&&this._listeners.splice(n,1)}toAbortSignal(){const t=new AbortController,n=r=>{t.abort(r)};return this.subscribe(n),t.signal.unsubscribe=()=>this.unsubscribe(n),t.signal}static source(){let t;return{token:new Ai(function(i){t=i}),cancel:t}}};function Xa(e){return function(n){return e.apply(null,n)}}function Ya(e){return f.isObject(e)&&e.isAxiosError===!0}const Qt={Continue:100,SwitchingProtocols:101,Processing:102,EarlyHints:103,Ok:200,Created:201,Accepted:202,NonAuthoritativeInformation:203,NoContent:204,ResetContent:205,PartialContent:206,MultiStatus:207,AlreadyReported:208,ImUsed:226,MultipleChoices:300,MovedPermanently:301,Found:302,SeeOther:303,NotModified:304,UseProxy:305,Unused:306,TemporaryRedirect:307,PermanentRedirect:308,BadRequest:400,Unauthorized:401,PaymentRequired:402,Forbidden:403,NotFound:404,MethodNotAllowed:405,NotAcceptable:406,ProxyAuthenticationRequired:407,RequestTimeout:408,Conflict:409,Gone:410,LengthRequired:411,PreconditionFailed:412,PayloadTooLarge:413,UriTooLong:414,UnsupportedMediaType:415,RangeNotSatisfiable:416,ExpectationFailed:417,ImATeapot:418,MisdirectedRequest:421,UnprocessableEntity:422,Locked:423,FailedDependency:424,TooEarly:425,UpgradeRequired:426,PreconditionRequired:428,TooManyRequests:429,RequestHeaderFieldsTooLarge:431,UnavailableForLegalReasons:451,InternalServerError:500,NotImplemented:501,BadGateway:502,ServiceUnavailable:503,GatewayTimeout:504,HttpVersionNotSupported:505,VariantAlsoNegotiates:506,InsufficientStorage:507,LoopDetected:508,NotExtended:510,NetworkAuthenticationRequired:511};Object.entries(Qt).forEach(([e,t])=>{Qt[t]=e});function xi(e){const t=new se(e),n=ei(se.prototype.request,t);return f.extend(n,se.prototype,t,{allOwnKeys:!0}),f.extend(n,t,null,{allOwnKeys:!0}),n.create=function(i){return xi(le(e,i))},n}const v=xi(Ue);v.Axios=se;v.CanceledError=Ee;v.CancelToken=Ga;v.isCancel=gi;v.VERSION=Si;v.toFormData=ht;v.AxiosError=y;v.Cancel=v.CanceledError;v.all=function(t){return Promise.all(t)};v.spread=Xa;v.isAxiosError=Ya;v.mergeConfig=le;v.AxiosHeaders=P;v.formToJSON=e=>hi(f.isHTMLForm(e)?new FormData(e):e);v.getAdapter=Ei.getAdapter;v.HttpStatusCode=Qt;v.default=v;const{Axios:pp,AxiosError:hp,CanceledError:gp,isCancel:mp,CancelToken:bp,VERSION:_p,all:yp,Cancel:wp,isAxiosError:Ep,spread:Sp,toFormData:Ap,AxiosHeaders:xp,HttpStatusCode:vp,formToJSON:Tp,getAdapter:Cp,mergeConfig:Op}=v;window.axios=v;window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";const yr=document.querySelector('meta[name="csrf-token"]');yr?window.axios.defaults.headers.common["X-CSRF-TOKEN"]=yr.getAttribute("content"):console.warn("[Smart-Saka] CSRF token meta tag tidak ditemukan di layout Blade.");window.axios.interceptors.response.use(e=>e,e=>(e.response?.status===419&&window.location.reload(),Promise.reject(e)));const Za=()=>{};var wr={};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const vi=function(e){const t=[];let n=0;for(let r=0;r<e.length;r++){let i=e.charCodeAt(r);i<128?t[n++]=i:i<2048?(t[n++]=i>>6|192,t[n++]=i&63|128):(i&64512)===55296&&r+1<e.length&&(e.charCodeAt(r+1)&64512)===56320?(i=65536+((i&1023)<<10)+(e.charCodeAt(++r)&1023),t[n++]=i>>18|240,t[n++]=i>>12&63|128,t[n++]=i>>6&63|128,t[n++]=i&63|128):(t[n++]=i>>12|224,t[n++]=i>>6&63|128,t[n++]=i&63|128)}return t},Qa=function(e){const t=[];let n=0,r=0;for(;n<e.length;){const i=e[n++];if(i<128)t[r++]=String.fromCharCode(i);else if(i>191&&i<224){const s=e[n++];t[r++]=String.fromCharCode((i&31)<<6|s&63)}else if(i>239&&i<365){const s=e[n++],o=e[n++],a=e[n++],c=((i&7)<<18|(s&63)<<12|(o&63)<<6|a&63)-65536;t[r++]=String.fromCharCode(55296+(c>>10)),t[r++]=String.fromCharCode(56320+(c&1023))}else{const s=e[n++],o=e[n++];t[r++]=String.fromCharCode((i&15)<<12|(s&63)<<6|o&63)}}return t.join("")},Ti={byteToCharMap_:null,charToByteMap_:null,byteToCharMapWebSafe_:null,charToByteMapWebSafe_:null,ENCODED_VALS_BASE:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",get ENCODED_VALS(){return this.ENCODED_VALS_BASE+"+/="},get ENCODED_VALS_WEBSAFE(){return this.ENCODED_VALS_BASE+"-_."},HAS_NATIVE_SUPPORT:typeof atob=="function",encodeByteArray(e,t){if(!Array.isArray(e))throw Error("encodeByteArray takes an array as a parameter");this.init_();const n=t?this.byteToCharMapWebSafe_:this.byteToCharMap_,r=[];for(let i=0;i<e.length;i+=3){const s=e[i],o=i+1<e.length,a=o?e[i+1]:0,c=i+2<e.length,u=c?e[i+2]:0,l=s>>2,d=(s&3)<<4|a>>4;let h=(a&15)<<2|u>>6,_=u&63;c||(_=64,o||(h=64)),r.push(n[l],n[d],n[h],n[_])}return r.join("")},encodeString(e,t){return this.HAS_NATIVE_SUPPORT&&!t?btoa(e):this.encodeByteArray(vi(e),t)},decodeString(e,t){return this.HAS_NATIVE_SUPPORT&&!t?atob(e):Qa(this.decodeStringToByteArray(e,t))},decodeStringToByteArray(e,t){this.init_();const n=t?this.charToByteMapWebSafe_:this.charToByteMap_,r=[];for(let i=0;i<e.length;){const s=n[e.charAt(i++)],a=i<e.length?n[e.charAt(i)]:0;++i;const u=i<e.length?n[e.charAt(i)]:64;++i;const d=i<e.length?n[e.charAt(i)]:64;if(++i,s==null||a==null||u==null||d==null)throw new ec;const h=s<<2|a>>4;if(r.push(h),u!==64){const _=a<<4&240|u>>2;if(r.push(_),d!==64){const g=u<<6&192|d;r.push(g)}}}return r},init_(){if(!this.byteToCharMap_){this.byteToCharMap_={},this.charToByteMap_={},this.byteToCharMapWebSafe_={},this.charToByteMapWebSafe_={};for(let e=0;e<this.ENCODED_VALS.length;e++)this.byteToCharMap_[e]=this.ENCODED_VALS.charAt(e),this.charToByteMap_[this.byteToCharMap_[e]]=e,this.byteToCharMapWebSafe_[e]=this.ENCODED_VALS_WEBSAFE.charAt(e),this.charToByteMapWebSafe_[this.byteToCharMapWebSafe_[e]]=e,e>=this.ENCODED_VALS_BASE.length&&(this.charToByteMap_[this.ENCODED_VALS_WEBSAFE.charAt(e)]=e,this.charToByteMapWebSafe_[this.ENCODED_VALS.charAt(e)]=e)}}};class ec extends Error{constructor(){super(...arguments),this.name="DecodeBase64StringError"}}const tc=function(e){const t=vi(e);return Ti.encodeByteArray(t,!0)},Ci=function(e){return tc(e).replace(/\./g,"")},nc=function(e){try{return Ti.decodeString(e,!0)}catch(t){console.error("base64Decode failed: ",t)}return null};/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function rc(){if(typeof self<"u")return self;if(typeof window<"u")return window;if(typeof global<"u")return global;throw new Error("Unable to locate global object.")}/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ic=()=>rc().__FIREBASE_DEFAULTS__,sc=()=>{if(typeof process>"u"||typeof wr>"u")return;const e=wr.__FIREBASE_DEFAULTS__;if(e)return JSON.parse(e)},oc=()=>{if(typeof document>"u")return;let e;try{e=document.cookie.match(/__FIREBASE_DEFAULTS__=([^;]+)/)}catch{return}const t=e&&nc(e[1]);return t&&JSON.parse(t)},ac=()=>{try{return Za()||ic()||sc()||oc()}catch(e){console.info(`Unable to get __FIREBASE_DEFAULTS__ due to: ${e}`);return}},Oi=()=>ac()?.config;/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class cc{constructor(){this.reject=()=>{},this.resolve=()=>{},this.promise=new Promise((t,n)=>{this.resolve=t,this.reject=n})}wrapCallback(t){return(n,r)=>{n?this.reject(n):this.resolve(r),typeof t=="function"&&(this.promise.catch(()=>{}),t.length===1?t(n):t(n,r))}}}function Ii(){try{return typeof indexedDB=="object"}catch{return!1}}function Ri(){return new Promise((e,t)=>{try{let n=!0;const r="validate-browser-context-for-indexeddb-analytics-module",i=self.indexedDB.open(r);i.onsuccess=()=>{i.result.close(),n||self.indexedDB.deleteDatabase(r),e(!0)},i.onupgradeneeded=()=>{n=!1},i.onerror=()=>{t(i.error?.message||"")}}catch(n){t(n)}})}function uc(){return!(typeof navigator>"u"||!navigator.cookieEnabled)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const lc="FirebaseError";class Se extends Error{constructor(t,n,r){super(n),this.code=t,this.customData=r,this.name=lc,Object.setPrototypeOf(this,Se.prototype),Error.captureStackTrace&&Error.captureStackTrace(this,bt.prototype.create)}}class bt{constructor(t,n,r){this.service=t,this.serviceName=n,this.errors=r}create(t,...n){const r=n[0]||{},i=`${this.service}/${t}`,s=this.errors[t],o=s?fc(s,r):"Error",a=`${this.serviceName}: ${o} (${i}).`;return new Se(i,a,r)}}function fc(e,t){return e.replace(dc,(n,r)=>{const i=t[r];return i!=null?String(i):`<${r}?>`})}const dc=/\{\$([^}]+)}/g;function en(e,t){if(e===t)return!0;const n=Object.keys(e),r=Object.keys(t);for(const i of n){if(!r.includes(i))return!1;const s=e[i],o=t[i];if(Er(s)&&Er(o)){if(!en(s,o))return!1}else if(s!==o)return!1}for(const i of r)if(!n.includes(i))return!1;return!0}function Er(e){return e!==null&&typeof e=="object"}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function _t(e){return e&&e._delegate?e._delegate:e}class X{constructor(t,n,r){this.name=t,this.instanceFactory=n,this.type=r,this.multipleInstances=!1,this.serviceProps={},this.instantiationMode="LAZY",this.onInstanceCreated=null}setInstantiationMode(t){return this.instantiationMode=t,this}setMultipleInstances(t){return this.multipleInstances=t,this}setServiceProps(t){return this.serviceProps=t,this}setInstanceCreatedCallback(t){return this.onInstanceCreated=t,this}}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ee="[DEFAULT]";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class pc{constructor(t,n){this.name=t,this.container=n,this.component=null,this.instances=new Map,this.instancesDeferred=new Map,this.instancesOptions=new Map,this.onInitCallbacks=new Map}get(t){const n=this.normalizeInstanceIdentifier(t);if(!this.instancesDeferred.has(n)){const r=new cc;if(this.instancesDeferred.set(n,r),this.isInitialized(n)||this.shouldAutoInitialize())try{const i=this.getOrInitializeService({instanceIdentifier:n});i&&r.resolve(i)}catch{}}return this.instancesDeferred.get(n).promise}getImmediate(t){const n=this.normalizeInstanceIdentifier(t?.identifier),r=t?.optional??!1;if(this.isInitialized(n)||this.shouldAutoInitialize())try{return this.getOrInitializeService({instanceIdentifier:n})}catch(i){if(r)return null;throw i}else{if(r)return null;throw Error(`Service ${this.name} is not available`)}}getComponent(){return this.component}setComponent(t){if(t.name!==this.name)throw Error(`Mismatching Component ${t.name} for Provider ${this.name}.`);if(this.component)throw Error(`Component for ${this.name} has already been provided`);if(this.component=t,!!this.shouldAutoInitialize()){if(gc(t))try{this.getOrInitializeService({instanceIdentifier:ee})}catch{}for(const[n,r]of this.instancesDeferred.entries()){const i=this.normalizeInstanceIdentifier(n);try{const s=this.getOrInitializeService({instanceIdentifier:i});r.resolve(s)}catch{}}}}clearInstance(t=ee){this.instancesDeferred.delete(t),this.instancesOptions.delete(t),this.instances.delete(t)}async delete(){const t=Array.from(this.instances.values());await Promise.all([...t.filter(n=>"INTERNAL"in n).map(n=>n.INTERNAL.delete()),...t.filter(n=>"_delete"in n).map(n=>n._delete())])}isComponentSet(){return this.component!=null}isInitialized(t=ee){return this.instances.has(t)}getOptions(t=ee){return this.instancesOptions.get(t)||{}}initialize(t={}){const{options:n={}}=t,r=this.normalizeInstanceIdentifier(t.instanceIdentifier);if(this.isInitialized(r))throw Error(`${this.name}(${r}) has already been initialized`);if(!this.isComponentSet())throw Error(`Component ${this.name} has not been registered yet`);const i=this.getOrInitializeService({instanceIdentifier:r,options:n});for(const[s,o]of this.instancesDeferred.entries()){const a=this.normalizeInstanceIdentifier(s);r===a&&o.resolve(i)}return i}onInit(t,n){const r=this.normalizeInstanceIdentifier(n),i=this.onInitCallbacks.get(r)??new Set;i.add(t),this.onInitCallbacks.set(r,i);const s=this.instances.get(r);return s&&t(s,r),()=>{i.delete(t)}}invokeOnInitCallbacks(t,n){const r=this.onInitCallbacks.get(n);if(r)for(const i of r)try{i(t,n)}catch{}}getOrInitializeService({instanceIdentifier:t,options:n={}}){let r=this.instances.get(t);if(!r&&this.component&&(r=this.component.instanceFactory(this.container,{instanceIdentifier:hc(t),options:n}),this.instances.set(t,r),this.instancesOptions.set(t,n),this.invokeOnInitCallbacks(r,t),this.component.onInstanceCreated))try{this.component.onInstanceCreated(this.container,t,r)}catch{}return r||null}normalizeInstanceIdentifier(t=ee){return this.component?this.component.multipleInstances?t:ee:t}shouldAutoInitialize(){return!!this.component&&this.component.instantiationMode!=="EXPLICIT"}}function hc(e){return e===ee?void 0:e}function gc(e){return e.instantiationMode==="EAGER"}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class mc{constructor(t){this.name=t,this.providers=new Map}addComponent(t){const n=this.getProvider(t.name);if(n.isComponentSet())throw new Error(`Component ${t.name} has already been registered with ${this.name}`);n.setComponent(t)}addOrOverwriteComponent(t){this.getProvider(t.name).isComponentSet()&&this.providers.delete(t.name),this.addComponent(t)}getProvider(t){if(this.providers.has(t))return this.providers.get(t);const n=new pc(t,this);return this.providers.set(t,n),n}getProviders(){return Array.from(this.providers.values())}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var S;(function(e){e[e.DEBUG=0]="DEBUG",e[e.VERBOSE=1]="VERBOSE",e[e.INFO=2]="INFO",e[e.WARN=3]="WARN",e[e.ERROR=4]="ERROR",e[e.SILENT=5]="SILENT"})(S||(S={}));const bc={debug:S.DEBUG,verbose:S.VERBOSE,info:S.INFO,warn:S.WARN,error:S.ERROR,silent:S.SILENT},_c=S.INFO,yc={[S.DEBUG]:"log",[S.VERBOSE]:"log",[S.INFO]:"info",[S.WARN]:"warn",[S.ERROR]:"error"},wc=(e,t,...n)=>{if(t<e.logLevel)return;const r=new Date().toISOString(),i=yc[t];if(i)console[i](`[${r}]  ${e.name}:`,...n);else throw new Error(`Attempted to log a message with an invalid logType (value: ${t})`)};class Ec{constructor(t){this.name=t,this._logLevel=_c,this._logHandler=wc,this._userLogHandler=null}get logLevel(){return this._logLevel}set logLevel(t){if(!(t in S))throw new TypeError(`Invalid value "${t}" assigned to \`logLevel\``);this._logLevel=t}setLogLevel(t){this._logLevel=typeof t=="string"?bc[t]:t}get logHandler(){return this._logHandler}set logHandler(t){if(typeof t!="function")throw new TypeError("Value assigned to `logHandler` must be a function");this._logHandler=t}get userLogHandler(){return this._userLogHandler}set userLogHandler(t){this._userLogHandler=t}debug(...t){this._userLogHandler&&this._userLogHandler(this,S.DEBUG,...t),this._logHandler(this,S.DEBUG,...t)}log(...t){this._userLogHandler&&this._userLogHandler(this,S.VERBOSE,...t),this._logHandler(this,S.VERBOSE,...t)}info(...t){this._userLogHandler&&this._userLogHandler(this,S.INFO,...t),this._logHandler(this,S.INFO,...t)}warn(...t){this._userLogHandler&&this._userLogHandler(this,S.WARN,...t),this._logHandler(this,S.WARN,...t)}error(...t){this._userLogHandler&&this._userLogHandler(this,S.ERROR,...t),this._logHandler(this,S.ERROR,...t)}}const Sc=(e,t)=>t.some(n=>e instanceof n);let Sr,Ar;function Ac(){return Sr||(Sr=[IDBDatabase,IDBObjectStore,IDBIndex,IDBCursor,IDBTransaction])}function xc(){return Ar||(Ar=[IDBCursor.prototype.advance,IDBCursor.prototype.continue,IDBCursor.prototype.continuePrimaryKey])}const Di=new WeakMap,tn=new WeakMap,ki=new WeakMap,kt=new WeakMap,On=new WeakMap;function vc(e){const t=new Promise((n,r)=>{const i=()=>{e.removeEventListener("success",s),e.removeEventListener("error",o)},s=()=>{n(q(e.result)),i()},o=()=>{r(e.error),i()};e.addEventListener("success",s),e.addEventListener("error",o)});return t.then(n=>{n instanceof IDBCursor&&Di.set(n,e)}).catch(()=>{}),On.set(t,e),t}function Tc(e){if(tn.has(e))return;const t=new Promise((n,r)=>{const i=()=>{e.removeEventListener("complete",s),e.removeEventListener("error",o),e.removeEventListener("abort",o)},s=()=>{n(),i()},o=()=>{r(e.error||new DOMException("AbortError","AbortError")),i()};e.addEventListener("complete",s),e.addEventListener("error",o),e.addEventListener("abort",o)});tn.set(e,t)}let nn={get(e,t,n){if(e instanceof IDBTransaction){if(t==="done")return tn.get(e);if(t==="objectStoreNames")return e.objectStoreNames||ki.get(e);if(t==="store")return n.objectStoreNames[1]?void 0:n.objectStore(n.objectStoreNames[0])}return q(e[t])},set(e,t,n){return e[t]=n,!0},has(e,t){return e instanceof IDBTransaction&&(t==="done"||t==="store")?!0:t in e}};function Cc(e){nn=e(nn)}function Oc(e){return e===IDBDatabase.prototype.transaction&&!("objectStoreNames"in IDBTransaction.prototype)?function(t,...n){const r=e.call(Nt(this),t,...n);return ki.set(r,t.sort?t.sort():[t]),q(r)}:xc().includes(e)?function(...t){return e.apply(Nt(this),t),q(Di.get(this))}:function(...t){return q(e.apply(Nt(this),t))}}function Ic(e){return typeof e=="function"?Oc(e):(e instanceof IDBTransaction&&Tc(e),Sc(e,Ac())?new Proxy(e,nn):e)}function q(e){if(e instanceof IDBRequest)return vc(e);if(kt.has(e))return kt.get(e);const t=Ic(e);return t!==e&&(kt.set(e,t),On.set(t,e)),t}const Nt=e=>On.get(e);function yt(e,t,{blocked:n,upgrade:r,blocking:i,terminated:s}={}){const o=indexedDB.open(e,t),a=q(o);return r&&o.addEventListener("upgradeneeded",c=>{r(q(o.result),c.oldVersion,c.newVersion,q(o.transaction),c)}),n&&o.addEventListener("blocked",c=>n(c.oldVersion,c.newVersion,c)),a.then(c=>{s&&c.addEventListener("close",()=>s()),i&&c.addEventListener("versionchange",u=>i(u.oldVersion,u.newVersion,u))}).catch(()=>{}),a}function Pt(e,{blocked:t}={}){const n=indexedDB.deleteDatabase(e);return t&&n.addEventListener("blocked",r=>t(r.oldVersion,r)),q(n).then(()=>{})}const Rc=["get","getKey","getAll","getAllKeys","count"],Dc=["put","add","delete","clear"],Mt=new Map;function xr(e,t){if(!(e instanceof IDBDatabase&&!(t in e)&&typeof t=="string"))return;if(Mt.get(t))return Mt.get(t);const n=t.replace(/FromIndex$/,""),r=t!==n,i=Dc.includes(n);if(!(n in(r?IDBIndex:IDBObjectStore).prototype)||!(i||Rc.includes(n)))return;const s=async function(o,...a){const c=this.transaction(o,i?"readwrite":"readonly");let u=c.store;return r&&(u=u.index(a.shift())),(await Promise.all([u[n](...a),i&&c.done]))[0]};return Mt.set(t,s),s}Cc(e=>({...e,get:(t,n,r)=>xr(t,n)||e.get(t,n,r),has:(t,n)=>!!xr(t,n)||e.has(t,n)}));/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class kc{constructor(t){this.container=t}getPlatformInfoString(){return this.container.getProviders().map(n=>{if(Nc(n)){const r=n.getImmediate();return`${r.library}/${r.version}`}else return null}).filter(n=>n).join(" ")}}function Nc(e){return e.getComponent()?.type==="VERSION"}const rn="@firebase/app",vr="0.14.11";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const K=new Ec("@firebase/app"),Pc="@firebase/app-compat",Mc="@firebase/analytics-compat",Bc="@firebase/analytics",Fc="@firebase/app-check-compat",$c="@firebase/app-check",Lc="@firebase/auth",jc="@firebase/auth-compat",Hc="@firebase/database",Uc="@firebase/data-connect",qc="@firebase/database-compat",Kc="@firebase/functions",Vc="@firebase/functions-compat",zc="@firebase/installations",Wc="@firebase/installations-compat",Jc="@firebase/messaging",Gc="@firebase/messaging-compat",Xc="@firebase/performance",Yc="@firebase/performance-compat",Zc="@firebase/remote-config",Qc="@firebase/remote-config-compat",eu="@firebase/storage",tu="@firebase/storage-compat",nu="@firebase/firestore",ru="@firebase/ai",iu="@firebase/firestore-compat",su="firebase";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const sn="[DEFAULT]",ou={[rn]:"fire-core",[Pc]:"fire-core-compat",[Bc]:"fire-analytics",[Mc]:"fire-analytics-compat",[$c]:"fire-app-check",[Fc]:"fire-app-check-compat",[Lc]:"fire-auth",[jc]:"fire-auth-compat",[Hc]:"fire-rtdb",[Uc]:"fire-data-connect",[qc]:"fire-rtdb-compat",[Kc]:"fire-fn",[Vc]:"fire-fn-compat",[zc]:"fire-iid",[Wc]:"fire-iid-compat",[Jc]:"fire-fcm",[Gc]:"fire-fcm-compat",[Xc]:"fire-perf",[Yc]:"fire-perf-compat",[Zc]:"fire-rc",[Qc]:"fire-rc-compat",[eu]:"fire-gcs",[tu]:"fire-gcs-compat",[nu]:"fire-fst",[iu]:"fire-fst-compat",[ru]:"fire-vertex","fire-js":"fire-js",[su]:"fire-js-all"};/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const st=new Map,au=new Map,on=new Map;function Tr(e,t){try{e.container.addComponent(t)}catch(n){K.debug(`Component ${t.name} failed to register with FirebaseApp ${e.name}`,n)}}function fe(e){const t=e.name;if(on.has(t))return K.debug(`There were multiple attempts to register component ${t}.`),!1;on.set(t,e);for(const n of st.values())Tr(n,e);for(const n of au.values())Tr(n,e);return!0}function In(e,t){const n=e.container.getProvider("heartbeat").getImmediate({optional:!0});return n&&n.triggerHeartbeat(),e.container.getProvider(t)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const cu={"no-app":"No Firebase App '{$appName}' has been created - call initializeApp() first","bad-app-name":"Illegal App name: '{$appName}'","duplicate-app":"Firebase App named '{$appName}' already exists with different options or config","app-deleted":"Firebase App named '{$appName}' already deleted","server-app-deleted":"Firebase Server App has been deleted","no-options":"Need to provide options, when not being deployed to hosting via source.","invalid-app-argument":"firebase.{$appName}() takes either no argument or a Firebase App instance.","invalid-log-argument":"First argument to `onLog` must be null or a function.","idb-open":"Error thrown when opening IndexedDB. Original error: {$originalErrorMessage}.","idb-get":"Error thrown when reading from IndexedDB. Original error: {$originalErrorMessage}.","idb-set":"Error thrown when writing to IndexedDB. Original error: {$originalErrorMessage}.","idb-delete":"Error thrown when deleting from IndexedDB. Original error: {$originalErrorMessage}.","finalization-registry-not-supported":"FirebaseServerApp deleteOnDeref field defined but the JS runtime does not support FinalizationRegistry.","invalid-server-app-environment":"FirebaseServerApp is not for use in browser environments."},W=new bt("app","Firebase",cu);/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class uu{constructor(t,n,r){this._isDeleted=!1,this._options={...t},this._config={...n},this._name=n.name,this._automaticDataCollectionEnabled=n.automaticDataCollectionEnabled,this._container=r,this.container.addComponent(new X("app",()=>this,"PUBLIC"))}get automaticDataCollectionEnabled(){return this.checkDestroyed(),this._automaticDataCollectionEnabled}set automaticDataCollectionEnabled(t){this.checkDestroyed(),this._automaticDataCollectionEnabled=t}get name(){return this.checkDestroyed(),this._name}get options(){return this.checkDestroyed(),this._options}get config(){return this.checkDestroyed(),this._config}get container(){return this._container}get isDeleted(){return this._isDeleted}set isDeleted(t){this._isDeleted=t}checkDestroyed(){if(this.isDeleted)throw W.create("app-deleted",{appName:this._name})}}function Ni(e,t={}){let n=e;typeof t!="object"&&(t={name:t});const r={name:sn,automaticDataCollectionEnabled:!0,...t},i=r.name;if(typeof i!="string"||!i)throw W.create("bad-app-name",{appName:String(i)});if(n||(n=Oi()),!n)throw W.create("no-options");const s=st.get(i);if(s){if(en(n,s.options)&&en(r,s.config))return s;throw W.create("duplicate-app",{appName:i})}const o=new mc(i);for(const c of on.values())o.addComponent(c);const a=new uu(n,r,o);return st.set(i,a),a}function lu(e=sn){const t=st.get(e);if(!t&&e===sn&&Oi())return Ni();if(!t)throw W.create("no-app",{appName:e});return t}function J(e,t,n){let r=ou[e]??e;n&&(r+=`-${n}`);const i=r.match(/\s|\//),s=t.match(/\s|\//);if(i||s){const o=[`Unable to register library "${r}" with version "${t}":`];i&&o.push(`library name "${r}" contains illegal characters (whitespace or "/")`),i&&s&&o.push("and"),s&&o.push(`version name "${t}" contains illegal characters (whitespace or "/")`),K.warn(o.join(" "));return}fe(new X(`${r}-version`,()=>({library:r,version:t}),"VERSION"))}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const fu="firebase-heartbeat-database",du=1,Be="firebase-heartbeat-store";let Bt=null;function Pi(){return Bt||(Bt=yt(fu,du,{upgrade:(e,t)=>{switch(t){case 0:try{e.createObjectStore(Be)}catch(n){console.warn(n)}}}}).catch(e=>{throw W.create("idb-open",{originalErrorMessage:e.message})})),Bt}async function pu(e){try{const n=(await Pi()).transaction(Be),r=await n.objectStore(Be).get(Mi(e));return await n.done,r}catch(t){if(t instanceof Se)K.warn(t.message);else{const n=W.create("idb-get",{originalErrorMessage:t?.message});K.warn(n.message)}}}async function Cr(e,t){try{const r=(await Pi()).transaction(Be,"readwrite");await r.objectStore(Be).put(t,Mi(e)),await r.done}catch(n){if(n instanceof Se)K.warn(n.message);else{const r=W.create("idb-set",{originalErrorMessage:n?.message});K.warn(r.message)}}}function Mi(e){return`${e.name}!${e.options.appId}`}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const hu=1024,gu=30;class mu{constructor(t){this.container=t,this._heartbeatsCache=null;const n=this.container.getProvider("app").getImmediate();this._storage=new _u(n),this._heartbeatsCachePromise=this._storage.read().then(r=>(this._heartbeatsCache=r,r))}async triggerHeartbeat(){try{const n=this.container.getProvider("platform-logger").getImmediate().getPlatformInfoString(),r=Or();if(this._heartbeatsCache?.heartbeats==null&&(this._heartbeatsCache=await this._heartbeatsCachePromise,this._heartbeatsCache?.heartbeats==null)||this._heartbeatsCache.lastSentHeartbeatDate===r||this._heartbeatsCache.heartbeats.some(i=>i.date===r))return;if(this._heartbeatsCache.heartbeats.push({date:r,agent:n}),this._heartbeatsCache.heartbeats.length>gu){const i=yu(this._heartbeatsCache.heartbeats);this._heartbeatsCache.heartbeats.splice(i,1)}return this._storage.overwrite(this._heartbeatsCache)}catch(t){K.warn(t)}}async getHeartbeatsHeader(){try{if(this._heartbeatsCache===null&&await this._heartbeatsCachePromise,this._heartbeatsCache?.heartbeats==null||this._heartbeatsCache.heartbeats.length===0)return"";const t=Or(),{heartbeatsToSend:n,unsentEntries:r}=bu(this._heartbeatsCache.heartbeats),i=Ci(JSON.stringify({version:2,heartbeats:n}));return this._heartbeatsCache.lastSentHeartbeatDate=t,r.length>0?(this._heartbeatsCache.heartbeats=r,await this._storage.overwrite(this._heartbeatsCache)):(this._heartbeatsCache.heartbeats=[],this._storage.overwrite(this._heartbeatsCache)),i}catch(t){return K.warn(t),""}}}function Or(){return new Date().toISOString().substring(0,10)}function bu(e,t=hu){const n=[];let r=e.slice();for(const i of e){const s=n.find(o=>o.agent===i.agent);if(s){if(s.dates.push(i.date),Ir(n)>t){s.dates.pop();break}}else if(n.push({agent:i.agent,dates:[i.date]}),Ir(n)>t){n.pop();break}r=r.slice(1)}return{heartbeatsToSend:n,unsentEntries:r}}class _u{constructor(t){this.app=t,this._canUseIndexedDBPromise=this.runIndexedDBEnvironmentCheck()}async runIndexedDBEnvironmentCheck(){return Ii()?Ri().then(()=>!0).catch(()=>!1):!1}async read(){if(await this._canUseIndexedDBPromise){const n=await pu(this.app);return n?.heartbeats?n:{heartbeats:[]}}else return{heartbeats:[]}}async overwrite(t){if(await this._canUseIndexedDBPromise){const r=await this.read();return Cr(this.app,{lastSentHeartbeatDate:t.lastSentHeartbeatDate??r.lastSentHeartbeatDate,heartbeats:t.heartbeats})}else return}async add(t){if(await this._canUseIndexedDBPromise){const r=await this.read();return Cr(this.app,{lastSentHeartbeatDate:t.lastSentHeartbeatDate??r.lastSentHeartbeatDate,heartbeats:[...r.heartbeats,...t.heartbeats]})}else return}}function Ir(e){return Ci(JSON.stringify({version:2,heartbeats:e})).length}function yu(e){if(e.length===0)return-1;let t=0,n=e[0].date;for(let r=1;r<e.length;r++)e[r].date<n&&(n=e[r].date,t=r);return t}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function wu(e){fe(new X("platform-logger",t=>new kc(t),"PRIVATE")),fe(new X("heartbeat",t=>new mu(t),"PRIVATE")),J(rn,vr,e),J(rn,vr,"esm2020"),J("fire-js","")}wu("");var Eu="firebase",Su="12.12.1";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */J(Eu,Su,"app");const Bi="@firebase/installations",Rn="0.6.21";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Fi=1e4,$i=`w:${Rn}`,Li="FIS_v2",Au="https://firebaseinstallations.googleapis.com/v1",xu=3600*1e3,vu="installations",Tu="Installations";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Cu={"missing-app-config-values":'Missing App configuration value: "{$valueName}"',"not-registered":"Firebase Installation is not registered.","installation-not-found":"Firebase Installation not found.","request-failed":'{$requestName} request failed with error "{$serverCode} {$serverStatus}: {$serverMessage}"',"app-offline":"Could not process request. Application offline.","delete-pending-registration":"Can't delete installation while there is a pending registration request."},de=new bt(vu,Tu,Cu);function ji(e){return e instanceof Se&&e.code.includes("request-failed")}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Hi({projectId:e}){return`${Au}/projects/${e}/installations`}function Ui(e){return{token:e.token,requestStatus:2,expiresIn:Iu(e.expiresIn),creationTime:Date.now()}}async function qi(e,t){const r=(await t.json()).error;return de.create("request-failed",{requestName:e,serverCode:r.code,serverMessage:r.message,serverStatus:r.status})}function Ki({apiKey:e}){return new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":e})}function Ou(e,{refreshToken:t}){const n=Ki(e);return n.append("Authorization",Ru(t)),n}async function Vi(e){const t=await e();return t.status>=500&&t.status<600?e():t}function Iu(e){return Number(e.replace("s","000"))}function Ru(e){return`${Li} ${e}`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Du({appConfig:e,heartbeatServiceProvider:t},{fid:n}){const r=Hi(e),i=Ki(e),s=t.getImmediate({optional:!0});if(s){const u=await s.getHeartbeatsHeader();u&&i.append("x-firebase-client",u)}const o={fid:n,authVersion:Li,appId:e.appId,sdkVersion:$i},a={method:"POST",headers:i,body:JSON.stringify(o)},c=await Vi(()=>fetch(r,a));if(c.ok){const u=await c.json();return{fid:u.fid||n,registrationStatus:2,refreshToken:u.refreshToken,authToken:Ui(u.authToken)}}else throw await qi("Create Installation",c)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function zi(e){return new Promise(t=>{setTimeout(t,e)})}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function ku(e){return btoa(String.fromCharCode(...e)).replace(/\+/g,"-").replace(/\//g,"_")}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Nu=/^[cdef][\w-]{21}$/,an="";function Pu(){try{const e=new Uint8Array(17);(self.crypto||self.msCrypto).getRandomValues(e),e[0]=112+e[0]%16;const n=Mu(e);return Nu.test(n)?n:an}catch{return an}}function Mu(e){return ku(e).substr(0,22)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function wt(e){return`${e.appName}!${e.appId}`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Wi=new Map;function Ji(e,t){const n=wt(e);Gi(n,t),Bu(n,t)}function Gi(e,t){const n=Wi.get(e);if(n)for(const r of n)r(t)}function Bu(e,t){const n=Fu();n&&n.postMessage({key:e,fid:t}),$u()}let re=null;function Fu(){return!re&&"BroadcastChannel"in self&&(re=new BroadcastChannel("[Firebase] FID Change"),re.onmessage=e=>{Gi(e.data.key,e.data.fid)}),re}function $u(){Wi.size===0&&re&&(re.close(),re=null)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Lu="firebase-installations-database",ju=1,pe="firebase-installations-store";let Ft=null;function Dn(){return Ft||(Ft=yt(Lu,ju,{upgrade:(e,t)=>{switch(t){case 0:e.createObjectStore(pe)}}})),Ft}async function ot(e,t){const n=wt(e),i=(await Dn()).transaction(pe,"readwrite"),s=i.objectStore(pe),o=await s.get(n);return await s.put(t,n),await i.done,(!o||o.fid!==t.fid)&&Ji(e,t.fid),t}async function Xi(e){const t=wt(e),r=(await Dn()).transaction(pe,"readwrite");await r.objectStore(pe).delete(t),await r.done}async function Et(e,t){const n=wt(e),i=(await Dn()).transaction(pe,"readwrite"),s=i.objectStore(pe),o=await s.get(n),a=t(o);return a===void 0?await s.delete(n):await s.put(a,n),await i.done,a&&(!o||o.fid!==a.fid)&&Ji(e,a.fid),a}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function kn(e){let t;const n=await Et(e.appConfig,r=>{const i=Hu(r),s=Uu(e,i);return t=s.registrationPromise,s.installationEntry});return n.fid===an?{installationEntry:await t}:{installationEntry:n,registrationPromise:t}}function Hu(e){const t=e||{fid:Pu(),registrationStatus:0};return Yi(t)}function Uu(e,t){if(t.registrationStatus===0){if(!navigator.onLine){const i=Promise.reject(de.create("app-offline"));return{installationEntry:t,registrationPromise:i}}const n={fid:t.fid,registrationStatus:1,registrationTime:Date.now()},r=qu(e,n);return{installationEntry:n,registrationPromise:r}}else return t.registrationStatus===1?{installationEntry:t,registrationPromise:Ku(e)}:{installationEntry:t}}async function qu(e,t){try{const n=await Du(e,t);return ot(e.appConfig,n)}catch(n){throw ji(n)&&n.customData.serverCode===409?await Xi(e.appConfig):await ot(e.appConfig,{fid:t.fid,registrationStatus:0}),n}}async function Ku(e){let t=await Rr(e.appConfig);for(;t.registrationStatus===1;)await zi(100),t=await Rr(e.appConfig);if(t.registrationStatus===0){const{installationEntry:n,registrationPromise:r}=await kn(e);return r||n}return t}function Rr(e){return Et(e,t=>{if(!t)throw de.create("installation-not-found");return Yi(t)})}function Yi(e){return Vu(e)?{fid:e.fid,registrationStatus:0}:e}function Vu(e){return e.registrationStatus===1&&e.registrationTime+Fi<Date.now()}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function zu({appConfig:e,heartbeatServiceProvider:t},n){const r=Wu(e,n),i=Ou(e,n),s=t.getImmediate({optional:!0});if(s){const u=await s.getHeartbeatsHeader();u&&i.append("x-firebase-client",u)}const o={installation:{sdkVersion:$i,appId:e.appId}},a={method:"POST",headers:i,body:JSON.stringify(o)},c=await Vi(()=>fetch(r,a));if(c.ok){const u=await c.json();return Ui(u)}else throw await qi("Generate Auth Token",c)}function Wu(e,{fid:t}){return`${Hi(e)}/${t}/authTokens:generate`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Nn(e,t=!1){let n;const r=await Et(e.appConfig,s=>{if(!Zi(s))throw de.create("not-registered");const o=s.authToken;if(!t&&Xu(o))return s;if(o.requestStatus===1)return n=Ju(e,t),s;{if(!navigator.onLine)throw de.create("app-offline");const a=Zu(s);return n=Gu(e,a),a}});return n?await n:r.authToken}async function Ju(e,t){let n=await Dr(e.appConfig);for(;n.authToken.requestStatus===1;)await zi(100),n=await Dr(e.appConfig);const r=n.authToken;return r.requestStatus===0?Nn(e,t):r}function Dr(e){return Et(e,t=>{if(!Zi(t))throw de.create("not-registered");const n=t.authToken;return Qu(n)?{...t,authToken:{requestStatus:0}}:t})}async function Gu(e,t){try{const n=await zu(e,t),r={...t,authToken:n};return await ot(e.appConfig,r),n}catch(n){if(ji(n)&&(n.customData.serverCode===401||n.customData.serverCode===404))await Xi(e.appConfig);else{const r={...t,authToken:{requestStatus:0}};await ot(e.appConfig,r)}throw n}}function Zi(e){return e!==void 0&&e.registrationStatus===2}function Xu(e){return e.requestStatus===2&&!Yu(e)}function Yu(e){const t=Date.now();return t<e.creationTime||e.creationTime+e.expiresIn<t+xu}function Zu(e){const t={requestStatus:1,requestTime:Date.now()};return{...e,authToken:t}}function Qu(e){return e.requestStatus===1&&e.requestTime+Fi<Date.now()}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function el(e){const t=e,{installationEntry:n,registrationPromise:r}=await kn(t);return r?r.catch(console.error):Nn(t).catch(console.error),n.fid}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function tl(e,t=!1){const n=e;return await nl(n),(await Nn(n,t)).token}async function nl(e){const{registrationPromise:t}=await kn(e);t&&await t}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function rl(e){if(!e||!e.options)throw $t("App Configuration");if(!e.name)throw $t("App Name");const t=["projectId","apiKey","appId"];for(const n of t)if(!e.options[n])throw $t(n);return{appName:e.name,projectId:e.options.projectId,apiKey:e.options.apiKey,appId:e.options.appId}}function $t(e){return de.create("missing-app-config-values",{valueName:e})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Qi="installations",il="installations-internal",sl=e=>{const t=e.getProvider("app").getImmediate(),n=rl(t),r=In(t,"heartbeat");return{app:t,appConfig:n,heartbeatServiceProvider:r,_delete:()=>Promise.resolve()}},ol=e=>{const t=e.getProvider("app").getImmediate(),n=In(t,Qi).getImmediate();return{getId:()=>el(n),getToken:i=>tl(n,i)}};function al(){fe(new X(Qi,sl,"PUBLIC")),fe(new X(il,ol,"PRIVATE"))}al();J(Bi,Rn);J(Bi,Rn,"esm2020");/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const cl="/firebase-messaging-sw.js",ul="/firebase-cloud-messaging-push-scope",es="BDOU99-h67HcA6JeFXHbSNMu7e2yNNu3RzoMj8TM4W88jITfq7ZmPvIM1Iv-4_l2LxQcYwhqby2xGpWwzjfAnG4",ll="https://fcmregistrations.googleapis.com/v1",ts="google.c.a.c_id",fl="google.c.a.c_l",dl="google.c.a.ts",pl="google.c.a.e",kr=1e4;var Nr;(function(e){e[e.DATA_MESSAGE=1]="DATA_MESSAGE",e[e.DISPLAY_NOTIFICATION=3]="DISPLAY_NOTIFICATION"})(Nr||(Nr={}));/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except
 * in compliance with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
 * or implied. See the License for the specific language governing permissions and limitations under
 * the License.
 */var Fe;(function(e){e.PUSH_RECEIVED="push-received",e.NOTIFICATION_CLICKED="notification-clicked"})(Fe||(Fe={}));/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function U(e){const t=new Uint8Array(e);return btoa(String.fromCharCode(...t)).replace(/=/g,"").replace(/\+/g,"-").replace(/\//g,"_")}function hl(e){const t="=".repeat((4-e.length%4)%4),n=(e+t).replace(/\-/g,"+").replace(/_/g,"/"),r=atob(n),i=new Uint8Array(r.length);for(let s=0;s<r.length;++s)i[s]=r.charCodeAt(s);return i}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Lt="fcm_token_details_db",gl=5,Pr="fcm_token_object_Store";async function ml(e){if("databases"in indexedDB&&!(await indexedDB.databases()).map(s=>s.name).includes(Lt))return null;let t=null;return(await yt(Lt,gl,{upgrade:async(r,i,s,o)=>{if(i<2||!r.objectStoreNames.contains(Pr))return;const a=o.objectStore(Pr),c=await a.index("fcmSenderId").get(e);if(await a.clear(),!!c){if(i===2){const u=c;if(!u.auth||!u.p256dh||!u.endpoint)return;t={token:u.fcmToken,createTime:u.createTime??Date.now(),subscriptionOptions:{auth:u.auth,p256dh:u.p256dh,endpoint:u.endpoint,swScope:u.swScope,vapidKey:typeof u.vapidKey=="string"?u.vapidKey:U(u.vapidKey)}}}else if(i===3){const u=c;t={token:u.fcmToken,createTime:u.createTime,subscriptionOptions:{auth:U(u.auth),p256dh:U(u.p256dh),endpoint:u.endpoint,swScope:u.swScope,vapidKey:U(u.vapidKey)}}}else if(i===4){const u=c;t={token:u.fcmToken,createTime:u.createTime,subscriptionOptions:{auth:U(u.auth),p256dh:U(u.p256dh),endpoint:u.endpoint,swScope:u.swScope,vapidKey:U(u.vapidKey)}}}}}})).close(),await Pt(Lt),await Pt("fcm_vapid_details_db"),await Pt("undefined"),bl(t)?t:null}function bl(e){if(!e||!e.subscriptionOptions)return!1;const{subscriptionOptions:t}=e;return typeof e.createTime=="number"&&e.createTime>0&&typeof e.token=="string"&&e.token.length>0&&typeof t.auth=="string"&&t.auth.length>0&&typeof t.p256dh=="string"&&t.p256dh.length>0&&typeof t.endpoint=="string"&&t.endpoint.length>0&&typeof t.swScope=="string"&&t.swScope.length>0&&typeof t.vapidKey=="string"&&t.vapidKey.length>0}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const _l="firebase-messaging-database",yl=1,he="firebase-messaging-store";let jt=null;function Pn(){return jt||(jt=yt(_l,yl,{upgrade:(e,t)=>{switch(t){case 0:e.createObjectStore(he)}}})),jt}async function ns(e){const t=Bn(e),r=await(await Pn()).transaction(he).objectStore(he).get(t);if(r)return r;{const i=await ml(e.appConfig.senderId);if(i)return await Mn(e,i),i}}async function Mn(e,t){const n=Bn(e),i=(await Pn()).transaction(he,"readwrite");return await i.objectStore(he).put(t,n),await i.done,t}async function wl(e){const t=Bn(e),r=(await Pn()).transaction(he,"readwrite");await r.objectStore(he).delete(t),await r.done}function Bn({appConfig:e}){return e.appId}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const El={"missing-app-config-values":'Missing App configuration value: "{$valueName}"',"only-available-in-window":"This method is available in a Window context.","only-available-in-sw":"This method is available in a service worker context.","permission-default":"The notification permission was not granted and dismissed instead.","permission-blocked":"The notification permission was not granted and blocked instead.","unsupported-browser":"This browser doesn't support the API's required to use the Firebase SDK.","indexed-db-unsupported":"This browser doesn't support indexedDb.open() (ex. Safari iFrame, Firefox Private Browsing, etc)","failed-service-worker-registration":"We are unable to register the default service worker. {$browserErrorMessage}","token-subscribe-failed":"A problem occurred while subscribing the user to FCM: {$errorInfo}","token-subscribe-no-token":"FCM returned no token when subscribing the user to push.","token-unsubscribe-failed":"A problem occurred while unsubscribing the user from FCM: {$errorInfo}","token-update-failed":"A problem occurred while updating the user from FCM: {$errorInfo}","token-update-no-token":"FCM returned no token when updating the user to push.","use-sw-after-get-token":"The useServiceWorker() method may only be called once and must be called before calling getToken() to ensure your service worker is used.","invalid-sw-registration":"The input to useServiceWorker() must be a ServiceWorkerRegistration.","invalid-bg-handler":"The input to setBackgroundMessageHandler() must be a function.","invalid-vapid-key":"The public VAPID key must be a string.","use-vapid-key-after-get-token":"The usePublicVapidKey() method may only be called once and must be called before calling getToken() to ensure your VAPID key is used."},I=new bt("messaging","Messaging",El);/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Sl(e,t){const n=await $n(e),r=is(t),i={method:"POST",headers:n,body:JSON.stringify(r)};let s;try{s=await(await fetch(Fn(e.appConfig),i)).json()}catch(o){throw I.create("token-subscribe-failed",{errorInfo:o?.toString()})}if(s.error){const o=s.error.message;throw I.create("token-subscribe-failed",{errorInfo:o})}if(!s.token)throw I.create("token-subscribe-no-token");return s.token}async function Al(e,t){const n=await $n(e),r=is(t.subscriptionOptions),i={method:"PATCH",headers:n,body:JSON.stringify(r)};let s;try{s=await(await fetch(`${Fn(e.appConfig)}/${t.token}`,i)).json()}catch(o){throw I.create("token-update-failed",{errorInfo:o?.toString()})}if(s.error){const o=s.error.message;throw I.create("token-update-failed",{errorInfo:o})}if(!s.token)throw I.create("token-update-no-token");return s.token}async function rs(e,t){const r={method:"DELETE",headers:await $n(e)};try{const s=await(await fetch(`${Fn(e.appConfig)}/${t}`,r)).json();if(s.error){const o=s.error.message;throw I.create("token-unsubscribe-failed",{errorInfo:o})}}catch(i){throw I.create("token-unsubscribe-failed",{errorInfo:i?.toString()})}}function Fn({projectId:e}){return`${ll}/projects/${e}/registrations`}async function $n({appConfig:e,installations:t}){const n=await t.getToken();return new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":e.apiKey,"x-goog-firebase-installations-auth":`FIS ${n}`})}function is({p256dh:e,auth:t,endpoint:n,vapidKey:r}){const i={web:{endpoint:n,auth:t,p256dh:e}};return r!==es&&(i.web.applicationPubKey=r),i}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const xl=10080*60*1e3;async function vl(e){const t=await Ol(e.swRegistration,e.vapidKey),n={vapidKey:e.vapidKey,swScope:e.swRegistration.scope,endpoint:t.endpoint,auth:U(t.getKey("auth")),p256dh:U(t.getKey("p256dh"))},r=await ns(e.firebaseDependencies);if(r){if(Il(r.subscriptionOptions,n))return Date.now()>=r.createTime+xl?Cl(e,{token:r.token,createTime:Date.now(),subscriptionOptions:n}):r.token;try{await rs(e.firebaseDependencies,r.token)}catch(i){console.warn(i)}return Mr(e.firebaseDependencies,n)}else return Mr(e.firebaseDependencies,n)}async function Tl(e){const t=await ns(e.firebaseDependencies);t&&(await rs(e.firebaseDependencies,t.token),await wl(e.firebaseDependencies));const n=await e.swRegistration.pushManager.getSubscription();return n?n.unsubscribe():!0}async function Cl(e,t){try{const n=await Al(e.firebaseDependencies,t),r={...t,token:n,createTime:Date.now()};return await Mn(e.firebaseDependencies,r),n}catch(n){throw n}}async function Mr(e,t){const r={token:await Sl(e,t),createTime:Date.now(),subscriptionOptions:t};return await Mn(e,r),r.token}async function Ol(e,t){const n=await e.pushManager.getSubscription();return n||e.pushManager.subscribe({userVisibleOnly:!0,applicationServerKey:hl(t)})}function Il(e,t){const n=t.vapidKey===e.vapidKey,r=t.endpoint===e.endpoint,i=t.auth===e.auth,s=t.p256dh===e.p256dh;return n&&r&&i&&s}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Br(e){const t={from:e.from,collapseKey:e.collapse_key,messageId:e.fcmMessageId};return Rl(t,e),Dl(t,e),kl(t,e),t}function Rl(e,t){if(!t.notification)return;e.notification={};const n=t.notification.title;n&&(e.notification.title=n);const r=t.notification.body;r&&(e.notification.body=r);const i=t.notification.image;i&&(e.notification.image=i);const s=t.notification.icon;s&&(e.notification.icon=s)}function Dl(e,t){t.data&&(e.data=t.data)}function kl(e,t){if(!t.fcmOptions&&!t.notification?.click_action)return;e.fcmOptions={};const n=t.fcmOptions?.link??t.notification?.click_action;n&&(e.fcmOptions.link=n);const r=t.fcmOptions?.analytics_label;r&&(e.fcmOptions.analyticsLabel=r)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Nl(e){return typeof e=="object"&&!!e&&ts in e}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Pl(e){if(!e||!e.options)throw Ht("App Configuration Object");if(!e.name)throw Ht("App Name");const t=["projectId","apiKey","appId","messagingSenderId"],{options:n}=e;for(const r of t)if(!n[r])throw Ht(r);return{appName:e.name,projectId:n.projectId,apiKey:n.apiKey,appId:n.appId,senderId:n.messagingSenderId}}function Ht(e){return I.create("missing-app-config-values",{valueName:e})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Ml{constructor(t,n,r){this.deliveryMetricsExportedToBigQueryEnabled=!1,this.onBackgroundMessageHandler=null,this.onMessageHandler=null,this.logEvents=[],this.isLogServiceStarted=!1;const i=Pl(t);this.firebaseDependencies={app:t,appConfig:i,installations:n,analyticsProvider:r}}_delete(){return Promise.resolve()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function ss(e){try{e.swRegistration=await navigator.serviceWorker.register(cl,{scope:ul}),e.swRegistration.update().catch(()=>{}),await Bl(e.swRegistration)}catch(t){throw I.create("failed-service-worker-registration",{browserErrorMessage:t?.message})}}async function Bl(e){return new Promise((t,n)=>{const r=setTimeout(()=>n(new Error(`Service worker not registered after ${kr} ms`)),kr),i=e.installing||e.waiting;e.active?(clearTimeout(r),t()):i?i.onstatechange=s=>{s.target?.state==="activated"&&(i.onstatechange=null,clearTimeout(r),t())}:(clearTimeout(r),n(new Error("No incoming service worker found.")))})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Fl(e,t){if(!t&&!e.swRegistration&&await ss(e),!(!t&&e.swRegistration)){if(!(t instanceof ServiceWorkerRegistration))throw I.create("invalid-sw-registration");e.swRegistration=t}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function $l(e,t){t?e.vapidKey=t:e.vapidKey||(e.vapidKey=es)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function os(e,t){if(!navigator)throw I.create("only-available-in-window");if(Notification.permission==="default"&&await Notification.requestPermission(),Notification.permission!=="granted")throw I.create("permission-blocked");return await $l(e,t?.vapidKey),await Fl(e,t?.serviceWorkerRegistration),vl(e)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Ll(e,t,n){const r=jl(t);(await e.firebaseDependencies.analyticsProvider.get()).logEvent(r,{message_id:n[ts],message_name:n[fl],message_time:n[dl],message_device_time:Math.floor(Date.now()/1e3)})}function jl(e){switch(e){case Fe.NOTIFICATION_CLICKED:return"notification_open";case Fe.PUSH_RECEIVED:return"notification_foreground";default:throw new Error}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Hl(e,t){const n=t.data;if(!n.isFirebaseMessaging)return;e.onMessageHandler&&n.messageType===Fe.PUSH_RECEIVED&&(typeof e.onMessageHandler=="function"?e.onMessageHandler(Br(n)):e.onMessageHandler.next(Br(n)));const r=n.data;Nl(r)&&r[pl]==="1"&&await Ll(e,n.messageType,r)}const Fr="@firebase/messaging",$r="0.12.25";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Ul=e=>{const t=new Ml(e.getProvider("app").getImmediate(),e.getProvider("installations-internal").getImmediate(),e.getProvider("analytics-internal"));return navigator.serviceWorker.addEventListener("message",n=>Hl(t,n)),t},ql=e=>{const t=e.getProvider("messaging").getImmediate();return{getToken:r=>os(t,r)}};function Kl(){fe(new X("messaging",Ul,"PUBLIC")),fe(new X("messaging-internal",ql,"PRIVATE")),J(Fr,$r),J(Fr,$r,"esm2020")}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function as(){try{await Ri()}catch{return!1}return typeof window<"u"&&Ii()&&uc()&&"serviceWorker"in navigator&&"PushManager"in window&&"Notification"in window&&"fetch"in window&&ServiceWorkerRegistration.prototype.hasOwnProperty("showNotification")&&PushSubscription.prototype.hasOwnProperty("getKey")}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Vl(e){if(!navigator)throw I.create("only-available-in-window");return e.swRegistration||await ss(e),Tl(e)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function zl(e,t){if(!navigator)throw I.create("only-available-in-window");return e.onMessageHandler=t,()=>{e.onMessageHandler=null}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Wl(e=lu()){return as().then(t=>{if(!t)throw I.create("unsupported-browser")},t=>{throw I.create("indexed-db-unsupported")}),In(_t(e),"messaging").getImmediate()}async function cs(e,t){return e=_t(e),os(e,t)}function Jl(e){return e=_t(e),Vl(e)}function Gl(e,t){return e=_t(e),zl(e,t)}Kl();const Xl={apiKey:"AIzaSyCp_JHo6eRkpwDKQkzpSSwyr06Xrp-reAc",authDomain:"sakadomas-f4e73.firebaseapp.com",projectId:"sakadomas-f4e73",storageBucket:"sakadomas-f4e73.firebasestorage.app",messagingSenderId:"583286260614",appId:"1:583286260614:web:467bbb0ea2277d8dfe2207"};let G=null,Ut=null;const Yl=async()=>{if(G)return!0;try{if(!await as())return console.warn("Firebase Messaging is not supported in this browser."),!1;const t=Ni(Xl);return G=Wl(t),Ut&&Ut(),Ut=Gl(G,n=>{console.log("[Firebase] Foreground message received:",n);const r={...n,notification:{title:n.data?.title||n.notification?.title||"",body:n.data?.body||n.notification?.body||""}};window.dispatchEvent(new CustomEvent("fcm-message",{detail:r}))}),!0}catch(e){return console.error("Error initializing Firebase:",e),!1}},Zl=async()=>{if(!G)return!1;try{if(await Notification.requestPermission()!=="granted")return!1;const t=await cs(G,{vapidKey:"BBn50LvGmFLKVJaxRHa1jwQkgxw0acougxpuJVhpRcjMrEAnykahJlvNzs2io6moLSDvJoPsxxHzBZPpSdl_eK0"});return t?(await v.post("/api/fcm/register",{token:t}),!0):!1}catch(e){return console.error("Error getting FCM token:",e),!1}},Ql=async()=>{if(!G)return!1;try{const e=await cs(G,{vapidKey:"BBn50LvGmFLKVJaxRHa1jwQkgxw0acougxpuJVhpRcjMrEAnykahJlvNzs2io6moLSDvJoPsxxHzBZPpSdl_eK0"});return e?(await Jl(G),await v.post("/api/fcm/remove",{token:e}),!0):!1}catch(e){return console.error("Error removing FCM token:",e),!1}};window.initFirebase=Yl;window.requestPermissionAndToken=Zl;window.removeToken=Ql;var cn=!1,un=!1,oe=[],ln=-1;function ef(e){tf(e)}function tf(e){oe.includes(e)||oe.push(e),rf()}function nf(e){let t=oe.indexOf(e);t!==-1&&t>ln&&oe.splice(t,1)}function rf(){!un&&!cn&&(cn=!0,queueMicrotask(sf))}function sf(){cn=!1,un=!0;for(let e=0;e<oe.length;e++)oe[e](),ln=e;oe.length=0,ln=-1,un=!1}var Ae,me,xe,us,fn=!0;function of(e){fn=!1,e(),fn=!0}function af(e){Ae=e.reactive,xe=e.release,me=t=>e.effect(t,{scheduler:n=>{fn?ef(n):n()}}),us=e.raw}function Lr(e){me=e}function cf(e){let t=()=>{};return[r=>{let i=me(r);return e._x_effects||(e._x_effects=new Set,e._x_runEffects=()=>{e._x_effects.forEach(s=>s())}),e._x_effects.add(i),t=()=>{i!==void 0&&(e._x_effects.delete(i),xe(i))},i},()=>{t()}]}function ls(e,t){let n=!0,r,i=me(()=>{let s=e();JSON.stringify(s),n?r=s:queueMicrotask(()=>{t(s,r),r=s}),n=!1});return()=>xe(i)}var fs=[],ds=[],ps=[];function uf(e){ps.push(e)}function Ln(e,t){typeof t=="function"?(e._x_cleanups||(e._x_cleanups=[]),e._x_cleanups.push(t)):(t=e,ds.push(t))}function hs(e){fs.push(e)}function gs(e,t,n){e._x_attributeCleanups||(e._x_attributeCleanups={}),e._x_attributeCleanups[t]||(e._x_attributeCleanups[t]=[]),e._x_attributeCleanups[t].push(n)}function ms(e,t){e._x_attributeCleanups&&Object.entries(e._x_attributeCleanups).forEach(([n,r])=>{(t===void 0||t.includes(n))&&(r.forEach(i=>i()),delete e._x_attributeCleanups[n])})}function lf(e){for(e._x_effects?.forEach(nf);e._x_cleanups?.length;)e._x_cleanups.pop()()}var jn=new MutationObserver(Kn),Hn=!1;function Un(){jn.observe(document,{subtree:!0,childList:!0,attributes:!0,attributeOldValue:!0}),Hn=!0}function bs(){ff(),jn.disconnect(),Hn=!1}var Ie=[];function ff(){let e=jn.takeRecords();Ie.push(()=>e.length>0&&Kn(e));let t=Ie.length;queueMicrotask(()=>{if(Ie.length===t)for(;Ie.length>0;)Ie.shift()()})}function x(e){if(!Hn)return e();bs();let t=e();return Un(),t}var qn=!1,at=[];function df(){qn=!0}function pf(){qn=!1,Kn(at),at=[]}function Kn(e){if(qn){at=at.concat(e);return}let t=[],n=new Set,r=new Map,i=new Map;for(let s=0;s<e.length;s++)if(!e[s].target._x_ignoreMutationObserver&&(e[s].type==="childList"&&(e[s].removedNodes.forEach(o=>{o.nodeType===1&&o._x_marker&&n.add(o)}),e[s].addedNodes.forEach(o=>{if(o.nodeType===1){if(n.has(o)){n.delete(o);return}o._x_marker||t.push(o)}})),e[s].type==="attributes")){let o=e[s].target,a=e[s].attributeName,c=e[s].oldValue,u=()=>{r.has(o)||r.set(o,[]),r.get(o).push({name:a,value:o.getAttribute(a)})},l=()=>{i.has(o)||i.set(o,[]),i.get(o).push(a)};o.hasAttribute(a)&&c===null?u():o.hasAttribute(a)?(l(),u()):l()}i.forEach((s,o)=>{ms(o,s)}),r.forEach((s,o)=>{fs.forEach(a=>a(o,s))});for(let s of n)t.some(o=>o.contains(s))||ds.forEach(o=>o(s));for(let s of t)s.isConnected&&ps.forEach(o=>o(s));t=null,n=null,r=null,i=null}function _s(e){return Ke(_e(e))}function qe(e,t,n){return e._x_dataStack=[t,..._e(n||e)],()=>{e._x_dataStack=e._x_dataStack.filter(r=>r!==t)}}function _e(e){return e._x_dataStack?e._x_dataStack:typeof ShadowRoot=="function"&&e instanceof ShadowRoot?_e(e.host):e.parentNode?_e(e.parentNode):[]}function Ke(e){return new Proxy({objects:e},hf)}var hf={ownKeys({objects:e}){return Array.from(new Set(e.flatMap(t=>Object.keys(t))))},has({objects:e},t){return t==Symbol.unscopables?!1:e.some(n=>Object.prototype.hasOwnProperty.call(n,t)||Reflect.has(n,t))},get({objects:e},t,n){return t=="toJSON"?gf:Reflect.get(e.find(r=>Reflect.has(r,t))||{},t,n)},set({objects:e},t,n,r){const i=e.find(o=>Object.prototype.hasOwnProperty.call(o,t))||e[e.length-1],s=Object.getOwnPropertyDescriptor(i,t);return s?.set&&s?.get?s.set.call(r,n)||!0:Reflect.set(i,t,n)}};function gf(){return Reflect.ownKeys(this).reduce((t,n)=>(t[n]=Reflect.get(this,n),t),{})}function ys(e){let t=r=>typeof r=="object"&&!Array.isArray(r)&&r!==null,n=(r,i="")=>{Object.entries(Object.getOwnPropertyDescriptors(r)).forEach(([s,{value:o,enumerable:a}])=>{if(a===!1||o===void 0||typeof o=="object"&&o!==null&&o.__v_skip)return;let c=i===""?s:`${i}.${s}`;typeof o=="object"&&o!==null&&o._x_interceptor?r[s]=o.initialize(e,c,s):t(o)&&o!==r&&!(o instanceof Element)&&n(o,c)})};return n(e)}function ws(e,t=()=>{}){let n={initialValue:void 0,_x_interceptor:!0,initialize(r,i,s){return e(this.initialValue,()=>mf(r,i),o=>dn(r,i,o),i,s)}};return t(n),r=>{if(typeof r=="object"&&r!==null&&r._x_interceptor){let i=n.initialize.bind(n);n.initialize=(s,o,a)=>{let c=r.initialize(s,o,a);return n.initialValue=c,i(s,o,a)}}else n.initialValue=r;return n}}function mf(e,t){return t.split(".").reduce((n,r)=>n[r],e)}function dn(e,t,n){if(typeof t=="string"&&(t=t.split(".")),t.length===1)e[t[0]]=n;else{if(t.length===0)throw error;return e[t[0]]||(e[t[0]]={}),dn(e[t[0]],t.slice(1),n)}}var Es={};function $(e,t){Es[e]=t}function pn(e,t){let n=bf(t);return Object.entries(Es).forEach(([r,i])=>{Object.defineProperty(e,`$${r}`,{get(){return i(t,n)},enumerable:!1})}),e}function bf(e){let[t,n]=Cs(e),r={interceptor:ws,...t};return Ln(e,n),r}function _f(e,t,n,...r){try{return n(...r)}catch(i){$e(i,e,t)}}function $e(e,t,n=void 0){e=Object.assign(e??{message:"No error message given."},{el:t,expression:n}),console.warn(`Alpine Expression Error: ${e.message}

${n?'Expression: "'+n+`"

`:""}`,t),setTimeout(()=>{throw e},0)}var tt=!0;function Ss(e){let t=tt;tt=!1;let n=e();return tt=t,n}function ae(e,t,n={}){let r;return D(e,t)(i=>r=i,n),r}function D(...e){return As(...e)}var As=xs;function yf(e){As=e}function xs(e,t){let n={};pn(n,e);let r=[n,..._e(e)],i=typeof t=="function"?wf(r,t):Sf(r,t,e);return _f.bind(null,e,t,i)}function wf(e,t){return(n=()=>{},{scope:r={},params:i=[]}={})=>{let s=t.apply(Ke([r,...e]),i);ct(n,s)}}var qt={};function Ef(e,t){if(qt[e])return qt[e];let n=Object.getPrototypeOf(async function(){}).constructor,r=/^[\n\s]*if.*\(.*\)/.test(e.trim())||/^(let|const)\s/.test(e.trim())?`(async()=>{ ${e} })()`:e,s=(()=>{try{let o=new n(["__self","scope"],`with (scope) { __self.result = ${r} }; __self.finished = true; return __self.result;`);return Object.defineProperty(o,"name",{value:`[Alpine] ${e}`}),o}catch(o){return $e(o,t,e),Promise.resolve()}})();return qt[e]=s,s}function Sf(e,t,n){let r=Ef(t,n);return(i=()=>{},{scope:s={},params:o=[]}={})=>{r.result=void 0,r.finished=!1;let a=Ke([s,...e]);if(typeof r=="function"){let c=r(r,a).catch(u=>$e(u,n,t));r.finished?(ct(i,r.result,a,o,n),r.result=void 0):c.then(u=>{ct(i,u,a,o,n)}).catch(u=>$e(u,n,t)).finally(()=>r.result=void 0)}}}function ct(e,t,n,r,i){if(tt&&typeof t=="function"){let s=t.apply(n,r);s instanceof Promise?s.then(o=>ct(e,o,n,r)).catch(o=>$e(o,i,t)):e(s)}else typeof t=="object"&&t instanceof Promise?t.then(s=>e(s)):e(t)}var Vn="x-";function ve(e=""){return Vn+e}function Af(e){Vn=e}var ut={};function O(e,t){return ut[e]=t,{before(n){if(!ut[n]){console.warn(String.raw`Cannot find directive \`${n}\`. \`${e}\` will use the default order of execution`);return}const r=ie.indexOf(n);ie.splice(r>=0?r:ie.indexOf("DEFAULT"),0,e)}}}function xf(e){return Object.keys(ut).includes(e)}function zn(e,t,n){if(t=Array.from(t),e._x_virtualDirectives){let s=Object.entries(e._x_virtualDirectives).map(([a,c])=>({name:a,value:c})),o=vs(s);s=s.map(a=>o.find(c=>c.name===a.name)?{name:`x-bind:${a.name}`,value:`"${a.value}"`}:a),t=t.concat(s)}let r={};return t.map(Rs((s,o)=>r[s]=o)).filter(ks).map(Cf(r,n)).sort(Of).map(s=>Tf(e,s))}function vs(e){return Array.from(e).map(Rs()).filter(t=>!ks(t))}var hn=!1,ke=new Map,Ts=Symbol();function vf(e){hn=!0;let t=Symbol();Ts=t,ke.set(t,[]);let n=()=>{for(;ke.get(t).length;)ke.get(t).shift()();ke.delete(t)},r=()=>{hn=!1,n()};e(n),r()}function Cs(e){let t=[],n=a=>t.push(a),[r,i]=cf(e);return t.push(i),[{Alpine:Ve,effect:r,cleanup:n,evaluateLater:D.bind(D,e),evaluate:ae.bind(ae,e)},()=>t.forEach(a=>a())]}function Tf(e,t){let n=()=>{},r=ut[t.type]||n,[i,s]=Cs(e);gs(e,t.original,s);let o=()=>{e._x_ignore||e._x_ignoreSelf||(r.inline&&r.inline(e,t,i),r=r.bind(r,e,t,i),hn?ke.get(Ts).push(r):r())};return o.runCleanups=s,o}var Os=(e,t)=>({name:n,value:r})=>(n.startsWith(e)&&(n=n.replace(e,t)),{name:n,value:r}),Is=e=>e;function Rs(e=()=>{}){return({name:t,value:n})=>{let{name:r,value:i}=Ds.reduce((s,o)=>o(s),{name:t,value:n});return r!==t&&e(r,t),{name:r,value:i}}}var Ds=[];function Wn(e){Ds.push(e)}function ks({name:e}){return Ns().test(e)}var Ns=()=>new RegExp(`^${Vn}([^:^.]+)\\b`);function Cf(e,t){return({name:n,value:r})=>{let i=n.match(Ns()),s=n.match(/:([a-zA-Z0-9\-_:]+)/),o=n.match(/\.[^.\]]+(?=[^\]]*$)/g)||[],a=t||e[n]||n;return{type:i?i[1]:null,value:s?s[1]:null,modifiers:o.map(c=>c.replace(".","")),expression:r,original:a}}}var gn="DEFAULT",ie=["ignore","ref","data","id","anchor","bind","init","for","model","modelable","transition","show","if",gn,"teleport"];function Of(e,t){let n=ie.indexOf(e.type)===-1?gn:e.type,r=ie.indexOf(t.type)===-1?gn:t.type;return ie.indexOf(n)-ie.indexOf(r)}function Ne(e,t,n={}){e.dispatchEvent(new CustomEvent(t,{detail:n,bubbles:!0,composed:!0,cancelable:!0}))}function ge(e,t){if(typeof ShadowRoot=="function"&&e instanceof ShadowRoot){Array.from(e.children).forEach(i=>ge(i,t));return}let n=!1;if(t(e,()=>n=!0),n)return;let r=e.firstElementChild;for(;r;)ge(r,t),r=r.nextElementSibling}function M(e,...t){console.warn(`Alpine Warning: ${e}`,...t)}var jr=!1;function If(){jr&&M("Alpine has already been initialized on this page. Calling Alpine.start() more than once can cause problems."),jr=!0,document.body||M("Unable to initialize. Trying to load Alpine before `<body>` is available. Did you forget to add `defer` in Alpine's `<script>` tag?"),Ne(document,"alpine:init"),Ne(document,"alpine:initializing"),Un(),uf(t=>V(t,ge)),Ln(t=>Ce(t)),hs((t,n)=>{zn(t,n).forEach(r=>r())});let e=t=>!St(t.parentElement,!0);Array.from(document.querySelectorAll(Bs().join(","))).filter(e).forEach(t=>{V(t)}),Ne(document,"alpine:initialized"),setTimeout(()=>{Nf()})}var Jn=[],Ps=[];function Ms(){return Jn.map(e=>e())}function Bs(){return Jn.concat(Ps).map(e=>e())}function Fs(e){Jn.push(e)}function $s(e){Ps.push(e)}function St(e,t=!1){return Te(e,n=>{if((t?Bs():Ms()).some(i=>n.matches(i)))return!0})}function Te(e,t){if(e){if(t(e))return e;if(e._x_teleportBack&&(e=e._x_teleportBack),!!e.parentElement)return Te(e.parentElement,t)}}function Rf(e){return Ms().some(t=>e.matches(t))}var Ls=[];function Df(e){Ls.push(e)}var kf=1;function V(e,t=ge,n=()=>{}){Te(e,r=>r._x_ignore)||vf(()=>{t(e,(r,i)=>{r._x_marker||(n(r,i),Ls.forEach(s=>s(r,i)),zn(r,r.attributes).forEach(s=>s()),r._x_ignore||(r._x_marker=kf++),r._x_ignore&&i())})})}function Ce(e,t=ge){t(e,n=>{lf(n),ms(n),delete n._x_marker})}function Nf(){[["ui","dialog",["[x-dialog], [x-popover]"]],["anchor","anchor",["[x-anchor]"]],["sort","sort",["[x-sort]"]]].forEach(([t,n,r])=>{xf(n)||r.some(i=>{if(document.querySelector(i))return M(`found "${i}", but missing ${t} plugin`),!0})})}var mn=[],Gn=!1;function Xn(e=()=>{}){return queueMicrotask(()=>{Gn||setTimeout(()=>{bn()})}),new Promise(t=>{mn.push(()=>{e(),t()})})}function bn(){for(Gn=!1;mn.length;)mn.shift()()}function Pf(){Gn=!0}function Yn(e,t){return Array.isArray(t)?Hr(e,t.join(" ")):typeof t=="object"&&t!==null?Mf(e,t):typeof t=="function"?Yn(e,t()):Hr(e,t)}function Hr(e,t){let n=i=>i.split(" ").filter(s=>!e.classList.contains(s)).filter(Boolean),r=i=>(e.classList.add(...i),()=>{e.classList.remove(...i)});return t=t===!0?t="":t||"",r(n(t))}function Mf(e,t){let n=a=>a.split(" ").filter(Boolean),r=Object.entries(t).flatMap(([a,c])=>c?n(a):!1).filter(Boolean),i=Object.entries(t).flatMap(([a,c])=>c?!1:n(a)).filter(Boolean),s=[],o=[];return i.forEach(a=>{e.classList.contains(a)&&(e.classList.remove(a),o.push(a))}),r.forEach(a=>{e.classList.contains(a)||(e.classList.add(a),s.push(a))}),()=>{o.forEach(a=>e.classList.add(a)),s.forEach(a=>e.classList.remove(a))}}function At(e,t){return typeof t=="object"&&t!==null?Bf(e,t):Ff(e,t)}function Bf(e,t){let n={};return Object.entries(t).forEach(([r,i])=>{n[r]=e.style[r],r.startsWith("--")||(r=$f(r)),e.style.setProperty(r,i)}),setTimeout(()=>{e.style.length===0&&e.removeAttribute("style")}),()=>{At(e,n)}}function Ff(e,t){let n=e.getAttribute("style",t);return e.setAttribute("style",t),()=>{e.setAttribute("style",n||"")}}function $f(e){return e.replace(/([a-z])([A-Z])/g,"$1-$2").toLowerCase()}function _n(e,t=()=>{}){let n=!1;return function(){n?t.apply(this,arguments):(n=!0,e.apply(this,arguments))}}O("transition",(e,{value:t,modifiers:n,expression:r},{evaluate:i})=>{typeof r=="function"&&(r=i(r)),r!==!1&&(!r||typeof r=="boolean"?jf(e,n,t):Lf(e,r,t))});function Lf(e,t,n){js(e,Yn,""),{enter:i=>{e._x_transition.enter.during=i},"enter-start":i=>{e._x_transition.enter.start=i},"enter-end":i=>{e._x_transition.enter.end=i},leave:i=>{e._x_transition.leave.during=i},"leave-start":i=>{e._x_transition.leave.start=i},"leave-end":i=>{e._x_transition.leave.end=i}}[n](t)}function jf(e,t,n){js(e,At);let r=!t.includes("in")&&!t.includes("out")&&!n,i=r||t.includes("in")||["enter"].includes(n),s=r||t.includes("out")||["leave"].includes(n);t.includes("in")&&!r&&(t=t.filter((m,w)=>w<t.indexOf("out"))),t.includes("out")&&!r&&(t=t.filter((m,w)=>w>t.indexOf("out")));let o=!t.includes("opacity")&&!t.includes("scale"),a=o||t.includes("opacity"),c=o||t.includes("scale"),u=a?0:1,l=c?Re(t,"scale",95)/100:1,d=Re(t,"delay",0)/1e3,h=Re(t,"origin","center"),_="opacity, transform",g=Re(t,"duration",150)/1e3,b=Re(t,"duration",75)/1e3,p="cubic-bezier(0.4, 0.0, 0.2, 1)";i&&(e._x_transition.enter.during={transformOrigin:h,transitionDelay:`${d}s`,transitionProperty:_,transitionDuration:`${g}s`,transitionTimingFunction:p},e._x_transition.enter.start={opacity:u,transform:`scale(${l})`},e._x_transition.enter.end={opacity:1,transform:"scale(1)"}),s&&(e._x_transition.leave.during={transformOrigin:h,transitionDelay:`${d}s`,transitionProperty:_,transitionDuration:`${b}s`,transitionTimingFunction:p},e._x_transition.leave.start={opacity:1,transform:"scale(1)"},e._x_transition.leave.end={opacity:u,transform:`scale(${l})`})}function js(e,t,n={}){e._x_transition||(e._x_transition={enter:{during:n,start:n,end:n},leave:{during:n,start:n,end:n},in(r=()=>{},i=()=>{}){yn(e,t,{during:this.enter.during,start:this.enter.start,end:this.enter.end},r,i)},out(r=()=>{},i=()=>{}){yn(e,t,{during:this.leave.during,start:this.leave.start,end:this.leave.end},r,i)}})}window.Element.prototype._x_toggleAndCascadeWithTransitions=function(e,t,n,r){const i=document.visibilityState==="visible"?requestAnimationFrame:setTimeout;let s=()=>i(n);if(t){e._x_transition&&(e._x_transition.enter||e._x_transition.leave)?e._x_transition.enter&&(Object.entries(e._x_transition.enter.during).length||Object.entries(e._x_transition.enter.start).length||Object.entries(e._x_transition.enter.end).length)?e._x_transition.in(n):s():e._x_transition?e._x_transition.in(n):s();return}e._x_hidePromise=e._x_transition?new Promise((o,a)=>{e._x_transition.out(()=>{},()=>o(r)),e._x_transitioning&&e._x_transitioning.beforeCancel(()=>a({isFromCancelledTransition:!0}))}):Promise.resolve(r),queueMicrotask(()=>{let o=Hs(e);o?(o._x_hideChildren||(o._x_hideChildren=[]),o._x_hideChildren.push(e)):i(()=>{let a=c=>{let u=Promise.all([c._x_hidePromise,...(c._x_hideChildren||[]).map(a)]).then(([l])=>l?.());return delete c._x_hidePromise,delete c._x_hideChildren,u};a(e).catch(c=>{if(!c.isFromCancelledTransition)throw c})})})};function Hs(e){let t=e.parentNode;if(t)return t._x_hidePromise?t:Hs(t)}function yn(e,t,{during:n,start:r,end:i}={},s=()=>{},o=()=>{}){if(e._x_transitioning&&e._x_transitioning.cancel(),Object.keys(n).length===0&&Object.keys(r).length===0&&Object.keys(i).length===0){s(),o();return}let a,c,u;Hf(e,{start(){a=t(e,r)},during(){c=t(e,n)},before:s,end(){a(),u=t(e,i)},after:o,cleanup(){c(),u()}})}function Hf(e,t){let n,r,i,s=_n(()=>{x(()=>{n=!0,r||t.before(),i||(t.end(),bn()),t.after(),e.isConnected&&t.cleanup(),delete e._x_transitioning})});e._x_transitioning={beforeCancels:[],beforeCancel(o){this.beforeCancels.push(o)},cancel:_n(function(){for(;this.beforeCancels.length;)this.beforeCancels.shift()();s()}),finish:s},x(()=>{t.start(),t.during()}),Pf(),requestAnimationFrame(()=>{if(n)return;let o=Number(getComputedStyle(e).transitionDuration.replace(/,.*/,"").replace("s",""))*1e3,a=Number(getComputedStyle(e).transitionDelay.replace(/,.*/,"").replace("s",""))*1e3;o===0&&(o=Number(getComputedStyle(e).animationDuration.replace("s",""))*1e3),x(()=>{t.before()}),r=!0,requestAnimationFrame(()=>{n||(x(()=>{t.end()}),bn(),setTimeout(e._x_transitioning.finish,o+a),i=!0)})})}function Re(e,t,n){if(e.indexOf(t)===-1)return n;const r=e[e.indexOf(t)+1];if(!r||t==="scale"&&isNaN(r))return n;if(t==="duration"||t==="delay"){let i=r.match(/([0-9]+)ms/);if(i)return i[1]}return t==="origin"&&["top","right","left","center","bottom"].includes(e[e.indexOf(t)+2])?[r,e[e.indexOf(t)+2]].join(" "):r}var Y=!1;function Q(e,t=()=>{}){return(...n)=>Y?t(...n):e(...n)}function Uf(e){return(...t)=>Y&&e(...t)}var Us=[];function xt(e){Us.push(e)}function qf(e,t){Us.forEach(n=>n(e,t)),Y=!0,qs(()=>{V(t,(n,r)=>{r(n,()=>{})})}),Y=!1}var wn=!1;function Kf(e,t){t._x_dataStack||(t._x_dataStack=e._x_dataStack),Y=!0,wn=!0,qs(()=>{Vf(t)}),Y=!1,wn=!1}function Vf(e){let t=!1;V(e,(r,i)=>{ge(r,(s,o)=>{if(t&&Rf(s))return o();t=!0,i(s,o)})})}function qs(e){let t=me;Lr((n,r)=>{let i=t(n);return xe(i),()=>{}}),e(),Lr(t)}function Ks(e,t,n,r=[]){switch(e._x_bindings||(e._x_bindings=Ae({})),e._x_bindings[t]=n,t=r.includes("camel")?Qf(t):t,t){case"value":zf(e,n);break;case"style":Jf(e,n);break;case"class":Wf(e,n);break;case"selected":case"checked":Gf(e,t,n);break;default:Vs(e,t,n);break}}function zf(e,t){if(Js(e))e.attributes.value===void 0&&(e.value=t),window.fromModel&&(typeof t=="boolean"?e.checked=nt(e.value)===t:e.checked=Ur(e.value,t));else if(Zn(e))Number.isInteger(t)?e.value=t:!Array.isArray(t)&&typeof t!="boolean"&&![null,void 0].includes(t)?e.value=String(t):Array.isArray(t)?e.checked=t.some(n=>Ur(n,e.value)):e.checked=!!t;else if(e.tagName==="SELECT")Zf(e,t);else{if(e.value===t)return;e.value=t===void 0?"":t}}function Wf(e,t){e._x_undoAddedClasses&&e._x_undoAddedClasses(),e._x_undoAddedClasses=Yn(e,t)}function Jf(e,t){e._x_undoAddedStyles&&e._x_undoAddedStyles(),e._x_undoAddedStyles=At(e,t)}function Gf(e,t,n){Vs(e,t,n),Yf(e,t,n)}function Vs(e,t,n){[null,void 0,!1].includes(n)&&td(t)?e.removeAttribute(t):(zs(t)&&(n=t),Xf(e,t,n))}function Xf(e,t,n){e.getAttribute(t)!=n&&e.setAttribute(t,n)}function Yf(e,t,n){e[t]!==n&&(e[t]=n)}function Zf(e,t){const n=[].concat(t).map(r=>r+"");Array.from(e.options).forEach(r=>{r.selected=n.includes(r.value)})}function Qf(e){return e.toLowerCase().replace(/-(\w)/g,(t,n)=>n.toUpperCase())}function Ur(e,t){return e==t}function nt(e){return[1,"1","true","on","yes",!0].includes(e)?!0:[0,"0","false","off","no",!1].includes(e)?!1:e?!!e:null}var ed=new Set(["allowfullscreen","async","autofocus","autoplay","checked","controls","default","defer","disabled","formnovalidate","inert","ismap","itemscope","loop","multiple","muted","nomodule","novalidate","open","playsinline","readonly","required","reversed","selected","shadowrootclonable","shadowrootdelegatesfocus","shadowrootserializable"]);function zs(e){return ed.has(e)}function td(e){return!["aria-pressed","aria-checked","aria-expanded","aria-selected"].includes(e)}function nd(e,t,n){return e._x_bindings&&e._x_bindings[t]!==void 0?e._x_bindings[t]:Ws(e,t,n)}function rd(e,t,n,r=!0){if(e._x_bindings&&e._x_bindings[t]!==void 0)return e._x_bindings[t];if(e._x_inlineBindings&&e._x_inlineBindings[t]!==void 0){let i=e._x_inlineBindings[t];return i.extract=r,Ss(()=>ae(e,i.expression))}return Ws(e,t,n)}function Ws(e,t,n){let r=e.getAttribute(t);return r===null?typeof n=="function"?n():n:r===""?!0:zs(t)?!![t,"true"].includes(r):r}function Zn(e){return e.type==="checkbox"||e.localName==="ui-checkbox"||e.localName==="ui-switch"}function Js(e){return e.type==="radio"||e.localName==="ui-radio"}function Gs(e,t){var n;return function(){var r=this,i=arguments,s=function(){n=null,e.apply(r,i)};clearTimeout(n),n=setTimeout(s,t)}}function Xs(e,t){let n;return function(){let r=this,i=arguments;n||(e.apply(r,i),n=!0,setTimeout(()=>n=!1,t))}}function Ys({get:e,set:t},{get:n,set:r}){let i=!0,s,o=me(()=>{let a=e(),c=n();if(i)r(Kt(a)),i=!1;else{let u=JSON.stringify(a),l=JSON.stringify(c);u!==s?r(Kt(a)):u!==l&&t(Kt(c))}s=JSON.stringify(e()),JSON.stringify(n())});return()=>{xe(o)}}function Kt(e){return typeof e=="object"?JSON.parse(JSON.stringify(e)):e}function id(e){(Array.isArray(e)?e:[e]).forEach(n=>n(Ve))}var te={},qr=!1;function sd(e,t){if(qr||(te=Ae(te),qr=!0),t===void 0)return te[e];te[e]=t,ys(te[e]),typeof t=="object"&&t!==null&&t.hasOwnProperty("init")&&typeof t.init=="function"&&te[e].init()}function od(){return te}var Zs={};function ad(e,t){let n=typeof t!="function"?()=>t:t;return e instanceof Element?Qs(e,n()):(Zs[e]=n,()=>{})}function cd(e){return Object.entries(Zs).forEach(([t,n])=>{Object.defineProperty(e,t,{get(){return(...r)=>n(...r)}})}),e}function Qs(e,t,n){let r=[];for(;r.length;)r.pop()();let i=Object.entries(t).map(([o,a])=>({name:o,value:a})),s=vs(i);return i=i.map(o=>s.find(a=>a.name===o.name)?{name:`x-bind:${o.name}`,value:`"${o.value}"`}:o),zn(e,i,n).map(o=>{r.push(o.runCleanups),o()}),()=>{for(;r.length;)r.pop()()}}var eo={};function ud(e,t){eo[e]=t}function ld(e,t){return Object.entries(eo).forEach(([n,r])=>{Object.defineProperty(e,n,{get(){return(...i)=>r.bind(t)(...i)},enumerable:!1})}),e}var fd={get reactive(){return Ae},get release(){return xe},get effect(){return me},get raw(){return us},version:"3.14.9",flushAndStopDeferringMutations:pf,dontAutoEvaluateFunctions:Ss,disableEffectScheduling:of,startObservingMutations:Un,stopObservingMutations:bs,setReactivityEngine:af,onAttributeRemoved:gs,onAttributesAdded:hs,closestDataStack:_e,skipDuringClone:Q,onlyDuringClone:Uf,addRootSelector:Fs,addInitSelector:$s,interceptClone:xt,addScopeToNode:qe,deferMutations:df,mapAttributes:Wn,evaluateLater:D,interceptInit:Df,setEvaluator:yf,mergeProxies:Ke,extractProp:rd,findClosest:Te,onElRemoved:Ln,closestRoot:St,destroyTree:Ce,interceptor:ws,transition:yn,setStyles:At,mutateDom:x,directive:O,entangle:Ys,throttle:Xs,debounce:Gs,evaluate:ae,initTree:V,nextTick:Xn,prefixed:ve,prefix:Af,plugin:id,magic:$,store:sd,start:If,clone:Kf,cloneNode:qf,bound:nd,$data:_s,watch:ls,walk:ge,data:ud,bind:ad},Ve=fd;function dd(e,t){const n=Object.create(null),r=e.split(",");for(let i=0;i<r.length;i++)n[r[i]]=!0;return i=>!!n[i]}var pd=Object.freeze({}),hd=Object.prototype.hasOwnProperty,vt=(e,t)=>hd.call(e,t),ce=Array.isArray,Pe=e=>to(e)==="[object Map]",gd=e=>typeof e=="string",Qn=e=>typeof e=="symbol",Tt=e=>e!==null&&typeof e=="object",md=Object.prototype.toString,to=e=>md.call(e),no=e=>to(e).slice(8,-1),er=e=>gd(e)&&e!=="NaN"&&e[0]!=="-"&&""+parseInt(e,10)===e,bd=e=>{const t=Object.create(null);return n=>t[n]||(t[n]=e(n))},_d=bd(e=>e.charAt(0).toUpperCase()+e.slice(1)),ro=(e,t)=>e!==t&&(e===e||t===t),En=new WeakMap,De=[],H,ue=Symbol("iterate"),Sn=Symbol("Map key iterate");function yd(e){return e&&e._isEffect===!0}function wd(e,t=pd){yd(e)&&(e=e.raw);const n=Ad(e,t);return t.lazy||n(),n}function Ed(e){e.active&&(io(e),e.options.onStop&&e.options.onStop(),e.active=!1)}var Sd=0;function Ad(e,t){const n=function(){if(!n.active)return e();if(!De.includes(n)){io(n);try{return vd(),De.push(n),H=n,e()}finally{De.pop(),so(),H=De[De.length-1]}}};return n.id=Sd++,n.allowRecurse=!!t.allowRecurse,n._isEffect=!0,n.active=!0,n.raw=e,n.deps=[],n.options=t,n}function io(e){const{deps:t}=e;if(t.length){for(let n=0;n<t.length;n++)t[n].delete(e);t.length=0}}var ye=!0,tr=[];function xd(){tr.push(ye),ye=!1}function vd(){tr.push(ye),ye=!0}function so(){const e=tr.pop();ye=e===void 0?!0:e}function B(e,t,n){if(!ye||H===void 0)return;let r=En.get(e);r||En.set(e,r=new Map);let i=r.get(n);i||r.set(n,i=new Set),i.has(H)||(i.add(H),H.deps.push(i),H.options.onTrack&&H.options.onTrack({effect:H,target:e,type:t,key:n}))}function Z(e,t,n,r,i,s){const o=En.get(e);if(!o)return;const a=new Set,c=l=>{l&&l.forEach(d=>{(d!==H||d.allowRecurse)&&a.add(d)})};if(t==="clear")o.forEach(c);else if(n==="length"&&ce(e))o.forEach((l,d)=>{(d==="length"||d>=r)&&c(l)});else switch(n!==void 0&&c(o.get(n)),t){case"add":ce(e)?er(n)&&c(o.get("length")):(c(o.get(ue)),Pe(e)&&c(o.get(Sn)));break;case"delete":ce(e)||(c(o.get(ue)),Pe(e)&&c(o.get(Sn)));break;case"set":Pe(e)&&c(o.get(ue));break}const u=l=>{l.options.onTrigger&&l.options.onTrigger({effect:l,target:e,key:n,type:t,newValue:r,oldValue:i,oldTarget:s}),l.options.scheduler?l.options.scheduler(l):l()};a.forEach(u)}var Td=dd("__proto__,__v_isRef,__isVue"),oo=new Set(Object.getOwnPropertyNames(Symbol).map(e=>Symbol[e]).filter(Qn)),Cd=ao(),Od=ao(!0),Kr=Id();function Id(){const e={};return["includes","indexOf","lastIndexOf"].forEach(t=>{e[t]=function(...n){const r=A(this);for(let s=0,o=this.length;s<o;s++)B(r,"get",s+"");const i=r[t](...n);return i===-1||i===!1?r[t](...n.map(A)):i}}),["push","pop","shift","unshift","splice"].forEach(t=>{e[t]=function(...n){xd();const r=A(this)[t].apply(this,n);return so(),r}}),e}function ao(e=!1,t=!1){return function(r,i,s){if(i==="__v_isReactive")return!e;if(i==="__v_isReadonly")return e;if(i==="__v_raw"&&s===(e?t?qd:fo:t?Ud:lo).get(r))return r;const o=ce(r);if(!e&&o&&vt(Kr,i))return Reflect.get(Kr,i,s);const a=Reflect.get(r,i,s);return(Qn(i)?oo.has(i):Td(i))||(e||B(r,"get",i),t)?a:An(a)?!o||!er(i)?a.value:a:Tt(a)?e?po(a):sr(a):a}}var Rd=Dd();function Dd(e=!1){return function(n,r,i,s){let o=n[r];if(!e&&(i=A(i),o=A(o),!ce(n)&&An(o)&&!An(i)))return o.value=i,!0;const a=ce(n)&&er(r)?Number(r)<n.length:vt(n,r),c=Reflect.set(n,r,i,s);return n===A(s)&&(a?ro(i,o)&&Z(n,"set",r,i,o):Z(n,"add",r,i)),c}}function kd(e,t){const n=vt(e,t),r=e[t],i=Reflect.deleteProperty(e,t);return i&&n&&Z(e,"delete",t,void 0,r),i}function Nd(e,t){const n=Reflect.has(e,t);return(!Qn(t)||!oo.has(t))&&B(e,"has",t),n}function Pd(e){return B(e,"iterate",ce(e)?"length":ue),Reflect.ownKeys(e)}var Md={get:Cd,set:Rd,deleteProperty:kd,has:Nd,ownKeys:Pd},Bd={get:Od,set(e,t){return console.warn(`Set operation on key "${String(t)}" failed: target is readonly.`,e),!0},deleteProperty(e,t){return console.warn(`Delete operation on key "${String(t)}" failed: target is readonly.`,e),!0}},nr=e=>Tt(e)?sr(e):e,rr=e=>Tt(e)?po(e):e,ir=e=>e,Ct=e=>Reflect.getPrototypeOf(e);function We(e,t,n=!1,r=!1){e=e.__v_raw;const i=A(e),s=A(t);t!==s&&!n&&B(i,"get",t),!n&&B(i,"get",s);const{has:o}=Ct(i),a=r?ir:n?rr:nr;if(o.call(i,t))return a(e.get(t));if(o.call(i,s))return a(e.get(s));e!==i&&e.get(t)}function Je(e,t=!1){const n=this.__v_raw,r=A(n),i=A(e);return e!==i&&!t&&B(r,"has",e),!t&&B(r,"has",i),e===i?n.has(e):n.has(e)||n.has(i)}function Ge(e,t=!1){return e=e.__v_raw,!t&&B(A(e),"iterate",ue),Reflect.get(e,"size",e)}function Vr(e){e=A(e);const t=A(this);return Ct(t).has.call(t,e)||(t.add(e),Z(t,"add",e,e)),this}function zr(e,t){t=A(t);const n=A(this),{has:r,get:i}=Ct(n);let s=r.call(n,e);s?uo(n,r,e):(e=A(e),s=r.call(n,e));const o=i.call(n,e);return n.set(e,t),s?ro(t,o)&&Z(n,"set",e,t,o):Z(n,"add",e,t),this}function Wr(e){const t=A(this),{has:n,get:r}=Ct(t);let i=n.call(t,e);i?uo(t,n,e):(e=A(e),i=n.call(t,e));const s=r?r.call(t,e):void 0,o=t.delete(e);return i&&Z(t,"delete",e,void 0,s),o}function Jr(){const e=A(this),t=e.size!==0,n=Pe(e)?new Map(e):new Set(e),r=e.clear();return t&&Z(e,"clear",void 0,void 0,n),r}function Xe(e,t){return function(r,i){const s=this,o=s.__v_raw,a=A(o),c=t?ir:e?rr:nr;return!e&&B(a,"iterate",ue),o.forEach((u,l)=>r.call(i,c(u),c(l),s))}}function Ye(e,t,n){return function(...r){const i=this.__v_raw,s=A(i),o=Pe(s),a=e==="entries"||e===Symbol.iterator&&o,c=e==="keys"&&o,u=i[e](...r),l=n?ir:t?rr:nr;return!t&&B(s,"iterate",c?Sn:ue),{next(){const{value:d,done:h}=u.next();return h?{value:d,done:h}:{value:a?[l(d[0]),l(d[1])]:l(d),done:h}},[Symbol.iterator](){return this}}}}function z(e){return function(...t){{const n=t[0]?`on key "${t[0]}" `:"";console.warn(`${_d(e)} operation ${n}failed: target is readonly.`,A(this))}return e==="delete"?!1:this}}function Fd(){const e={get(s){return We(this,s)},get size(){return Ge(this)},has:Je,add:Vr,set:zr,delete:Wr,clear:Jr,forEach:Xe(!1,!1)},t={get(s){return We(this,s,!1,!0)},get size(){return Ge(this)},has:Je,add:Vr,set:zr,delete:Wr,clear:Jr,forEach:Xe(!1,!0)},n={get(s){return We(this,s,!0)},get size(){return Ge(this,!0)},has(s){return Je.call(this,s,!0)},add:z("add"),set:z("set"),delete:z("delete"),clear:z("clear"),forEach:Xe(!0,!1)},r={get(s){return We(this,s,!0,!0)},get size(){return Ge(this,!0)},has(s){return Je.call(this,s,!0)},add:z("add"),set:z("set"),delete:z("delete"),clear:z("clear"),forEach:Xe(!0,!0)};return["keys","values","entries",Symbol.iterator].forEach(s=>{e[s]=Ye(s,!1,!1),n[s]=Ye(s,!0,!1),t[s]=Ye(s,!1,!0),r[s]=Ye(s,!0,!0)}),[e,n,t,r]}var[$d,Ld,Ip,Rp]=Fd();function co(e,t){const n=e?Ld:$d;return(r,i,s)=>i==="__v_isReactive"?!e:i==="__v_isReadonly"?e:i==="__v_raw"?r:Reflect.get(vt(n,i)&&i in r?n:r,i,s)}var jd={get:co(!1)},Hd={get:co(!0)};function uo(e,t,n){const r=A(n);if(r!==n&&t.call(e,r)){const i=no(e);console.warn(`Reactive ${i} contains both the raw and reactive versions of the same object${i==="Map"?" as keys":""}, which can lead to inconsistencies. Avoid differentiating between the raw and reactive versions of an object and only use the reactive version if possible.`)}}var lo=new WeakMap,Ud=new WeakMap,fo=new WeakMap,qd=new WeakMap;function Kd(e){switch(e){case"Object":case"Array":return 1;case"Map":case"Set":case"WeakMap":case"WeakSet":return 2;default:return 0}}function Vd(e){return e.__v_skip||!Object.isExtensible(e)?0:Kd(no(e))}function sr(e){return e&&e.__v_isReadonly?e:ho(e,!1,Md,jd,lo)}function po(e){return ho(e,!0,Bd,Hd,fo)}function ho(e,t,n,r,i){if(!Tt(e))return console.warn(`value cannot be made reactive: ${String(e)}`),e;if(e.__v_raw&&!(t&&e.__v_isReactive))return e;const s=i.get(e);if(s)return s;const o=Vd(e);if(o===0)return e;const a=new Proxy(e,o===2?r:n);return i.set(e,a),a}function A(e){return e&&A(e.__v_raw)||e}function An(e){return!!(e&&e.__v_isRef===!0)}$("nextTick",()=>Xn);$("dispatch",e=>Ne.bind(Ne,e));$("watch",(e,{evaluateLater:t,cleanup:n})=>(r,i)=>{let s=t(r),a=ls(()=>{let c;return s(u=>c=u),c},i);n(a)});$("store",od);$("data",e=>_s(e));$("root",e=>St(e));$("refs",e=>(e._x_refs_proxy||(e._x_refs_proxy=Ke(zd(e))),e._x_refs_proxy));function zd(e){let t=[];return Te(e,n=>{n._x_refs&&t.push(n._x_refs)}),t}var Vt={};function go(e){return Vt[e]||(Vt[e]=0),++Vt[e]}function Wd(e,t){return Te(e,n=>{if(n._x_ids&&n._x_ids[t])return!0})}function Jd(e,t){e._x_ids||(e._x_ids={}),e._x_ids[t]||(e._x_ids[t]=go(t))}$("id",(e,{cleanup:t})=>(n,r=null)=>{let i=`${n}${r?`-${r}`:""}`;return Gd(e,i,t,()=>{let s=Wd(e,n),o=s?s._x_ids[n]:go(n);return r?`${n}-${o}-${r}`:`${n}-${o}`})});xt((e,t)=>{e._x_id&&(t._x_id=e._x_id)});function Gd(e,t,n,r){if(e._x_id||(e._x_id={}),e._x_id[t])return e._x_id[t];let i=r();return e._x_id[t]=i,n(()=>{delete e._x_id[t]}),i}$("el",e=>e);mo("Focus","focus","focus");mo("Persist","persist","persist");function mo(e,t,n){$(t,r=>M(`You can't use [$${t}] without first installing the "${e}" plugin here: https://alpinejs.dev/plugins/${n}`,r))}O("modelable",(e,{expression:t},{effect:n,evaluateLater:r,cleanup:i})=>{let s=r(t),o=()=>{let l;return s(d=>l=d),l},a=r(`${t} = __placeholder`),c=l=>a(()=>{},{scope:{__placeholder:l}}),u=o();c(u),queueMicrotask(()=>{if(!e._x_model)return;e._x_removeModelListeners.default();let l=e._x_model.get,d=e._x_model.set,h=Ys({get(){return l()},set(_){d(_)}},{get(){return o()},set(_){c(_)}});i(h)})});O("teleport",(e,{modifiers:t,expression:n},{cleanup:r})=>{e.tagName.toLowerCase()!=="template"&&M("x-teleport can only be used on a <template> tag",e);let i=Gr(n),s=e.content.cloneNode(!0).firstElementChild;e._x_teleport=s,s._x_teleportBack=e,e.setAttribute("data-teleport-template",!0),s.setAttribute("data-teleport-target",!0),e._x_forwardEvents&&e._x_forwardEvents.forEach(a=>{s.addEventListener(a,c=>{c.stopPropagation(),e.dispatchEvent(new c.constructor(c.type,c))})}),qe(s,{},e);let o=(a,c,u)=>{u.includes("prepend")?c.parentNode.insertBefore(a,c):u.includes("append")?c.parentNode.insertBefore(a,c.nextSibling):c.appendChild(a)};x(()=>{o(s,i,t),Q(()=>{V(s)})()}),e._x_teleportPutBack=()=>{let a=Gr(n);x(()=>{o(e._x_teleport,a,t)})},r(()=>x(()=>{s.remove(),Ce(s)}))});var Xd=document.createElement("div");function Gr(e){let t=Q(()=>document.querySelector(e),()=>Xd)();return t||M(`Cannot find x-teleport element for selector: "${e}"`),t}var bo=()=>{};bo.inline=(e,{modifiers:t},{cleanup:n})=>{t.includes("self")?e._x_ignoreSelf=!0:e._x_ignore=!0,n(()=>{t.includes("self")?delete e._x_ignoreSelf:delete e._x_ignore})};O("ignore",bo);O("effect",Q((e,{expression:t},{effect:n})=>{n(D(e,t))}));function xn(e,t,n,r){let i=e,s=c=>r(c),o={},a=(c,u)=>l=>u(c,l);if(n.includes("dot")&&(t=Yd(t)),n.includes("camel")&&(t=Zd(t)),n.includes("passive")&&(o.passive=!0),n.includes("capture")&&(o.capture=!0),n.includes("window")&&(i=window),n.includes("document")&&(i=document),n.includes("debounce")){let c=n[n.indexOf("debounce")+1]||"invalid-wait",u=lt(c.split("ms")[0])?Number(c.split("ms")[0]):250;s=Gs(s,u)}if(n.includes("throttle")){let c=n[n.indexOf("throttle")+1]||"invalid-wait",u=lt(c.split("ms")[0])?Number(c.split("ms")[0]):250;s=Xs(s,u)}return n.includes("prevent")&&(s=a(s,(c,u)=>{u.preventDefault(),c(u)})),n.includes("stop")&&(s=a(s,(c,u)=>{u.stopPropagation(),c(u)})),n.includes("once")&&(s=a(s,(c,u)=>{c(u),i.removeEventListener(t,s,o)})),(n.includes("away")||n.includes("outside"))&&(i=document,s=a(s,(c,u)=>{e.contains(u.target)||u.target.isConnected!==!1&&(e.offsetWidth<1&&e.offsetHeight<1||e._x_isShown!==!1&&c(u))})),n.includes("self")&&(s=a(s,(c,u)=>{u.target===e&&c(u)})),(ep(t)||_o(t))&&(s=a(s,(c,u)=>{tp(u,n)||c(u)})),i.addEventListener(t,s,o),()=>{i.removeEventListener(t,s,o)}}function Yd(e){return e.replace(/-/g,".")}function Zd(e){return e.toLowerCase().replace(/-(\w)/g,(t,n)=>n.toUpperCase())}function lt(e){return!Array.isArray(e)&&!isNaN(e)}function Qd(e){return[" ","_"].includes(e)?e:e.replace(/([a-z])([A-Z])/g,"$1-$2").replace(/[_\s]/,"-").toLowerCase()}function ep(e){return["keydown","keyup"].includes(e)}function _o(e){return["contextmenu","click","mouse"].some(t=>e.includes(t))}function tp(e,t){let n=t.filter(s=>!["window","document","prevent","stop","once","capture","self","away","outside","passive"].includes(s));if(n.includes("debounce")){let s=n.indexOf("debounce");n.splice(s,lt((n[s+1]||"invalid-wait").split("ms")[0])?2:1)}if(n.includes("throttle")){let s=n.indexOf("throttle");n.splice(s,lt((n[s+1]||"invalid-wait").split("ms")[0])?2:1)}if(n.length===0||n.length===1&&Xr(e.key).includes(n[0]))return!1;const i=["ctrl","shift","alt","meta","cmd","super"].filter(s=>n.includes(s));return n=n.filter(s=>!i.includes(s)),!(i.length>0&&i.filter(o=>((o==="cmd"||o==="super")&&(o="meta"),e[`${o}Key`])).length===i.length&&(_o(e.type)||Xr(e.key).includes(n[0])))}function Xr(e){if(!e)return[];e=Qd(e);let t={ctrl:"control",slash:"/",space:" ",spacebar:" ",cmd:"meta",esc:"escape",up:"arrow-up",down:"arrow-down",left:"arrow-left",right:"arrow-right",period:".",comma:",",equal:"=",minus:"-",underscore:"_"};return t[e]=e,Object.keys(t).map(n=>{if(t[n]===e)return n}).filter(n=>n)}O("model",(e,{modifiers:t,expression:n},{effect:r,cleanup:i})=>{let s=e;t.includes("parent")&&(s=e.parentNode);let o=D(s,n),a;typeof n=="string"?a=D(s,`${n} = __placeholder`):typeof n=="function"&&typeof n()=="string"?a=D(s,`${n()} = __placeholder`):a=()=>{};let c=()=>{let h;return o(_=>h=_),Yr(h)?h.get():h},u=h=>{let _;o(g=>_=g),Yr(_)?_.set(h):a(()=>{},{scope:{__placeholder:h}})};typeof n=="string"&&e.type==="radio"&&x(()=>{e.hasAttribute("name")||e.setAttribute("name",n)});var l=e.tagName.toLowerCase()==="select"||["checkbox","radio"].includes(e.type)||t.includes("lazy")?"change":"input";let d=Y?()=>{}:xn(e,l,t,h=>{u(zt(e,t,h,c()))});if(t.includes("fill")&&([void 0,null,""].includes(c())||Zn(e)&&Array.isArray(c())||e.tagName.toLowerCase()==="select"&&e.multiple)&&u(zt(e,t,{target:e},c())),e._x_removeModelListeners||(e._x_removeModelListeners={}),e._x_removeModelListeners.default=d,i(()=>e._x_removeModelListeners.default()),e.form){let h=xn(e.form,"reset",[],_=>{Xn(()=>e._x_model&&e._x_model.set(zt(e,t,{target:e},c())))});i(()=>h())}e._x_model={get(){return c()},set(h){u(h)}},e._x_forceModelUpdate=h=>{h===void 0&&typeof n=="string"&&n.match(/\./)&&(h=""),window.fromModel=!0,x(()=>Ks(e,"value",h)),delete window.fromModel},r(()=>{let h=c();t.includes("unintrusive")&&document.activeElement.isSameNode(e)||e._x_forceModelUpdate(h)})});function zt(e,t,n,r){return x(()=>{if(n instanceof CustomEvent&&n.detail!==void 0)return n.detail!==null&&n.detail!==void 0?n.detail:n.target.value;if(Zn(e))if(Array.isArray(r)){let i=null;return t.includes("number")?i=Wt(n.target.value):t.includes("boolean")?i=nt(n.target.value):i=n.target.value,n.target.checked?r.includes(i)?r:r.concat([i]):r.filter(s=>!np(s,i))}else return n.target.checked;else{if(e.tagName.toLowerCase()==="select"&&e.multiple)return t.includes("number")?Array.from(n.target.selectedOptions).map(i=>{let s=i.value||i.text;return Wt(s)}):t.includes("boolean")?Array.from(n.target.selectedOptions).map(i=>{let s=i.value||i.text;return nt(s)}):Array.from(n.target.selectedOptions).map(i=>i.value||i.text);{let i;return Js(e)?n.target.checked?i=n.target.value:i=r:i=n.target.value,t.includes("number")?Wt(i):t.includes("boolean")?nt(i):t.includes("trim")?i.trim():i}}})}function Wt(e){let t=e?parseFloat(e):null;return rp(t)?t:e}function np(e,t){return e==t}function rp(e){return!Array.isArray(e)&&!isNaN(e)}function Yr(e){return e!==null&&typeof e=="object"&&typeof e.get=="function"&&typeof e.set=="function"}O("cloak",e=>queueMicrotask(()=>x(()=>e.removeAttribute(ve("cloak")))));$s(()=>`[${ve("init")}]`);O("init",Q((e,{expression:t},{evaluate:n})=>typeof t=="string"?!!t.trim()&&n(t,{},!1):n(t,{},!1)));O("text",(e,{expression:t},{effect:n,evaluateLater:r})=>{let i=r(t);n(()=>{i(s=>{x(()=>{e.textContent=s})})})});O("html",(e,{expression:t},{effect:n,evaluateLater:r})=>{let i=r(t);n(()=>{i(s=>{x(()=>{e.innerHTML=s,e._x_ignoreSelf=!0,V(e),delete e._x_ignoreSelf})})})});Wn(Os(":",Is(ve("bind:"))));var yo=(e,{value:t,modifiers:n,expression:r,original:i},{effect:s,cleanup:o})=>{if(!t){let c={};cd(c),D(e,r)(l=>{Qs(e,l,i)},{scope:c});return}if(t==="key")return ip(e,r);if(e._x_inlineBindings&&e._x_inlineBindings[t]&&e._x_inlineBindings[t].extract)return;let a=D(e,r);s(()=>a(c=>{c===void 0&&typeof r=="string"&&r.match(/\./)&&(c=""),x(()=>Ks(e,t,c,n))})),o(()=>{e._x_undoAddedClasses&&e._x_undoAddedClasses(),e._x_undoAddedStyles&&e._x_undoAddedStyles()})};yo.inline=(e,{value:t,modifiers:n,expression:r})=>{t&&(e._x_inlineBindings||(e._x_inlineBindings={}),e._x_inlineBindings[t]={expression:r,extract:!1})};O("bind",yo);function ip(e,t){e._x_keyExpression=t}Fs(()=>`[${ve("data")}]`);O("data",(e,{expression:t},{cleanup:n})=>{if(sp(e))return;t=t===""?"{}":t;let r={};pn(r,e);let i={};ld(i,r);let s=ae(e,t,{scope:i});(s===void 0||s===!0)&&(s={}),pn(s,e);let o=Ae(s);ys(o);let a=qe(e,o);o.init&&ae(e,o.init),n(()=>{o.destroy&&ae(e,o.destroy),a()})});xt((e,t)=>{e._x_dataStack&&(t._x_dataStack=e._x_dataStack,t.setAttribute("data-has-alpine-state",!0))});function sp(e){return Y?wn?!0:e.hasAttribute("data-has-alpine-state"):!1}O("show",(e,{modifiers:t,expression:n},{effect:r})=>{let i=D(e,n);e._x_doHide||(e._x_doHide=()=>{x(()=>{e.style.setProperty("display","none",t.includes("important")?"important":void 0)})}),e._x_doShow||(e._x_doShow=()=>{x(()=>{e.style.length===1&&e.style.display==="none"?e.removeAttribute("style"):e.style.removeProperty("display")})});let s=()=>{e._x_doHide(),e._x_isShown=!1},o=()=>{e._x_doShow(),e._x_isShown=!0},a=()=>setTimeout(o),c=_n(d=>d?o():s(),d=>{typeof e._x_toggleAndCascadeWithTransitions=="function"?e._x_toggleAndCascadeWithTransitions(e,d,o,s):d?a():s()}),u,l=!0;r(()=>i(d=>{!l&&d===u||(t.includes("immediate")&&(d?a():s()),c(d),u=d,l=!1)}))});O("for",(e,{expression:t},{effect:n,cleanup:r})=>{let i=ap(t),s=D(e,i.items),o=D(e,e._x_keyExpression||"index");e._x_prevKeys=[],e._x_lookup={},n(()=>op(e,i,s,o)),r(()=>{Object.values(e._x_lookup).forEach(a=>x(()=>{Ce(a),a.remove()})),delete e._x_prevKeys,delete e._x_lookup})});function op(e,t,n,r){let i=o=>typeof o=="object"&&!Array.isArray(o),s=e;n(o=>{cp(o)&&o>=0&&(o=Array.from(Array(o).keys(),p=>p+1)),o===void 0&&(o=[]);let a=e._x_lookup,c=e._x_prevKeys,u=[],l=[];if(i(o))o=Object.entries(o).map(([p,m])=>{let w=Zr(t,m,p,o);r(E=>{l.includes(E)&&M("Duplicate key on x-for",e),l.push(E)},{scope:{index:p,...w}}),u.push(w)});else for(let p=0;p<o.length;p++){let m=Zr(t,o[p],p,o);r(w=>{l.includes(w)&&M("Duplicate key on x-for",e),l.push(w)},{scope:{index:p,...m}}),u.push(m)}let d=[],h=[],_=[],g=[];for(let p=0;p<c.length;p++){let m=c[p];l.indexOf(m)===-1&&_.push(m)}c=c.filter(p=>!_.includes(p));let b="template";for(let p=0;p<l.length;p++){let m=l[p],w=c.indexOf(m);if(w===-1)c.splice(p,0,m),d.push([b,p]);else if(w!==p){let E=c.splice(p,1)[0],T=c.splice(w-1,1)[0];c.splice(p,0,T),c.splice(w,0,E),h.push([E,T])}else g.push(m);b=m}for(let p=0;p<_.length;p++){let m=_[p];m in a&&(x(()=>{Ce(a[m]),a[m].remove()}),delete a[m])}for(let p=0;p<h.length;p++){let[m,w]=h[p],E=a[m],T=a[w],C=document.createElement("div");x(()=>{T||M('x-for ":key" is undefined or invalid',s,w,a),T.after(C),E.after(T),T._x_currentIfEl&&T.after(T._x_currentIfEl),C.before(E),E._x_currentIfEl&&E.after(E._x_currentIfEl),C.remove()}),T._x_refreshXForScope(u[l.indexOf(w)])}for(let p=0;p<d.length;p++){let[m,w]=d[p],E=m==="template"?s:a[m];E._x_currentIfEl&&(E=E._x_currentIfEl);let T=u[w],C=l[w],k=document.importNode(s.content,!0).firstElementChild,L=Ae(T);qe(k,L,s),k._x_refreshXForScope=be=>{Object.entries(be).forEach(([ze,Eo])=>{L[ze]=Eo})},x(()=>{E.after(k),Q(()=>V(k))()}),typeof C=="object"&&M("x-for key cannot be an object, it must be a string or an integer",s),a[C]=k}for(let p=0;p<g.length;p++)a[g[p]]._x_refreshXForScope(u[l.indexOf(g[p])]);s._x_prevKeys=l})}function ap(e){let t=/,([^,\}\]]*)(?:,([^,\}\]]*))?$/,n=/^\s*\(|\)\s*$/g,r=/([\s\S]*?)\s+(?:in|of)\s+([\s\S]*)/,i=e.match(r);if(!i)return;let s={};s.items=i[2].trim();let o=i[1].replace(n,"").trim(),a=o.match(t);return a?(s.item=o.replace(t,"").trim(),s.index=a[1].trim(),a[2]&&(s.collection=a[2].trim())):s.item=o,s}function Zr(e,t,n,r){let i={};return/^\[.*\]$/.test(e.item)&&Array.isArray(t)?e.item.replace("[","").replace("]","").split(",").map(o=>o.trim()).forEach((o,a)=>{i[o]=t[a]}):/^\{.*\}$/.test(e.item)&&!Array.isArray(t)&&typeof t=="object"?e.item.replace("{","").replace("}","").split(",").map(o=>o.trim()).forEach(o=>{i[o]=t[o]}):i[e.item]=t,e.index&&(i[e.index]=n),e.collection&&(i[e.collection]=r),i}function cp(e){return!Array.isArray(e)&&!isNaN(e)}function wo(){}wo.inline=(e,{expression:t},{cleanup:n})=>{let r=St(e);r._x_refs||(r._x_refs={}),r._x_refs[t]=e,n(()=>delete r._x_refs[t])};O("ref",wo);O("if",(e,{expression:t},{effect:n,cleanup:r})=>{e.tagName.toLowerCase()!=="template"&&M("x-if can only be used on a <template> tag",e);let i=D(e,t),s=()=>{if(e._x_currentIfEl)return e._x_currentIfEl;let a=e.content.cloneNode(!0).firstElementChild;return qe(a,{},e),x(()=>{e.after(a),Q(()=>V(a))()}),e._x_currentIfEl=a,e._x_undoIf=()=>{x(()=>{Ce(a),a.remove()}),delete e._x_currentIfEl},a},o=()=>{e._x_undoIf&&(e._x_undoIf(),delete e._x_undoIf)};n(()=>i(a=>{a?s():o()})),r(()=>e._x_undoIf&&e._x_undoIf())});O("id",(e,{expression:t},{evaluate:n})=>{n(t).forEach(i=>Jd(e,i))});xt((e,t)=>{e._x_ids&&(t._x_ids=e._x_ids)});Wn(Os("@",Is(ve("on:"))));O("on",Q((e,{value:t,modifiers:n,expression:r},{cleanup:i})=>{let s=r?D(e,r):()=>{};e.tagName.toLowerCase()==="template"&&(e._x_forwardEvents||(e._x_forwardEvents=[]),e._x_forwardEvents.includes(t)||e._x_forwardEvents.push(t));let o=xn(e,t,n,a=>{s(()=>{},{scope:{$event:a},params:[a]})});i(()=>o())}));Ot("Collapse","collapse","collapse");Ot("Intersect","intersect","intersect");Ot("Focus","trap","focus");Ot("Mask","mask","mask");function Ot(e,t,n){O(t,r=>M(`You can't use [x-${t}] without first installing the "${e}" plugin here: https://alpinejs.dev/plugins/${n}`,r))}Ve.setEvaluator(xs);Ve.setReactivityEngine({reactive:sr,effect:wd,release:Ed,raw:A});var up=Ve,Dp=up;function lp(e){e.directive("collapse",t),t.inline=(n,{modifiers:r})=>{r.includes("min")&&(n._x_doShow=()=>{},n._x_doHide=()=>{})};function t(n,{modifiers:r}){let i=Qr(r,"duration",250)/1e3,s=Qr(r,"min",0),o=!r.includes("min");n._x_isShown||(n.style.height=`${s}px`),!n._x_isShown&&o&&(n.hidden=!0),n._x_isShown||(n.style.overflow="hidden");let a=(u,l)=>{let d=e.setStyles(u,l);return l.height?()=>{}:d},c={transitionProperty:"height",transitionDuration:`${i}s`,transitionTimingFunction:"cubic-bezier(0.4, 0.0, 0.2, 1)"};n._x_transition={in(u=()=>{},l=()=>{}){o&&(n.hidden=!1),o&&(n.style.display=null);let d=n.getBoundingClientRect().height;n.style.height="auto";let h=n.getBoundingClientRect().height;d===h&&(d=s),e.transition(n,e.setStyles,{during:c,start:{height:d+"px"},end:{height:h+"px"}},()=>n._x_isShown=!0,()=>{Math.abs(n.getBoundingClientRect().height-h)<1&&(n.style.overflow=null)})},out(u=()=>{},l=()=>{}){let d=n.getBoundingClientRect().height;e.transition(n,a,{during:c,start:{height:d+"px"},end:{height:s+"px"}},()=>n.style.overflow="hidden",()=>{n._x_isShown=!1,n.style.height==`${s}px`&&o&&(n.style.display="none",n.hidden=!0)})}}}}function Qr(e,t,n){if(e.indexOf(t)===-1)return n;const r=e[e.indexOf(t)+1];if(!r)return n;if(t==="duration"){let i=r.match(/([0-9]+)ms/);if(i)return i[1]}if(t==="min"){let i=r.match(/([0-9]+)px/);if(i)return i[1]}return r}var kp=lp;export{kp as a,Dp as m};
//# sourceMappingURL=module.esm-Fzhlmc-i.js.map
