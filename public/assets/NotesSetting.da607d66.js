import{v as z}from"./vue-i18n.c6de3223.js";import{j as C,u as I,e as x,c as M,g as w}from"./main.f5ac513d.js";import{u as A,_ as T}from"./NoteModal.86bd9d44.js";import{u as E}from"./vue-router.746ec05f.js";import{i as j,O as l,j as u,k as p,U as e,u as d,l as r,as as S,X as D,R as k,r as O,a as F,M as P,g as G,F as V,f as H}from"./@vue.8cc12e6e.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./axios.7b768d2b.js";import"./pinia.32c02ade.js";import"./lodash.8ab3583e.js";import"./@vuelidate.c7422de1.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";import"./payment.333e3a22.js";const L={__name:"NoteIndexDropdown",props:{row:{type:Object,default:null},table:{type:Object,default:null},loadData:{type:Function,default:null}},setup(g){const c=g,B=C(),_=I(),{t:o}=z.useI18n(),a=A(),h=E(),y=x(),b=M();j("utils");function v(n){a.fetchNote(n),b.openModal({title:o("settings.customization.notes.edit_note"),componentName:"NoteModal",size:"md",refreshData:c.loadData})}function s(n){B.openDialog({title:o("general.are_you_sure"),message:o("settings.customization.notes.note_confirm_delete"),yesLabel:o("general.yes"),noLabel:o("general.no"),variant:"danger",hideNoButton:!1,size:"lg"}).then(async()=>{(await a.deleteNote(n)).data.success?_.showNotification({type:"success",message:o("settings.customization.notes.deleted_message")}):_.showNotification({type:"error",message:o("settings.customization.notes.already_in_use")}),c.loadData&&c.loadData()})}return(n,t)=>{const i=l("BaseIcon"),m=l("BaseButton"),N=l("BaseDropdownItem"),f=l("BaseDropdown");return u(),p(f,null,{activator:e(()=>[d(h).name==="notes.view"?(u(),p(m,{key:0,variant:"primary"},{default:e(()=>[r(i,{name:"DotsHorizontalIcon",class:"h-5 text-white"})]),_:1})):(u(),p(i,{key:1,name:"DotsHorizontalIcon",class:"h-5 text-gray-500"}))]),default:e(()=>[d(y).hasAbilities(d(w).MANAGE_NOTE)?(u(),p(N,{key:0,onClick:t[0]||(t[0]=$=>v(g.row.id))},{default:e(()=>[r(i,{name:"PencilIcon",class:"w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"}),S(" "+D(n.$t("general.edit")),1)]),_:1})):k("",!0),d(y).hasAbilities(d(w).MANAGE_NOTE)?(u(),p(N,{key:1,onClick:t[1]||(t[1]=$=>s(g.row.id))},{default:e(()=>[r(i,{name:"TrashIcon",class:"w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"}),S(" "+D(n.$t("general.delete")),1)]),_:1})):k("",!0)]),_:1})}}},gt={__name:"NotesSetting",setup(g){const{t:c}=z.useI18n(),B=M();C();const _=A();I();const o=x(),a=O(""),h=F(()=>[{key:"name",label:c("settings.customization.notes.name"),thClass:"extra",tdClass:"font-medium text-gray-900"},{key:"type",label:c("settings.customization.notes.type"),thClass:"extra",tdClass:"font-medium text-gray-900"},{key:"actions",label:"",tdClass:"text-right text-sm font-medium",sortable:!1}]);async function y({page:s,filter:n,sort:t}){let i=H({orderByField:t.fieldName||"created_at",orderBy:t.order||"desc",page:s}),m=await _.fetchNotes(i);return{data:m.data.data,pagination:{totalPages:m.data.meta.last_page,currentPage:s,totalCount:m.data.meta.total,limit:5}}}async function b(){await B.openModal({title:c("settings.customization.notes.add_note"),componentName:"NoteModal",size:"md",refreshData:a.value&&a.value.refresh})}async function v(){a.value&&a.value.refresh()}return(s,n)=>{const t=l("BaseIcon"),i=l("BaseButton"),m=l("BaseTable"),N=l("BaseSettingCard");return u(),P(V,null,[r(T),r(N,{title:s.$t("settings.customization.notes.title"),description:s.$t("settings.customization.notes.description")},{action:e(()=>[d(o).hasAbilities(d(w).MANAGE_NOTE)?(u(),p(i,{key:0,variant:"primary-outline",onClick:b},{left:e(f=>[r(t,{class:G(f.class),name:"PlusIcon"},null,8,["class"])]),default:e(()=>[S(" "+D(s.$t("settings.customization.notes.add_note")),1)]),_:1})):k("",!0)]),default:e(()=>[r(m,{ref_key:"table",ref:a,data:y,columns:h.value,class:"mt-14"},{"cell-actions":e(({row:f})=>[r(L,{row:f.data,table:a.value,"load-data":v},null,8,["row","table"])]),_:1},8,["columns"])]),_:1},8,["title","description"])],64)}}};export{gt as default};