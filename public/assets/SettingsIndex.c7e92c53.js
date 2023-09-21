import{u as M,b as P}from"./vue-router.746ec05f.js";import{d as R}from"./main.f5ac513d.js";import{B as S,a as j}from"./BaseListItem.e2a78b01.js";import{v as y}from"./vue-i18n.c6de3223.js";import{r as L,a as O,w as U,O as s,j as c,k as f,U as a,l as e,P as r,u as g,z as $,M as C,aW as E,F}from"./@vue.8cc12e6e.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./axios.7b768d2b.js";import"./pinia.32c02ade.js";import"./lodash.8ab3583e.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./@vuelidate.c7422de1.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";const H={class:"w-full mb-6 select-wrapper xl:hidden"},N={class:"flex"},z={class:"hidden mt-1 xl:block min-w-[240px]"},A={class:"w-full overflow-hidden"},_t={__name:"SettingsIndex",setup(G){const{t:B}=y.useI18n();let n=L({});const u=R(),l=M(),m=P(),p=O(()=>u.settingMenu.map(t=>Object.assign({},t,{title:B(t.title)})));U(()=>{l.path==="/admin/settings"&&m.push("/admin/settings/account-settings");const t=p.value.find(i=>i.link===l.path);n.value=t});function v(t){return l.path.indexOf(t)>-1}function h(t){return m.push(t.link)}return(t,i)=>{const d=s("BaseBreadcrumbItem"),b=s("BaseBreadcrumb"),k=s("BasePageHeader"),w=s("BaseMultiselect"),V=s("BaseIcon"),x=s("RouterView"),I=s("BasePage");return c(),f(I,null,{default:a(()=>[e(k,{title:t.$tc("settings.setting",1),class:"mb-6"},{default:a(()=>[e(b,null,{default:a(()=>[e(d,{title:t.$t("general.home"),to:"/admin/dashboard"},null,8,["title"]),e(d,{title:t.$tc("settings.setting",2),to:"/admin/settings/account-settings",active:""},null,8,["title"])]),_:1})]),_:1},8,["title"]),r("div",H,[e(w,{modelValue:g(n),"onUpdate:modelValue":[i[0]||(i[0]=o=>$(n)?n.value=o:n=o),h],options:p.value,"can-deselect":!1,"value-prop":"title","track-by":"title",label:"title",object:""},null,8,["modelValue","options"])]),r("div",N,[r("div",z,[e(j,null,{default:a(()=>[(c(!0),C(F,null,E(g(u).settingMenu,(o,_)=>(c(),f(S,{key:_,title:t.$t(o.title),to:o.link,active:v(o.link),index:_,class:"py-3"},{icon:a(()=>[e(V,{name:o.icon},null,8,["name"])]),_:2},1032,["title","to","active","index"]))),128))]),_:1})]),r("div",A,[e(x)])])]),_:1})}}};export{_t as default};