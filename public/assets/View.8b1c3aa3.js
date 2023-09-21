import{v as re}from"./vue-i18n.c6de3223.js";import{u as ne,b as ie}from"./vue-router.746ec05f.js";import{l as de}from"./lodash.8ab3583e.js";import{c as ue,k as me,j as ce,e as pe,g as C}from"./main.f5ac513d.js";import{_ as fe}from"./EstimateIndexDropdown.e28cee42.js";import{_ as _e}from"./SendEstimateModal.403a3e08.js";import{L as ve}from"./LoadingIcon.f4bad6f3.js";import{r as p,f as ye,a as B,e as ge,O as n,j as u,M as k,l as a,k as y,U as o,P as i,u as I,as as N,X as v,R as g,F as P,aW as be,g as he}from"./@vue.8cc12e6e.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./axios.7b768d2b.js";import"./pinia.32c02ade.js";import"./@vuelidate.c7422de1.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";import"./mail-driver.f62a08cc.js";const xe={class:"mr-3 text-sm"},Be={class:"fixed top-0 left-0 hidden h-full pt-16 pb-[6.4rem] ml-56 bg-white xl:ml-64 w-88 xl:block"},ke={class:"flex items-center justify-between px-4 pt-8 pb-2 border border-gray-200 border-solid height-full"},Se={class:"mb-6"},Te={class:"flex mb-6 ml-3",role:"group","aria-label":"First group"},we={class:"px-4 py-1 pb-2 mb-1 mb-2 text-sm border-b border-gray-200 border-solid"},Ee={class:"flex-2"},Ie={class:"mt-1 mb-2 text-xs not-italic font-medium leading-5 text-gray-600"},Fe={class:"flex-1 whitespace-nowrap right"},Ve={class:"text-sm not-italic font-normal leading-5 text-right text-gray-600 est-date"},De={key:0,class:"flex justify-center p-4 items-center"},$e={key:1,class:"flex justify-center px-4 mt-5 text-sm text-gray-600"},Le={class:"flex flex-col min-h-0 mt-8 overflow-hidden",style:{height:"75vh"}},Ae=["src"],dt={__name:"View",setup(Ne){const R=ue(),F=me(),H=ce(),M=pe(),{t:f}=re.useI18n(),r=p(null),b=ne();ie();const S=p(!1),h=p(!1),T=p(!1),d=p(null),x=p(1),V=p(1),j=p(null),t=ye({orderBy:null,orderByField:null,searchText:null}),O=B(()=>r.value.estimate_number),z=B(()=>t.orderBy==="asc"||t.orderBy==null);B(()=>z.value?f("general.ascending"):f("general.descending"));const G=B(()=>`/estimates/pdf/${r.value.unique_hash}`);B(()=>r.value&&r.value.id?estimate.value.id:null),ge(b,(e,l)=>{e.name==="estimates.view"&&U()}),w(),U(),c=de.exports.debounce(c,500);function q(e){return b.params.id==e}async function w(e,l=!1){if(h.value)return;let m={};t.searchText!==""&&t.searchText!==null&&t.searchText!==void 0&&(m.search=t.searchText),t.orderBy!==null&&t.orderBy!==void 0&&(m.orderBy=t.orderBy),t.orderByField!==null&&t.orderByField!==void 0&&(m.orderByField=t.orderByField),h.value=!0;let E=await F.fetchEstimates({page:e,...m});h.value=!1,d.value=d.value?d.value:[],d.value=[...d.value,...E.data.data],x.value=e||1,V.value=E.data.meta.last_page;let _=d.value.find(D=>D.id==b.params.id);l==!1&&!_&&x.value<V.value&&Object.keys(m).length===0&&w(++x.value),_&&setTimeout(()=>{l==!1&&W()},500)}function W(){const e=document.getElementById(`estimate-${b.params.id}`);e&&(e.scrollIntoView({behavior:"smooth"}),e.classList.add("shake"),X())}function X(){j.value.addEventListener("scroll",e=>{e.target.scrollTop>0&&e.target.scrollTop+e.target.clientHeight>e.target.scrollHeight-200&&x.value<V.value&&w(++x.value,!0)})}async function U(){T.value=!0;let e=await F.fetchEstimate(b.params.id);e.data&&(T.value=!1,r.value={...e.data.data})}async function c(){d.value=[],w()}function J(){return t.orderBy==="asc"?(t.orderBy="desc",c(),!0):(t.orderBy="asc",c(),!0)}async function K(){H.openDialog({title:f("general.are_you_sure"),message:f("estimates.confirm_mark_as_sent"),yesLabel:f("general.ok"),noLabel:f("general.cancel"),variant:"primary",hideNoButton:!1,size:"lg"}).then(e=>{S.value=!1,e&&(F.markAsSent({id:r.value.id,status:"SENT"}),r.value.status="SENT",S.value=!0),S.value=!1})}async function Q(e){R.openModal({title:f("estimates.send_estimate"),componentName:"SendEstimateModal",id:r.value.id,data:r.value})}function Y(){let e=d.value.findIndex(l=>l.id===r.value.id);d.value[e]&&(d.value[e].status="SENT",r.value.status="SENT")}return(e,l)=>{const m=n("BaseButton"),E=n("BasePageHeader"),_=n("BaseIcon"),D=n("BaseInput"),$=n("BaseRadio"),L=n("BaseInputGroup"),A=n("BaseDropdownItem"),Z=n("BaseDropdown"),ee=n("BaseText"),te=n("BaseEstimateStatusBadge"),ae=n("BaseFormatMoney"),se=n("router-link"),oe=n("BasePage");return u(),k(P,null,[a(_e,{onUpdate:Y}),r.value?(u(),y(oe,{key:0,class:"xl:pl-96 xl:ml-8"},{default:o(()=>[a(E,{title:O.value},{actions:o(()=>[i("div",xe,[r.value.status==="DRAFT"&&I(M).hasAbilities(I(C).EDIT_ESTIMATE)?(u(),y(m,{key:0,disabled:S.value,"content-loading":T.value,variant:"primary-outline",onClick:K},{default:o(()=>[N(v(e.$t("estimates.mark_as_sent")),1)]),_:1},8,["disabled","content-loading"])):g("",!0)]),r.value.status==="DRAFT"&&I(M).hasAbilities(I(C).SEND_ESTIMATE)?(u(),y(m,{key:0,"content-loading":T.value,variant:"primary",class:"text-sm",onClick:Q},{default:o(()=>[N(v(e.$t("estimates.send_estimate")),1)]),_:1},8,["content-loading"])):g("",!0),a(fe,{class:"ml-3",row:r.value},null,8,["row"])]),_:1},8,["title"]),i("div",Be,[i("div",ke,[i("div",Se,[a(D,{modelValue:t.searchText,"onUpdate:modelValue":l[0]||(l[0]=s=>t.searchText=s),placeholder:e.$t("general.search"),type:"text",variant:"gray",onInput:l[1]||(l[1]=s=>c())},{right:o(()=>[a(_,{name:"SearchIcon",class:"text-gray-400"})]),_:1},8,["modelValue","placeholder"])]),i("div",Te,[a(Z,{class:"ml-3",position:"bottom-start","width-class":"w-45","position-class":"left-0"},{activator:o(()=>[a(m,{size:"md",variant:"gray"},{default:o(()=>[a(_,{name:"FilterIcon"})]),_:1})]),default:o(()=>[i("div",we,v(e.$t("general.sort_by")),1),a(A,{class:"flex px-4 py-2 cursor-pointer"},{default:o(()=>[a(L,{class:"-mt-3 font-normal"},{default:o(()=>[a($,{id:"filter_estimate_date",modelValue:t.orderByField,"onUpdate:modelValue":[l[2]||(l[2]=s=>t.orderByField=s),c],label:e.$t("reports.estimates.estimate_date"),size:"sm",name:"filter",value:"estimate_date"},null,8,["modelValue","label"])]),_:1})]),_:1}),a(A,{class:"flex px-4 py-2 cursor-pointer"},{default:o(()=>[a(L,{class:"-mt-3 font-normal"},{default:o(()=>[a($,{id:"filter_due_date",modelValue:t.orderByField,"onUpdate:modelValue":[l[3]||(l[3]=s=>t.orderByField=s),c],label:e.$t("estimates.due_date"),value:"expiry_date",size:"sm",name:"filter"},null,8,["modelValue","label"])]),_:1})]),_:1}),a(A,{class:"flex px-4 py-2 cursor-pointer"},{default:o(()=>[a(L,{class:"-mt-3 font-normal"},{default:o(()=>[a($,{id:"filter_estimate_number",modelValue:t.orderByField,"onUpdate:modelValue":[l[4]||(l[4]=s=>t.orderByField=s),c],label:e.$t("estimates.estimate_number"),value:"estimate_number",size:"sm",name:"filter"},null,8,["modelValue","label"])]),_:1})]),_:1})]),_:1}),a(m,{class:"ml-1",size:"md",variant:"gray",onClick:J},{default:o(()=>[z.value?(u(),y(_,{key:0,name:"SortAscendingIcon"})):(u(),y(_,{key:1,name:"SortDescendingIcon"}))]),_:1})])]),i("div",{ref_key:"estimateListSection",ref:j,class:"h-full overflow-y-scroll border-l border-gray-200 border-solid base-scroll"},[(u(!0),k(P,null,be(d.value,(s,le)=>(u(),k("div",{key:le},[s?(u(),y(se,{key:0,id:"estimate-"+s.id,to:`/admin/estimates/${s.id}/view`,class:he(["flex justify-between side-estimate p-4 cursor-pointer hover:bg-gray-100 items-center border-l-4 border-transparent",{"bg-gray-100 border-l-4 border-primary-500 border-solid":q(s.id)}]),style:{"border-bottom":"1px solid rgba(185, 193, 209, 0.41)"}},{default:o(()=>[i("div",Ee,[a(ee,{text:s.customer.name,length:30,class:"pr-2 mb-2 text-sm not-italic font-normal leading-5 text-black capitalize truncate"},null,8,["text"]),i("div",Ie,v(s.estimate_number),1),a(te,{status:s.status,class:"px-1 text-xs"},{default:o(()=>[N(v(s.status),1)]),_:2},1032,["status"])]),i("div",Fe,[a(ae,{amount:s.total,currency:s.customer.currency,class:"block mb-2 text-xl not-italic font-semibold leading-8 text-right text-gray-900"},null,8,["amount","currency"]),i("div",Ve,v(s.formatted_estimate_date),1)])]),_:2},1032,["id","to","class"])):g("",!0)]))),128)),h.value?(u(),k("div",De,[a(ve,{class:"h-6 m-1 animate-spin text-primary-400"})])):g("",!0),!d.value?.length&&!h.value?(u(),k("p",$e,v(e.$t("estimates.no_matching_estimates")),1)):g("",!0)],512)]),i("div",Le,[i("iframe",{src:`${G.value}`,class:"flex-1 border border-gray-400 border-solid rounded-md bg-white frame-style"},null,8,Ae)])]),_:1})):g("",!0)],64)}}};export{dt as default};