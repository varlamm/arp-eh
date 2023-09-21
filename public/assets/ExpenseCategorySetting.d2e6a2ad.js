import{j as k,u as M,e as P,c as E,g as v}from"./main.f5ac513d.js";import{u as I}from"./category.11760f10.js";import{v as N}from"./vue-i18n.c6de3223.js";import{u as T}from"./vue-router.746ec05f.js";import{i as j,O as i,j as d,k as u,U as e,u as g,l as s,as as b,X as C,R as w,r as z,a as F,M as V,g as L,P as S,F as O}from"./@vue.8cc12e6e.js";import{_ as X}from"./CategoryModal.681da4d6.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./axios.7b768d2b.js";import"./pinia.32c02ade.js";import"./lodash.8ab3583e.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./@vuelidate.c7422de1.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";const A={__name:"ExpenseCategoryIndexDropdown",props:{row:{type:Object,default:null},table:{type:Object,default:null},loadData:{type:Function,default:null}},setup(y){const c=y,x=k();M();const{t:a}=N.useI18n(),o=I(),B=T(),_=P(),h=E();j("utils");function D(r){o.fetchCategory(r),h.openModal({title:a("settings.expense_category.edit_category"),componentName:"CategoryModal",refreshData:c.loadData,size:"sm"})}function n(r){x.openDialog({title:a("general.are_you_sure"),message:a("settings.expense_category.confirm_delete"),yesLabel:a("general.ok"),noLabel:a("general.cancel"),variant:"danger",hideNoButton:!1,size:"lg"}).then(async()=>{if((await o.deleteCategory(r)).data.success)return c.loadData&&c.loadData(),!0;c.loadData&&c.loadData()})}return(r,t)=>{const l=i("BaseIcon"),m=i("BaseButton"),f=i("BaseDropdownItem"),p=i("BaseDropdown");return d(),u(p,null,{activator:e(()=>[g(B).name==="expenseCategorys.view"?(d(),u(m,{key:0,variant:"primary"},{default:e(()=>[s(l,{name:"DotsHorizontalIcon",class:"h-5 text-white"})]),_:1})):(d(),u(l,{key:1,name:"DotsHorizontalIcon",class:"h-5 text-gray-500"}))]),default:e(()=>[g(_).hasAbilities(g(v).EDIT_EXPENSE)?(d(),u(f,{key:0,onClick:t[0]||(t[0]=$=>D(y.row.id))},{default:e(()=>[s(l,{name:"PencilIcon",class:"w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"}),b(" "+C(r.$t("general.edit")),1)]),_:1})):w("",!0),g(_).hasAbilities(g(v).DELETE_EXPENSE)?(d(),u(f,{key:1,onClick:t[1]||(t[1]=$=>n(y.row.id))},{default:e(()=>[s(l,{name:"TrashIcon",class:"w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"}),b(" "+C(r.$t("general.delete")),1)]),_:1})):w("",!0)]),_:1})}}},H={class:"w-64"},R={class:"truncate"},_e={__name:"ExpenseCategorySetting",setup(y){const c=I();k();const x=E(),{t:a}=N.useI18n(),o=z(null),B=F(()=>[{key:"name",label:a("settings.expense_category.category_name"),thClass:"extra",tdClass:"font-medium text-gray-900"},{key:"description",label:a("settings.expense_category.category_description"),thClass:"extra",tdClass:"font-medium text-gray-900"},{key:"actions",label:"",tdClass:"text-right text-sm font-medium",sortable:!1}]);async function _({page:n,filter:r,sort:t}){let l={orderByField:t.fieldName||"created_at",orderBy:t.order||"desc",page:n},m=await c.fetchCategories(l);return{data:m.data.data,pagination:{totalPages:m.data.meta.last_page,currentPage:n,totalCount:m.data.meta.total,limit:5}}}function h(){x.openModal({title:a("settings.expense_category.add_category"),componentName:"CategoryModal",size:"sm",refreshData:o.value&&o.value.refresh})}async function D(){o.value&&o.value.refresh()}return(n,r)=>{const t=i("BaseIcon"),l=i("BaseButton"),m=i("BaseTable"),f=i("BaseSettingCard");return d(),V(O,null,[s(X),s(f,{title:n.$t("settings.expense_category.title"),description:n.$t("settings.expense_category.description")},{action:e(()=>[s(l,{variant:"primary-outline",type:"button",onClick:h},{left:e(p=>[s(t,{class:L(p.class),name:"PlusIcon"},null,8,["class"])]),default:e(()=>[b(" "+C(n.$t("settings.expense_category.add_new_category")),1)]),_:1})]),default:e(()=>[s(m,{ref_key:"table",ref:o,data:_,columns:B.value,class:"mt-16"},{"cell-description":e(({row:p})=>[S("div",H,[S("p",R,C(p.data.description),1)])]),"cell-actions":e(({row:p})=>[s(A,{row:p.data,table:o.value,"load-data":D},null,8,["row","table"])]),_:1},8,["columns"])]),_:1},8,["title","description"])],64)}}};export{_e as default};