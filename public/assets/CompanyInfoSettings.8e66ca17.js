import{r as V,f as R,a as F,O as i,j as w,k as N,U as l,P as d,X as g,u as o,by as T,l as a,as as k,g as A,R as q,i as ee,M as L,z as te,F as ae}from"./@vue.8cc12e6e.js";import{b as O,c as E,d as J}from"./main.f5ac513d.js";import{v as P}from"./vue-i18n.c6de3223.js";import{c as h,r as z,s as oe,d as X,m as se}from"./@vuelidate.c7422de1.js";import{b as ne}from"./vue-router.746ec05f.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./axios.7b768d2b.js";import"./pinia.32c02ade.js";import"./lodash.8ab3583e.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";const le={class:"flex justify-between w-full"},re={class:"px-6 pt-6"},ie={class:"font-medium text-lg text-left"},de={class:"mt-2 text-sm leading-snug text-gray-500",style:{"max-width":"680px"}},me=["onSubmit"],ue={class:"p-4 sm:p-6 space-y-4"},pe={class:"z-0 flex justify-end p-4 bg-gray-50 border-modal-bg"},ce={__name:"DeleteCompanyModal",setup(H){const p=O(),v=E(),I=J(),$=ne(),{t:S}=P.useI18n();let m=V(!1);const e=R({id:p.selectedCompany.id,name:null}),b=F(()=>v.active&&v.componentName==="DeleteCompanyModal"),f={formData:{name:{required:h.withMessage(S("validation.required"),z),sameAsName:h.withMessage(S("validation.company_name_not_same"),oe(p.selectedCompany.name))}}},c=X(f,{formData:e},{$scope:!1});async function B(){if(c.value.$touch(),c.value.$invalid)return!0;const y=p.companies[0];m.value=!0;try{const _=await p.deleteCompany(e);console.log(_.data.success),_.data.success&&(u(),await p.setSelectedCompany(y),$.push("/admin/dashboard"),await I.setIsAppLoaded(!1),await I.bootstrap()),m.value=!1}catch{m.value=!1}}function D(){e.id=null,e.name="",c.value.$reset()}function u(){v.closeModal(),setTimeout(()=>{D(),c.value.$reset()},300)}return(y,_)=>{const U=i("BaseInput"),x=i("BaseInputGroup"),n=i("BaseButton"),t=i("BaseIcon"),M=i("BaseModal");return w(),N(M,{show:b.value,onClose:u},{default:l(()=>[d("div",le,[d("div",re,[d("h6",ie,g(o(v).title),1),d("p",de,g(y.$t("settings.company_info.delete_company_modal_desc",{company:o(p).selectedCompany.name})),1)])]),d("form",{action:"",onSubmit:T(B,["prevent"])},[d("div",ue,[a(x,{label:y.$t("settings.company_info.delete_company_modal_label",{company:o(p).selectedCompany.name}),error:o(c).formData.name.$error&&o(c).formData.name.$errors[0].$message,required:""},{default:l(()=>[a(U,{modelValue:e.name,"onUpdate:modelValue":_[0]||(_[0]=r=>e.name=r),invalid:o(c).formData.name.$error,onInput:_[1]||(_[1]=r=>o(c).formData.name.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"])]),d("div",pe,[a(n,{class:"mr-3 text-sm",variant:"primary-outline",outline:"",type:"button",onClick:u},{default:l(()=>[k(g(y.$t("general.cancel")),1)]),_:1}),a(n,{loading:o(m),disabled:o(m),variant:"danger",type:"submit"},{left:l(r=>[o(m)?q("",!0):(w(),N(t,{key:0,name:"TrashIcon",class:A(r.class)},null,8,["class"]))]),default:l(()=>[k(" "+g(y.$t("general.delete")),1)]),_:1},8,["loading","disabled"])])],40,me)]),_:1},8,["show"])}}},_e=["onSubmit"],fe={key:0,class:"py-5"},ye={class:"text-lg leading-6 font-medium text-gray-900"},ge={class:"mt-2 max-w-xl text-sm text-gray-500"},ve={class:"mt-5"},Ae={__name:"CompanyInfoSettings",setup(H){const p=O(),v=J(),I=E(),{t:$}=P.useI18n(),S=ee("utils");let m=V(!1);const e=R({name:null,logo:null,address:{address_street_1:"",address_street_2:"",website:"",country_id:null,state:"",city:"",phone:"",zip:""}});S.mergeSettings(e,{...p.selectedCompany});let b=V([]),f=V(null),c=V(null);const B=V(!1);e.logo&&b.value.push({image:e.logo});const D=F(()=>({name:{required:h.withMessage($("validation.required"),z),minLength:h.withMessage($("validation.name_min_length"),se(3))},address:{country_id:{required:h.withMessage($("validation.required"),z)}}})),u=X(D,F(()=>e));v.fetchCountries();function y(n,t,M,r){c.value=r.name,f.value=t}function _(){f.value=null,B.value=!0}async function U(){if(u.value.$touch(),u.value.$invalid)return!0;if(m.value=!0,(await p.updateCompany(e)).data.data){if(f.value||B.value){let t=new FormData;f.value&&t.append("company_logo",JSON.stringify({name:c.value,data:f.value})),t.append("is_company_logo_removed",B.value),await p.updateCompanyLogo(t),f.value=null,B.value=!1}m.value=!1}m.value=!1}function x(n){I.openModal({title:$("settings.company_info.are_you_absolutely_sure"),componentName:"DeleteCompanyModal",size:"sm"})}return(n,t)=>{const M=i("BaseFileUploader"),r=i("BaseInputGroup"),G=i("BaseInputGrid"),C=i("BaseInput"),K=i("BaseMultiselect"),j=i("BaseTextarea"),Q=i("BaseIcon"),W=i("BaseButton"),Y=i("BaseDivider"),Z=i("BaseSettingCard");return w(),L(ae,null,[d("form",{onSubmit:T(U,["prevent"])},[a(Z,{title:n.$t("settings.company_info.company_info"),description:n.$t("settings.company_info.section_description")},{default:l(()=>[a(G,{class:"mt-5"},{default:l(()=>[a(r,{label:n.$tc("settings.company_info.company_logo")},{default:l(()=>[a(M,{modelValue:o(b),"onUpdate:modelValue":t[0]||(t[0]=s=>te(b)?b.value=s:b=s),base64:"",onChange:y,onRemove:_},null,8,["modelValue"])]),_:1},8,["label"])]),_:1}),a(G,{class:"mt-5"},{default:l(()=>[a(r,{label:n.$tc("settings.company_info.company_name"),error:o(u).name.$error&&o(u).name.$errors[0].$message,required:""},{default:l(()=>[a(C,{modelValue:e.name,"onUpdate:modelValue":t[1]||(t[1]=s=>e.name=s),invalid:o(u).name.$error,onBlur:t[2]||(t[2]=s=>o(u).name.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"]),a(r,{label:n.$tc("settings.company_info.phone")},{default:l(()=>[a(C,{modelValue:e.address.phone,"onUpdate:modelValue":t[3]||(t[3]=s=>e.address.phone=s)},null,8,["modelValue"])]),_:1},8,["label"]),a(r,{label:n.$tc("settings.company_info.country"),error:o(u).address.country_id.$error&&o(u).address.country_id.$errors[0].$message,required:""},{default:l(()=>[a(K,{modelValue:e.address.country_id,"onUpdate:modelValue":t[4]||(t[4]=s=>e.address.country_id=s),label:"name",invalid:o(u).address.country_id.$error,options:o(v).countries,"value-prop":"id","can-deselect":!0,"can-clear":!1,searchable:"","track-by":"name"},null,8,["modelValue","invalid","options"])]),_:1},8,["label","error"]),a(r,{label:n.$tc("settings.company_info.state")},{default:l(()=>[a(C,{modelValue:e.address.state,"onUpdate:modelValue":t[5]||(t[5]=s=>e.address.state=s),name:"state",type:"text"},null,8,["modelValue"])]),_:1},8,["label"]),a(r,{label:n.$tc("settings.company_info.city")},{default:l(()=>[a(C,{modelValue:e.address.city,"onUpdate:modelValue":t[6]||(t[6]=s=>e.address.city=s),type:"text"},null,8,["modelValue"])]),_:1},8,["label"]),a(r,{label:n.$tc("settings.company_info.zip")},{default:l(()=>[a(C,{modelValue:e.address.zip,"onUpdate:modelValue":t[7]||(t[7]=s=>e.address.zip=s)},null,8,["modelValue"])]),_:1},8,["label"]),d("div",null,[a(r,{label:n.$tc("settings.company_info.address")},{default:l(()=>[a(j,{modelValue:e.address.address_street_1,"onUpdate:modelValue":t[8]||(t[8]=s=>e.address.address_street_1=s),rows:"2"},null,8,["modelValue"])]),_:1},8,["label"]),a(j,{modelValue:e.address.address_street_2,"onUpdate:modelValue":t[9]||(t[9]=s=>e.address.address_street_2=s),rows:"2",row:2,class:"mt-2"},null,8,["modelValue"])])]),_:1}),a(W,{loading:o(m),disabled:o(m),type:"submit",class:"mt-6"},{left:l(s=>[o(m)?q("",!0):(w(),N(Q,{key:0,class:A(s.class),name:"SaveIcon"},null,8,["class"]))]),default:l(()=>[k(" "+g(n.$tc("settings.company_info.save")),1)]),_:1},8,["loading","disabled"]),o(p).companies.length!==1?(w(),L("div",fe,[a(Y,{class:"my-4"}),d("h3",ye,g(n.$tc("settings.company_info.delete_company")),1),d("div",ge,[d("p",null,g(n.$tc("settings.company_info.delete_company_description")),1)]),d("div",ve,[d("button",{type:"button",class:"inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm",onClick:x},g(n.$tc("general.delete")),1)])])):q("",!0)]),_:1},8,["title","description"])],40,_e),a(ce)],64)}}};export{Ae as default};