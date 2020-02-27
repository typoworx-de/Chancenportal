(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{522:function(e,t,i){"use strict";(function(e){var a=i(541);t.a={name:"CustomMap",components:{KachelComponent:a.a},props:["items","apiKey","show","height","icon","lat","lng","link"],data:function(){return{geocoder:null,map:!1,showMap:!1,windows:[],marker:[],initialized:!1,singleAddressOnly:!1}},watch:{show:function(e){this.showMap=!!e,this.showMap&&!1===this.initialized&&(this.init(),this.initialized=!0)}},methods:{openLink:function(){window.open(this.link)},initSingleMarker:function(){var e={url:this.icon?this.icon:"/img/svg/Icon_Ort.svg",scaledSize:new google.maps.Size(20,28),origin:new google.maps.Point(0,0),anchor:new google.maps.Point(10,28)},t=parseFloat(this.lat),i=parseFloat(this.lng),a=new google.maps.Marker({map:this.map,position:{lat:t,lng:i},icon:e});this.marker.push(a),this.map.setCenter(new google.maps.LatLng(t,i)),this.map.setZoom(14)},loaded:function(){var e=this;this.marker=[],this.windows=[],this.geocoder=new google.maps.Geocoder,this.map=new google.maps.Map(this.$refs.custom_map,{center:{lat:52.0849347,lng:9.6404649},zoom:8,disableDefaultUI:this.singleAddressOnly}),!1===this.singleAddressOnly?this.$nextTick((function(){e.createMarker()})):this.$nextTick((function(){e.initSingleMarker()}))},init:function(){var t=this;void 0===window.google||void 0===window.google.maps?e.getScript("https://maps.googleapis.com/maps/api/js?key="+this.apiKey+"&libraries=geometry",(function(){t.loaded()})):this.loaded()},createMarker:function(){var t=this,i={url:this.icon?this.icon:"/img/svg/Icon_Ort.svg",scaledSize:new google.maps.Size(20,28),origin:new google.maps.Point(0,0),anchor:new google.maps.Point(10,28)},a=e(this.$refs.items).find(".kachel");a.length&&a.each((function(a,s){var n=e(s),o=parseFloat(n.data("lat")),l=parseFloat(n.data("lng"));if(!isNaN(o)&&!isNaN(l)){var r=new google.maps.Marker({map:t.map,position:{lat:o,lng:l},icon:i}),h=new google.maps.InfoWindow({content:n.clone().wrap("<div>").parent().html()});t.marker.push(r),t.windows.push(h),r.addListener("click",(function(){t.windows.forEach((function(e){e.close()})),h.open(t.map,r);var i=e(t.$refs.custom_map).find(".gm-style-iw");i.find(".kachel").show(),e(t.$refs.custom_map).find("div > div > div:nth-child(1) > div:nth-child(3) > div:nth-child(2) > div:nth-child(4) > div > div:nth-child(1)").remove();var a=i.prev();a.children(":nth-child(2)").css({display:"none"}),a.children(":nth-child(4)").css({display:"none"}),i.parent().parent().css({left:"0px"}),a.children(":nth-child(3)").find("div").children().css({"box-shadow":"rgba(72, 181, 233, 0.6) 0px 1px 6px","z-index":"1"});var s=i.next();s.css({opacity:"1",right:"29px",top:"6px","border-radius":"50%","box-shadow":"none"}),e(".iw-content").height()<140&&e(".iw-bottom-gradient").css({display:"none"}),s.mouseout((function(){e(this).css({opacity:"1"})})),setTimeout((function(){e(".kachel--light").parentsUntil(".gm-style").css("overflow","visible")}),500)}))}})),this.resetZoom()},setZoom:function(){var e=this;this.$nextTick((function(){google.maps.event.addListenerOnce(e.map,"idle",(function(){e.showMap=e.show,e.initialized=!0;for(var t=new google.maps.LatLngBounds,i=0;i<e.marker.length;i++)t.extend(e.marker[i].getPosition());e.map.fitBounds(t)}))}))},resetZoom:function(){var e=this;this.$nextTick((function(){if(e.initialized){e.showMap=e.show,e.initialized=!0;for(var t=new google.maps.LatLngBounds,i=0;i<e.marker.length;i++)t.extend(e.marker[i].getPosition());e.map.fitBounds(t)}}))}},mounted:function(){this.lat&&this.lng&&(this.singleAddressOnly=!0),!0===this.show&&(this.showMap=this.show,this.$forceUpdate(),!1===this.initialized&&(this.init(),this.initialized=!0))}}}).call(this,i(16))},541:function(e,t,i){"use strict";var a={name:"KachelComponent",props:{image:{default:"",type:String},light:{default:!1,type:Boolean},theme:{default:"",type:String},offer:{default:null,type:Object},lat:{default:null,type:[String,Number]},lng:{default:null,type:[String,Number]},type:{default:"colorbox",type:String},title:{default:null,type:String},text:{default:null,type:String},date:{default:null,type:String},text2:{default:null,type:String},link:{default:"",type:String},gradient:{default:!1,type:Boolean},showContent:{default:!0,type:Boolean},event:{default:!1,type:Boolean}},data:function(){return{themeName:1,kategorieName:null}},created:function(){this.themeName=!this.theme&&Math.round(4*Math.random()+1),1===this.themeName?this.kategorieName="Bildung & Betreuung":2===this.themeName?this.kategorieName="Freizeit & Kultur":3===this.themeName?this.kategorieName="Übergang Schule & Beruf":4===this.themeName?this.kategorieName="Beratung & Unterstützung":5===this.themeName&&(this.kategorieName="Vorsorge & Gesundheit")}},s=(i(499),i(37)),n=Object(s.a)(a,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return"colorbox"===e.type?i("div",{staticClass:"kachel",class:[e.themeName?"kachel--theme-"+(e.offer?e.offer.theme:e.themeName):"",{"kachel--light":e.light},{"kachel--with-image":e.image}],style:{"background-image":e.image?"url("+e.image+")":"none"},attrs:{"data-lat":e.lat,"data-lng":e.lng}},[i("a",{attrs:{href:e.link}},[e.image&&!1!==e.gradient?i("div",{staticClass:"kachel__gradient"}):e._e(),e._v(" "),i("div",{staticClass:"kachel__inner",class:{"kachel__inner--nocontent":!1===e.showContent}},[e.kategorieName?i("div",{staticClass:"kachel__category"},[e._v(e._s(e.offer?e.offer.category:e.kategorieName))]):i("div",{staticClass:"kachel__category"},[e._v(e._s(e.title?e.title:e.offer?e.offer.category:e.loremIpsum(3)))]),e._v(" "),!1!==e.showContent?i("div",{staticClass:"kachel__content"},[i("h3",{staticClass:"kachel__headline"},[e._v("\n                    "+e._s(e.title?e.title:e.offer?e.offer.title:e.loremIpsum(2))+"\n                ")]),e._v(" "),i("h4",{staticClass:"kachel__subheadline"},[e._v(e._s(e.text?e.text:e.offer?e.offer.text:e.loremIpsum(2)))]),e._v(" "),e.offer?e._e():i("p",[e._v(e._s(e.text2?e.text2:e.loremIpsum(6)))]),e._v(" "),e.event?e._e():i("div",{staticClass:"kachel__date"},[e._v(e._s(e.date?e.date:e.offer?e.offer.date:"Heute, 01.01.2018"))]),e._v(" "),e.event?i("div",{staticClass:"kachel__footer"},[i("div",{staticClass:"kachel__location"},[e._v(e._s(e.offer?e.offer.district:"Alle Stadtteile"))]),e._v(" "),e.offer&&e.offer.targetGroup||!e.offer?i("div",{staticClass:"kachel__user"},[e._v(e._s(e.offer?e.offer.targetGroup:"für Mütter und Kinder"))]):e._e(),e._v(" "),i("div",{staticClass:"kachel__calendar"},[e._v(e._s(e.offer?e.offer.date:"Jeden Montag"))]),e._v(" "),i("div",{staticClass:"kachel__time"},[e._v(e._s(e.offer?e.offer.time+" Uhr":"15:00 - 18:00 Uhr"))])]):e._e()]):e._e()]),e._v(" "),i("div",{staticClass:"kachel__arrow"})]),e._v(" "),i("div",{staticClass:"kachel__map-arrow"})]):i("div",{staticClass:"kachel kachel--image-text",attrs:{"data-lat":e.lat,"data-lng":e.lng}},[i("a",{attrs:{href:e.link}},[i("div",{staticClass:"kachel__text"},[i("h3",{staticClass:"kachel__headline"},[e._v("\n                "+e._s(e.title?e.title:e.loremIpsum(2))+"\n            ")]),e._v(" "),i("p",[e._v(e._s(e.text?e.text:e.loremIpsum(15)))])]),e._v(" "),e.image?i("div",{staticClass:"kachel__image-wrapper"},[i("div",{staticClass:"kachel__image",style:{"background-image":e.image?"url("+e.image+")":"none"}})]):e._e(),e._v(" "),i("div",{staticClass:"kachel__arrow"})])])}),[],!1,null,null,null);t.a=n.exports},557:function(e,t,i){"use strict";i.r(t);var a=i(522).a,s=(i(500),i(37)),n=Object(s.a)(a,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{directives:[{name:"show",rawName:"v-show",value:e.showMap,expression:"showMap"}],staticClass:"custom-map"},[e.singleAddressOnly?i("div",{staticClass:"custom-map__click",on:{click:function(t){return e.openLink()}}}):e._e(),e._v(" "),i("div",{ref:"items",staticClass:"custom-map__items"},[e._t("default")],2),e._v(" "),i("div",[i("div",{ref:"custom_map",staticClass:"custom-map__map",style:{height:e.height?e.height+"px":""}})])])}),[],!1,null,null,null);t.default=n.exports}}]);