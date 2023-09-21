import{a as j}from"./axios.7b768d2b.js";import{d as W}from"./pinia.32c02ade.js";import{h as q,u as P,c as L,j as Y}from"./main.f5ac513d.js";import{v as O}from"./vue-i18n.c6de3223.js";import{u as E}from"./disk.dc8bac64.js";import{r as y,f as R,a as w,O as i,j as G,k as x,U as n,P as D,as as $,X as S,u as r,l as s,by as Z,g as A,R as ee,M as te,F as ae}from"./@vue.8cc12e6e.js";import{c as U,r as F,d as se}from"./@vuelidate.c7422de1.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./vue-router.746ec05f.js";import"./lodash.8ab3583e.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";const X=(I=!1)=>{const k=I?window.pinia.defineStore:W,{global:_}=window.i18n;return k({id:"backup",state:()=>({backups:[],currentBackupData:{option:"full",selected_disk:null}}),actions:{fetchBackups(f){return new Promise((o,a)=>{j.get("/api/v1/backups",{params:f}).then(e=>{this.backups=e.data.data,o(e)}).catch(e=>{q(e),a(e)})})},createBackup(f){return new Promise((o,a)=>{j.post("/api/v1/backups",f).then(e=>{P().showNotification({type:"success",message:_.t("settings.backup.created_message")}),o(e)}).catch(e=>{q(e),a(e)})})},removeBackup(f){return new Promise((o,a)=>{j.delete(`/api/v1/backups/${f.disk}`,{params:f}).then(e=>{P().showNotification({type:"success",message:_.t("settings.backup.deleted_message")}),o(e)}).catch(e=>{q(e),a(e)})})}}})()},oe={class:"flex justify-between w-full"},ne=["onSubmit"],le={class:"p-6"},re={class:"z-0 flex justify-end p-4 border-t border-gray-200 border-solid"},ie={__name:"BackupModal",setup(I){y(null),y(!1);let k=y(!1),_=y(!1);const f=R(["full","only-db","only-files"]),o=X(),a=L(),e=E(),{t:p}=O.useI18n(),m=w(()=>a.active&&a.componentName==="BackupModal"),M=w(()=>e.disks.map(l=>({...l,name:l.name+" \u2014 ["+l.driver+"]"}))),V=w(()=>({currentBackupData:{option:{required:U.withMessage(p("validation.required"),F)},selected_disk:{required:U.withMessage(p("validation.required"),F)}}})),b=se(V,w(()=>o));async function N(){if(b.value.currentBackupData.$touch(),b.value.currentBackupData.$invalid)return!0;let l={option:o.currentBackupData.option,file_disk_id:o.currentBackupData.selected_disk.id};try{k.value=!0,(await o.createBackup(l)).data&&(k.value=!1,a.refreshData&&a.refreshData(),a.closeModal())}catch{k.value=!1}}async function z(){_.value=!0;let l=await e.fetchDisks({limit:"all"});o.currentBackupData.selected_disk=l.data.data[0],_.value=!1}function C(){a.closeModal(),setTimeout(()=>{b.value.$reset(),o.$reset()})}return(l,g)=>{const t=i("BaseIcon"),c=i("BaseMultiselect"),d=i("BaseInputGroup"),u=i("BaseInputGrid"),h=i("BaseButton"),T=i("BaseModal");return G(),x(T,{show:m.value,onClose:C,onOpen:z},{header:n(()=>[D("div",oe,[$(S(r(a).title)+" ",1),s(t,{name:"XIcon",class:"w-6 h-6 text-gray-500 cursor-pointer",onClick:C})])]),default:n(()=>[D("form",{onSubmit:Z(N,["prevent"])},[D("div",le,[s(u,{layout:"one-column"},{default:n(()=>[s(d,{label:l.$t("settings.backup.select_backup_type"),error:r(b).currentBackupData.option.$error&&r(b).currentBackupData.option.$errors[0].$message,horizontal:"",required:"",class:"py-2"},{default:n(()=>[s(c,{modelValue:r(o).currentBackupData.option,"onUpdate:modelValue":g[0]||(g[0]=B=>r(o).currentBackupData.option=B),options:f,"can-deselect":!1,placeholder:l.$t("settings.backup.select_backup_type"),searchable:""},null,8,["modelValue","options","placeholder"])]),_:1},8,["label","error"]),s(d,{label:l.$t("settings.disk.select_disk"),error:r(b).currentBackupData.selected_disk.$error&&r(b).currentBackupData.selected_disk.$errors[0].$message,horizontal:"",required:"",class:"py-2"},{default:n(()=>[s(c,{modelValue:r(o).currentBackupData.selected_disk,"onUpdate:modelValue":g[1]||(g[1]=B=>r(o).currentBackupData.selected_disk=B),"content-loading":r(_),options:M.value,searchable:!0,"allow-empty":!1,label:"name","value-prop":"id",placeholder:l.$t("settings.disk.select_disk"),"track-by":"name",object:""},null,8,["modelValue","content-loading","options","placeholder"])]),_:1},8,["label","error"])]),_:1})]),D("div",re,[s(h,{class:"mr-3",variant:"primary-outline",type:"button",onClick:C},{default:n(()=>[$(S(l.$t("general.cancel")),1)]),_:1}),s(h,{loading:r(k),disabled:r(k),variant:"primary",type:"submit"},{left:n(B=>[r(k)?ee("",!0):(G(),x(t,{key:0,name:"SaveIcon",class:A(B.class)},null,8,["class"]))]),default:n(()=>[$(" "+S(l.$t("general.create")),1)]),_:1},8,["loading","disabled"])])],40,ne)]),_:1},8,["show"])}}},ce={class:"grid my-14 md:grid-cols-3"},ue={class:"inline-block"},qe={__name:"BackupSetting",setup(I){const k=Y(),_=X(),f=L(),o=E(),{t:a}=O.useI18n(),e=R({selected_disk:{driver:"local"}}),p=y("");let m=y(!0);const M=w(()=>[{key:"path",label:a("settings.backup.path"),thClass:"extra",tdClass:"font-medium text-gray-900"},{key:"created_at",label:a("settings.backup.created_at"),tdClass:"font-medium text-gray-900"},{key:"size",label:a("settings.backup.size"),tdClass:"font-medium text-gray-900"},{key:"actions",label:"",tdClass:"text-right text-sm font-medium",sortable:!1}]),V=w(()=>o.disks.map(t=>({...t,name:t.name+" \u2014 ["+t.driver+"]"})));z();function b(t){k.openDialog({title:a("general.are_you_sure"),message:a("settings.backup.backup_confirm_delete"),yesLabel:a("general.ok"),noLabel:a("general.cancel"),variant:"danger",hideNoButton:!1,size:"lg"}).then(async c=>{if(c){let d={disk:e.selected_disk.driver,file_disk_id:e.selected_disk.id,path:t.path},u=await _.removeBackup(d);if(u.data.success||u.data.backup)return p.value&&p.value.refresh(),!0}})}function N(){setTimeout(()=>{p.value.refresh()},100)}async function z(){m.value=!0;let t=await o.fetchDisks({limit:"all"});t.data.error,e.selected_disk=t.data.data.find(c=>c.set_as_default==0),m.value=!1}async function C({page:t,filter:c,sort:d}){let u={disk:e.selected_disk.driver,filed_disk_id:e.selected_disk.id};m.value=!0;let h=await _.fetchBackups(u);return m.value=!1,{data:h.data.backups,pagination:{totalPages:1,currentPage:1}}}async function l(){f.openModal({title:a("settings.backup.create_backup"),componentName:"BackupModal",refreshData:p.value&&p.value.refresh,size:"sm"})}async function g(t){m.value=!0,window.axios({method:"GET",url:"/api/v1/download-backup",responseType:"blob",params:{disk:e.selected_disk.driver,file_disk_id:e.selected_disk.id,path:t.path}}).then(c=>{const d=window.URL.createObjectURL(new Blob([c.data])),u=document.createElement("a");u.href=d,u.setAttribute("download",t.path.split("/")[1]),document.body.appendChild(u),u.click(),m.value=!1}).catch(c=>{m.value=!1})}return(t,c)=>{const d=i("BaseIcon"),u=i("BaseButton"),h=i("BaseMultiselect"),T=i("BaseInputGroup"),B=i("BaseDropdownItem"),H=i("BaseDropdown"),J=i("BaseTable"),K=i("BaseSettingCard");return G(),te(ae,null,[s(ie),s(K,{title:t.$tc("settings.backup.title",1),description:t.$t("settings.backup.description")},{action:n(()=>[s(u,{variant:"primary-outline",onClick:l},{left:n(v=>[s(d,{class:A(v.class),name:"PlusIcon"},null,8,["class"])]),default:n(()=>[$(" "+S(t.$t("settings.backup.new_backup")),1)]),_:1})]),default:n(()=>[D("div",ce,[s(T,{label:t.$t("settings.disk.select_disk"),"content-loading":r(m)},{default:n(()=>[s(h,{modelValue:e.selected_disk,"onUpdate:modelValue":c[0]||(c[0]=v=>e.selected_disk=v),"content-loading":r(m),options:V.value,"track-by":"name",placeholder:t.$t("settings.disk.select_disk"),label:"name",searchable:!0,object:"",class:"w-full","value-prop":"id",onSelect:N},null,8,["modelValue","content-loading","options","placeholder"])]),_:1},8,["label","content-loading"])]),s(J,{ref_key:"table",ref:p,class:"mt-10","show-filter":!1,data:C,columns:M.value},{"cell-actions":n(({row:v})=>[s(H,null,{activator:n(()=>[D("div",ue,[s(d,{name:"DotsHorizontalIcon",class:"text-gray-500"})])]),default:n(()=>[s(B,{onClick:Q=>g(v.data)},{default:n(()=>[s(d,{name:"CloudDownloadIcon",class:"mr-3 text-gray-600"}),$(" "+S(t.$t("general.download")),1)]),_:2},1032,["onClick"]),s(B,{onClick:Q=>b(v.data)},{default:n(()=>[s(d,{name:"TrashIcon",class:"mr-3 text-gray-600"}),$(" "+S(t.$t("general.delete")),1)]),_:2},1032,["onClick"])]),_:2},1024)]),_:1},8,["columns"])]),_:1},8,["title","description"])],64)}}};export{qe as default};