import{r as V,f as O,a as f,e as Y,O as c,j as I,M as j,l as r,U as i,u as e,g as h,as as C,X as M,P as D,by as U,k as T,R as Z}from"./@vue.8cc12e6e.js";import{b as R,d as X}from"./main.f5ac513d.js";import{v as A}from"./vue-i18n.c6de3223.js";import{d as H,c as g,r as _}from"./@vuelidate.c7422de1.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./axios.7b768d2b.js";import"./vue-router.746ec05f.js";import"./pinia.32c02ade.js";import"./lodash.8ab3583e.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";const J=["onSubmit"],K=["onSubmit"],Se={__name:"PreferencesSetting",setup(L){const b=R(),d=X(),{t:m,tm:Q}=A.useI18n();let y=V(!1),v=V(!1),s=V(!1);const a=O({...b.selectedCompanySettings});f(()=>d.config.retrospective_edits.map(t=>(t.title=m(t.key),t))),Y(()=>a.carbon_date_format,t=>{if(t){const n=d.dateFormats.find(u=>u.carbon_format_value===t);a.moment_date_format=n.moment_format_value}});const S=f({get:()=>a.discount_per_item==="YES",set:async t=>{const n=t?"YES":"NO";let u={settings:{discount_per_item:n}};a.discount_per_item=n,await b.updateCompanySettings({data:u,message:"general.setting_updated"})}}),$=f({get:()=>a.automatically_expire_public_links==="YES",set:async t=>{const n=t?"YES":"NO";a.automatically_expire_public_links=n}}),z=f(()=>({currency:{required:g.withMessage(m("validation.required"),_)},language:{required:g.withMessage(m("validation.required"),_)},carbon_date_format:{required:g.withMessage(m("validation.required"),_)},moment_date_format:{required:g.withMessage(m("validation.required"),_)},time_zone:{required:g.withMessage(m("validation.required"),_)},fiscal_year:{required:g.withMessage(m("validation.required"),_)}})),l=H(z,f(()=>a));F();async function F(){s.value=!0,Promise.all([d.fetchCurrencies(),d.fetchDateFormats(),d.fetchTimeZones()]).then(([t])=>{s.value=!1})}async function N(){if(l.value.$touch(),l.value.$invalid)return;let t={settings:{...a}};y.value=!0,delete t.settings.link_expiry_days,await b.updateCompanySettings({data:t,message:"settings.preferences.updated_message"}),y.value=!1}async function P(){v.value=!0,await b.updateCompanySettings({data:{settings:{link_expiry_days:a.link_expiry_days,automatically_expire_public_links:a.automatically_expire_public_links}},message:"settings.preferences.updated_message"}),v.value=!1}return(t,n)=>{const u=c("BaseMultiselect"),p=c("BaseInputGroup"),x=c("BaseInputGrid"),k=c("BaseIcon"),B=c("BaseButton"),q=c("BaseDivider"),w=c("BaseSwitchSection"),E=c("BaseInput"),G=c("BaseSettingCard");return I(),j("form",{action:"",class:"relative",onSubmit:U(N,["prevent"])},[r(G,{title:t.$t("settings.menu_title.preferences"),description:t.$t("settings.preferences.general_settings")},{default:i(()=>[r(x,{class:"mt-5"},{default:i(()=>[r(p,{"content-loading":e(s),label:t.$tc("settings.preferences.currency"),"help-text":t.$t("settings.preferences.company_currency_unchangeable"),error:e(l).currency.$error&&e(l).currency.$errors[0].$message,required:""},{default:i(()=>[r(u,{modelValue:a.currency,"onUpdate:modelValue":n[0]||(n[0]=o=>a.currency=o),"content-loading":e(s),options:e(d).currencies,label:"name","value-prop":"id",searchable:!0,"track-by":"name",invalid:e(l).currency.$error,disabled:"",class:"w-full"},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["content-loading","label","help-text","error"]),r(p,{label:t.$tc("settings.preferences.default_language"),"content-loading":e(s),error:e(l).language.$error&&e(l).language.$errors[0].$message,required:""},{default:i(()=>[r(u,{modelValue:a.language,"onUpdate:modelValue":n[1]||(n[1]=o=>a.language=o),"content-loading":e(s),options:e(d).config.languages,label:"name","value-prop":"code",class:"w-full","track-by":"name",searchable:!0,invalid:e(l).language.$error},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),r(p,{label:t.$tc("settings.preferences.time_zone"),"content-loading":e(s),error:e(l).time_zone.$error&&e(l).time_zone.$errors[0].$message,required:""},{default:i(()=>[r(u,{modelValue:a.time_zone,"onUpdate:modelValue":n[2]||(n[2]=o=>a.time_zone=o),"content-loading":e(s),options:e(d).timeZones,label:"key","value-prop":"value","track-by":"key",searchable:!0,invalid:e(l).time_zone.$error},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),r(p,{label:t.$tc("settings.preferences.date_format"),"content-loading":e(s),error:e(l).carbon_date_format.$error&&e(l).carbon_date_format.$errors[0].$message,required:""},{default:i(()=>[r(u,{modelValue:a.carbon_date_format,"onUpdate:modelValue":n[3]||(n[3]=o=>a.carbon_date_format=o),"content-loading":e(s),options:e(d).dateFormats,label:"display_date","value-prop":"carbon_format_value","track-by":"display_date",searchable:"",invalid:e(l).carbon_date_format.$error,class:"w-full"},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),r(p,{"content-loading":e(s),error:e(l).fiscal_year.$error&&e(l).fiscal_year.$errors[0].$message,label:t.$tc("settings.preferences.fiscal_year"),required:""},{default:i(()=>[r(u,{modelValue:a.fiscal_year,"onUpdate:modelValue":n[4]||(n[4]=o=>a.fiscal_year=o),"content-loading":e(s),options:e(d).config.fiscal_years,label:"key","value-prop":"value",invalid:e(l).fiscal_year.$error,"track-by":"key",searchable:!0,class:"w-full"},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["content-loading","error","label"])]),_:1}),r(B,{"content-loading":e(s),disabled:e(y),loading:e(y),type:"submit",class:"mt-6"},{left:i(o=>[r(k,{name:"SaveIcon",class:h(o.class)},null,8,["class"])]),default:i(()=>[C(" "+M(t.$tc("settings.company_info.save")),1)]),_:1},8,["content-loading","disabled","loading"]),r(q,{class:"mt-6 mb-2"}),D("ul",null,[D("form",{onSubmit:U(P,["prevent"])},[r(w,{modelValue:$.value,"onUpdate:modelValue":n[5]||(n[5]=o=>$.value=o),title:t.$t("settings.preferences.expire_public_links"),description:t.$t("settings.preferences.expire_setting_description")},null,8,["modelValue","title","description"]),$.value?(I(),T(p,{key:0,"content-loading":e(s),label:t.$t("settings.preferences.expire_public_links"),class:"mt-2 mb-4"},{default:i(()=>[r(E,{modelValue:a.link_expiry_days,"onUpdate:modelValue":n[6]||(n[6]=o=>a.link_expiry_days=o),disabled:a.automatically_expire_public_links==="NO","content-loading":e(s),type:"number"},null,8,["modelValue","disabled","content-loading"])]),_:1},8,["content-loading","label"])):Z("",!0),r(B,{"content-loading":e(s),disabled:e(v),loading:e(v),type:"submit",class:"mt-6"},{left:i(o=>[r(k,{name:"SaveIcon",class:h(o.class)},null,8,["class"])]),default:i(()=>[C(" "+M(t.$tc("general.save")),1)]),_:1},8,["content-loading","disabled","loading"])],40,K),r(q,{class:"mt-6 mb-2"}),r(w,{modelValue:S.value,"onUpdate:modelValue":n[7]||(n[7]=o=>S.value=o),title:t.$t("settings.preferences.discount_per_item"),description:t.$t("settings.preferences.discount_setting_description")},null,8,["modelValue","title","description"])])]),_:1},8,["title","description"])],40,J)}}};export{Se as default};