(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-660c2bd2"],{"4de4":function(e,t,n){"use strict";var r=n("23e7"),o=n("b727").filter,a=n("1dde"),s=n("ae40"),i=a("filter"),c=s("filter");r({target:"Array",proto:!0,forced:!i||!c},{filter:function(e){return o(this,e,arguments.length>1?arguments[1]:void 0)}})},5530:function(e,t,n){"use strict";function r(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function o(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function a(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?o(Object(n),!0).forEach((function(t){r(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):o(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}n.d(t,"a",(function(){return a})),n("a4d3"),n("4de4"),n("4160"),n("e439"),n("dbb4"),n("b64b"),n("159b")},"859c":function(e,t,n){"use strict";n.r(t);var r=(n("4160"),n("b0c0"),n("d3b7"),n("159b"),n("53ca")),o=n("5530"),a=n("2f62"),s={name:"vc-header",data:function(){return{nav_menu:{},user:{}}},computed:Object(o.a)({},Object(a.c)({is_collapse:function(e){return e.is_collapse},side_width:function(e){return e.side_width},active_menu_nav:function(e){return e.active_menu_nav}})),mounted:function(){var e=this;e.user=JSON.parse(e.$cookie.get("user")),this.$api.request("auth_menu_model","get_nav_menu","get").then((function(t){0==t.code?(e.nav_menu=t.data.nav_menu,e.$store.dispatch("setNavMenu",e.nav_menu)):1e3==t.code?e.$router.push({path:"/login"}):e.$Message.error(t.msg)}))},methods:{userEvent:function(e){var t=this;"logout"==e?t.$api.request("admin_model","logout","post").then((function(e){0==e.code?(t.$cookie.remove("token"),t.$cookie.remove("user"),t.$cookie.remove("server"),t.$Message.success(e.msg),window.sessionStorage.clear(),t.$store.dispatch("setNavTabsData",{currentNavTab:"welcome",navTabs:[{title:"系统首页",name:"welcome",closable:!1}]}),t.$router.push({path:"/login"})):t.$Message.error(e.msg)})):"change_backend_en"==e?t.$api.axios_request("/welcome/lang","get",{lang:"en-us"}).then((function(e){0==e.code?t.lang=e.data.lang:t.$Message.error(e.msg)})):"change_backend_es"==e&&t.$api.axios_request("/welcome/lang","get",{lang:"es-mx"}).then((function(e){0==e.code?t.lang=e.data.lang:t.$Message.error(e.msg)}))},getMenuRouter:function(e,t,n){var o=this;if(null!=e){var a=function(r){var a=e[r];if(null!=a.children)o.getMenuRouter(a.children,t,n);else{if(""==a.component)return{v:void 0};var s={path:a.path,component:a.component,name:a.title,meta:{permission:a.permission,model:a.model_name,filter_field:a.filter_field,title:a.title,icon:a.icon,noCache:!0,topId:a.top_id,id:a.id}};if(null!=t){var i=!0;t.forEach((function(e,t){e.path!=a.path&&e.name!=a.title||(i=!1)})),i&&n.push(s)}else n.push(s)}};for(var s in e){var i=a(s);if("object"===Object(r.a)(i))return i.v}}},handleSelect:function(e){var t=this,r=this.nav_menu[e];null==r&&(r=[]),r.active_header=e,t.$store.dispatch("setSideMenuData",r);var o=[],a=JSON.parse(window.sessionStorage.getItem("side_menu_router"));if(this.getMenuRouter(r.children,a,o),(o=this.$helper.mergeObj(a,o)).length>0){window.sessionStorage.setItem("side_menu_router",JSON.stringify(o));var s=t.$router.options.routes[0].children,i=[];o.forEach((function(e,t){var r=!0;s.forEach((function(t,n){e.path!=t.path&&e.name!=t.name||(r=!1)})),r&&i.push({path:e.path,component:function(){return n("9dac")("./"+e.component)},name:e.name,meta:e.meta})})),i.length>0&&(t.$router.options.routes[0].children=i,t.$router.addRoutes(t.$router.options.routes))}}}},i=(n("974f"),n("2877")),c=Object(i.a)(s,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("Header",{style:{height:"42px"}},[n("div",{staticClass:"header-inner"},[n("div",{staticClass:"header-logo",class:e.side_width},[e.is_collapse?n("span",[n("Dropdown",{staticClass:"main-nav",attrs:{trigger:"click"},on:{"on-click":e.handleSelect}},[n("span",{staticClass:"ivu-dropdown-link"},[n("Icon",{attrs:{type:"md-menu"}})],1),n("DropdownMenu",{attrs:{slot:"list"},slot:"list"},[e._l(e.nav_menu,(function(t,r){return[n("DropdownItem",{attrs:{name:t.path}},[e._v(e._s(t.title))])]}))],2)],1)],1):n("span",{staticClass:"logo"},[e._v("VueCMF")])]),e.is_collapse?n("div",{staticClass:"menu-nav"},[e._v("VueCMF")]):n("Menu",{staticClass:"menu-nav",attrs:{"active-name":e.active_menu_nav,mode:"horizontal",theme:"light"},on:{"on-select":e.handleSelect}},[e._l(e.nav_menu,(function(t,r){return[n("MenuItem",{attrs:{name:t.id}},[e._v(e._s(t.title))])]}))],2),n("Dropdown",{staticClass:"user-info",attrs:{trigger:"click",placement:"bottom-end"},on:{"on-click":e.userEvent}},[n("span",{staticClass:"ivu-dropdown-link user-face"},[n("Icon",{attrs:{type:"ios-contact"}})],1),n("DropdownMenu",{attrs:{slot:"list"},slot:"list"},[n("DropdownItem",[e._v("账号："+e._s(e.user.username))]),n("DropdownItem",[e._v("角色："+e._s(e.user.role))]),n("DropdownItem",{attrs:{divided:"",name:"logout"}},[n("Icon",{attrs:{type:"md-power"}}),e._v(" 退出系统")],1)],1)],1)],1)])}),[],!1,null,null,null);t.default=c.exports},"974f":function(e,t,n){"use strict";var r=n("ffea");n.n(r).a},b64b:function(e,t,n){var r=n("23e7"),o=n("7b0b"),a=n("df75");r({target:"Object",stat:!0,forced:n("d039")((function(){a(1)}))},{keys:function(e){return a(o(e))}})},dbb4:function(e,t,n){var r=n("23e7"),o=n("83ab"),a=n("56ef"),s=n("fc6a"),i=n("06cf"),c=n("8418");r({target:"Object",stat:!0,sham:!o},{getOwnPropertyDescriptors:function(e){for(var t,n,r=s(e),o=i.f,u=a(r),l={},d=0;u.length>d;)void 0!==(n=o(r,t=u[d++]))&&c(l,t,n);return l}})},e439:function(e,t,n){var r=n("23e7"),o=n("d039"),a=n("fc6a"),s=n("06cf").f,i=n("83ab"),c=o((function(){s(1)}));r({target:"Object",stat:!0,forced:!i||c,sham:!i},{getOwnPropertyDescriptor:function(e,t){return s(a(e),t)}})},ffea:function(e,t,n){}}]);
//# sourceMappingURL=chunk-660c2bd2.ba0f761f.js.map