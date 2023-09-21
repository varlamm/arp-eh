import{D as F,_ as L,a as R}from"./EstimateIcon.5317b945.js";import{j as r,M as C,g as M,P as t,O as i,k as p,U as c,l as a,Q as W,X as h,aX as q,i as N,u as e,R as v,as as y,r as B,e as z,a as V,aq as j,o as U}from"./@vue.8cc12e6e.js";import{_ as T,h as H,b as O,e as E,g as _}from"./main.f5ac513d.js";import{a as X}from"./axios.7b768d2b.js";import{d as Z}from"./pinia.32c02ade.js";import"./lodash.8ab3583e.js";import{_ as Q}from"./LineChart.37ee9ebf.js";import{v as Y}from"./vue-i18n.c6de3223.js";import{_ as G}from"./InvoiceIndexDropdown.86fb0c41.js";import{_ as J}from"./EstimateIndexDropdown.e28cee42.js";import{u as K,b as tt}from"./vue-router.746ec05f.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./vue.a6ed3e81.js";import"./@intlify.0be1c2d8.js";import"./@vuelidate.c7422de1.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";import"./chart.js.bab82181.js";const et=t("circle",{cx:"25",cy:"25",r:"25",fill:"#EAF1FB"},null,-1),at=t("path",{d:"M28.2656 23.0547C27.3021 24.0182 26.1302 24.5 24.75 24.5C23.3698 24.5 22.1849 24.0182 21.1953 23.0547C20.2318 22.0651 19.75 20.8802 19.75 19.5C19.75 18.1198 20.2318 16.9479 21.1953 15.9844C22.1849 14.9948 23.3698 14.5 24.75 14.5C26.1302 14.5 27.3021 14.9948 28.2656 15.9844C29.2552 16.9479 29.75 18.1198 29.75 19.5C29.75 20.8802 29.2552 22.0651 28.2656 23.0547ZM28.2656 25.75C29.6979 25.75 30.9219 26.2708 31.9375 27.3125C32.9792 28.3281 33.5 29.5521 33.5 30.9844V32.625C33.5 33.1458 33.3177 33.5885 32.9531 33.9531C32.5885 34.3177 32.1458 34.5 31.625 34.5H17.875C17.3542 34.5 16.9115 34.3177 16.5469 33.9531C16.1823 33.5885 16 33.1458 16 32.625V30.9844C16 29.5521 16.5078 28.3281 17.5234 27.3125C18.5651 26.2708 19.8021 25.75 21.2344 25.75H21.8984C22.8099 26.1667 23.7604 26.375 24.75 26.375C25.7396 26.375 26.6901 26.1667 27.6016 25.75H28.2656Z",fill:"currentColor"},null,-1),st=[et,at],ot={__name:"CustomerIcon",props:{colorClass:{type:String,default:"text-primary-500"}},setup(d){return(o,s)=>(r(),C("svg",{width:"50",height:"50",viewBox:"0 0 50 50",class:M(d.colorClass),fill:"none",xmlns:"http://www.w3.org/2000/svg"},st,2))}},lt={},nt={class:"flex items-center"};function rt(d,o){const s=i("BaseContentPlaceholdersText"),n=i("BaseContentPlaceholdersBox"),u=i("BaseContentPlaceholders");return r(),p(u,{rounded:!0,class:"relative flex justify-between w-full p-3 bg-white rounded shadow lg:col-span-3 xl:p-4"},{default:c(()=>[t("div",null,[a(s,{class:"h-5 -mb-1 w-14 xl:mb-6 xl:h-7",lines:1}),a(s,{class:"h-3 w-28 xl:h-4",lines:1})]),t("div",nt,[a(n,{circle:!0,class:"w-10 h-10 xl:w-12 xl:h-12"})])]),_:1})}var ct=T(lt,[["render",rt]]);const it={},dt={class:"flex items-center"};function ut(d,o){const s=i("BaseContentPlaceholdersText"),n=i("BaseContentPlaceholdersBox"),u=i("BaseContentPlaceholders");return r(),p(u,{rounded:!0,class:"relative flex justify-between w-full p-3 bg-white rounded shadow lg:col-span-2 xl:p-4"},{default:c(()=>[t("div",null,[a(s,{class:"w-12 h-5 -mb-1 xl:mb-6 xl:h-7",lines:1}),a(s,{class:"w-20 h-3 xl:h-4",lines:1})]),t("div",dt,[a(n,{circle:!0,class:"w-10 h-10 xl:w-12 xl:h-12"})])]),_:1})}var mt=T(it,[["render",ut]]);const _t={class:"text-xl font-semibold leading-tight text-black xl:text-3xl"},ht={class:"block mt-1 text-sm leading-tight text-gray-500 xl:text-lg"},pt={class:"flex items-center"},$={__name:"DashboardStatsItem",props:{iconComponent:{type:Object,required:!0},loading:{type:Boolean,default:!1},route:{type:String,required:!0},label:{type:String,required:!0},large:{type:Boolean,default:!1}},setup(d){return(o,s)=>{const n=i("router-link");return d.loading?d.large?(r(),p(ct,{key:1})):(r(),p(mt,{key:2})):(r(),p(n,{key:0,class:M(["relative flex justify-between p-3 bg-white rounded shadow hover:bg-gray-50 xl:p-4 lg:col-span-2",{"lg:!col-span-3":d.large}]),to:d.route},{default:c(()=>[t("div",null,[t("span",_t,[W(o.$slots,"default")]),t("span",ht,h(d.label),1)]),t("div",pt,[(r(),p(q(d.iconComponent),{class:"w-10 h-10 xl:w-12 xl:h-12"}))])]),_:3},8,["class","to"]))}}},S=(d=!1)=>(d?window.pinia.defineStore:Z)({id:"dashboard",state:()=>({stats:{totalAmountDue:0,totalCustomerCount:0,totalInvoiceCount:0,totalEstimateCount:0},chartData:{months:[],invoiceTotals:[],expenseTotals:[],receiptTotals:[],netIncomeTotals:[]},totalSales:null,totalReceipts:null,totalExpenses:null,totalNetIncome:null,recentDueInvoices:[],recentEstimates:[],isDashboardDataLoaded:!1}),actions:{loadData(s){return new Promise((n,u)=>{X.get("/api/v1/dashboard",{params:s}).then(l=>{this.stats.totalAmountDue=l.data.total_amount_due,this.stats.totalCustomerCount=l.data.total_customer_count,this.stats.totalInvoiceCount=l.data.total_invoice_count,this.stats.totalEstimateCount=l.data.total_estimate_count,this.chartData&&l.data.chart_data&&(this.chartData.months=l.data.chart_data.months,this.chartData.invoiceTotals=l.data.chart_data.invoice_totals,this.chartData.expenseTotals=l.data.chart_data.expense_totals,this.chartData.receiptTotals=l.data.chart_data.receipt_totals,this.chartData.netIncomeTotals=l.data.chart_data.net_income_totals),this.totalSales=l.data.total_sales,this.totalReceipts=l.data.total_receipts,this.totalExpenses=l.data.total_expenses,this.totalNetIncome=l.data.total_net_income,this.recentDueInvoices=l.data.recent_due_invoices,this.recentEstimates=l.data.recent_estimates,this.isDashboardDataLoaded=!0,n(l)}).catch(l=>{H(l),u(l)})})}}})(),bt={class:"grid gap-6 sm:grid-cols-2 lg:grid-cols-9 xl:gap-8"},ft={__name:"DashboardStats",setup(d){N("utils");const o=S(),s=O(),n=E();return(u,l)=>{const x=i("BaseFormatMoney");return r(),C("div",bt,[e(n).hasAbilities(e(_).VIEW_INVOICE)?(r(),p($,{key:0,"icon-component":F,loading:!e(o).isDashboardDataLoaded,route:"/admin/invoices",large:!0,label:u.$t("dashboard.cards.due_amount")},{default:c(()=>[a(x,{amount:e(o).stats.totalAmountDue,currency:e(s).selectedCompanyCurrency},null,8,["amount","currency"])]),_:1},8,["loading","label"])):v("",!0),e(n).hasAbilities(e(_).VIEW_CUSTOMER)?(r(),p($,{key:1,"icon-component":ot,loading:!e(o).isDashboardDataLoaded,route:"/admin/customers",label:u.$t("dashboard.cards.customers")},{default:c(()=>[y(h(e(o).stats.totalCustomerCount),1)]),_:1},8,["loading","label"])):v("",!0),e(n).hasAbilities(e(_).VIEW_INVOICE)?(r(),p($,{key:2,"icon-component":L,loading:!e(o).isDashboardDataLoaded,route:"/admin/invoices",label:u.$t("dashboard.cards.invoices")},{default:c(()=>[y(h(e(o).stats.totalInvoiceCount),1)]),_:1},8,["loading","label"])):v("",!0),e(n).hasAbilities(e(_).VIEW_ESTIMATE)?(r(),p($,{key:3,"icon-component":R,loading:!e(o).isDashboardDataLoaded,route:"/admin/estimates",label:u.$t("dashboard.cards.estimates")},{default:c(()=>[y(h(e(o).stats.totalEstimateCount),1)]),_:1},8,["loading","label"])):v("",!0)])}}},xt={},gt={class:"grid grid-cols-1 col-span-10 px-4 py-5 lg:col-span-7 xl:col-span-8 sm:p-8"},yt={class:"flex items-center justify-between mb-2 xl:mb-4"},Ct={class:"grid grid-cols-3 col-span-10 text-center border-t border-l border-gray-200 border-solid lg:border-t-0 lg:text-right lg:col-span-3 xl:col-span-2 lg:grid-cols-1"},vt={class:"flex flex-col items-center justify-center p-6 lg:justify-end lg:items-end"},wt={class:"flex flex-col items-center justify-center p-6 lg:justify-end lg:items-end"},Dt={class:"flex flex-col items-center justify-center p-6 lg:justify-end lg:items-end"},$t={class:"flex flex-col items-center justify-center col-span-3 p-6 border-t border-gray-200 border-solid lg:justify-end lg:items-end lg:col-span-1"};function Bt(d,o){const s=i("BaseContentPlaceholdersText"),n=i("BaseContentPlaceholdersBox"),u=i("BaseContentPlaceholders");return r(),p(u,{class:"grid grid-cols-10 mt-8 bg-white rounded shadow"},{default:c(()=>[t("div",gt,[t("div",yt,[a(s,{class:"h-10 w-36",lines:1}),a(s,{class:"h-10 w-36 !mt-0",lines:1})]),a(n,{class:"h-80 xl:h-72 sm:w-full"})]),t("div",Ct,[t("div",vt,[a(s,{class:"h-3 w-14 xl:h-4",lines:1}),a(s,{class:"w-20 h-5 xl:h-6",lines:1})]),t("div",wt,[a(s,{class:"h-3 w-14 xl:h-4",lines:1}),a(s,{class:"w-20 h-5 xl:h-6",lines:1})]),t("div",Dt,[a(s,{class:"h-3 w-14 xl:h-4",lines:1}),a(s,{class:"w-20 h-5 xl:h-6",lines:1})]),t("div",$t,[a(s,{class:"h-3 w-14 xl:h-4",lines:1}),a(s,{class:"w-20 h-5 xl:h-6",lines:1})])])]),_:1})}var Et=T(xt,[["render",Bt]]);const It={key:0,class:"grid grid-cols-10 mt-8 bg-white rounded shadow"},Tt={class:"grid grid-cols-1 col-span-10 px-4 py-5 lg:col-span-7 xl:col-span-8 sm:p-6"},St={class:"flex justify-between mt-1 mb-4 flex-col md:flex-row"},kt={class:"flex items-center sw-section-title h-10"},At={class:"w-full my-2 md:m-0 md:w-40 h-10"},Pt={class:"grid grid-cols-3 col-span-10 text-center border-t border-l border-gray-200 border-solid lg:border-t-0 lg:text-right lg:col-span-3 xl:col-span-2 lg:grid-cols-1"},Vt={class:"p-6"},jt={class:"text-xs leading-5 lg:text-sm"},Mt=t("br",null,null,-1),Nt={class:"block mt-1 text-xl font-semibold leading-8 lg:text-2xl"},Ot={class:"p-6"},Ft={class:"text-xs leading-5 lg:text-sm"},Lt=t("br",null,null,-1),Rt={class:"block mt-1 text-xl font-semibold leading-8 lg:text-2xl text-green-400"},Wt={class:"p-6"},qt={class:"text-xs leading-5 lg:text-sm"},zt=t("br",null,null,-1),Ut={class:"block mt-1 text-xl font-semibold leading-8 lg:text-2xl text-red-400"},Ht={class:"col-span-3 p-6 border-t border-gray-200 border-solid lg:col-span-1"},Xt={class:"text-xs leading-5 lg:text-sm"},Zt=t("br",null,null,-1),Qt={class:"block mt-1 text-xl font-semibold leading-8 lg:text-2xl text-primary-500"},Yt={__name:"DashboardChart",setup(d){const o=S(),s=O();N("utils");const n=E(),u=B(["This year","Previous year"]),l=B("This year");z(l,b=>{b==="Previous year"?x({previous_year:!0}):x()},{immediate:!0});async function x(b){n.hasAbilities(_.DASHBOARD)&&await o.loadData(b)}return(b,w)=>{const I=i("BaseIcon"),g=i("BaseMultiselect"),f=i("BaseFormatMoney");return r(),C("div",null,[e(o).isDashboardDataLoaded?(r(),C("div",It,[t("div",Tt,[t("div",St,[t("h6",kt,[a(I,{name:"ChartSquareBarIcon",class:"text-primary-400 mr-1"}),y(" "+h(b.$t("dashboard.monthly_chart.title")),1)]),t("div",At,[a(g,{modelValue:l.value,"onUpdate:modelValue":w[0]||(w[0]=D=>l.value=D),options:u.value,"allow-empty":!1,"show-labels":!1,placeholder:b.$t("dashboard.select_year"),"can-deselect":!1},null,8,["modelValue","options","placeholder"])])]),a(Q,{invoices:e(o).chartData.invoiceTotals,expenses:e(o).chartData.expenseTotals,receipts:e(o).chartData.receiptTotals,income:e(o).chartData.netIncomeTotals,labels:e(o).chartData.months,class:"sm:w-full"},null,8,["invoices","expenses","receipts","income","labels"])]),t("div",Pt,[t("div",Vt,[t("span",jt,h(b.$t("dashboard.chart_info.total_sales")),1),Mt,t("span",Nt,[a(f,{amount:e(o).totalSales,currency:e(s).selectedCompanyCurrency},null,8,["amount","currency"])])]),t("div",Ot,[t("span",Ft,h(b.$t("dashboard.chart_info.total_receipts")),1),Lt,t("span",Rt,[a(f,{amount:e(o).totalReceipts,currency:e(s).selectedCompanyCurrency},null,8,["amount","currency"])])]),t("div",Wt,[t("span",qt,h(b.$t("dashboard.chart_info.total_expense")),1),zt,t("span",Ut,[a(f,{amount:e(o).totalExpenses,currency:e(s).selectedCompanyCurrency},null,8,["amount","currency"])])]),t("div",Ht,[t("span",Xt,h(b.$t("dashboard.chart_info.net_income")),1),Zt,t("span",Qt,[a(f,{amount:e(o).totalNetIncome,currency:e(s).selectedCompanyCurrency},null,8,["amount","currency"])])])])])):(r(),p(Et,{key:1}))])}}},Gt={class:"grid grid-cols-1 gap-6 mt-10 xl:grid-cols-2"},Jt={key:0,class:"due-invoices"},Kt={class:"relative z-10 flex items-center justify-between mb-3"},te={class:"mb-0 text-xl font-semibold leading-normal"},ee={key:1,class:"recent-estimates"},ae={class:"relative z-10 flex items-center justify-between mb-3"},se={class:"mb-0 text-xl font-semibold leading-normal"},oe={__name:"DashboardTable",setup(d){const o=S(),{t:s}=Y.useI18n(),n=E(),u=B(null),l=B(null),x=V(()=>[{key:"formattedDueDate",label:s("dashboard.recent_invoices_card.due_on")},{key:"user",label:s("dashboard.recent_invoices_card.customer")},{key:"due_amount",label:s("dashboard.recent_invoices_card.amount_due")},{key:"actions",tdClass:"text-right text-sm font-medium pl-0",thClass:"text-right pl-0",sortable:!1}]),b=V(()=>[{key:"formattedEstimateDate",label:s("dashboard.recent_estimate_card.date")},{key:"user",label:s("dashboard.recent_estimate_card.customer")},{key:"total",label:s("dashboard.recent_estimate_card.amount_due")},{key:"actions",tdClass:"text-right text-sm font-medium pl-0",thClass:"text-right pl-0",sortable:!1}]);function w(){return n.hasAbilities([_.DELETE_INVOICE,_.EDIT_INVOICE,_.VIEW_INVOICE,_.SEND_INVOICE])}function I(){return n.hasAbilities([_.CREATE_ESTIMATE,_.EDIT_ESTIMATE,_.VIEW_ESTIMATE,_.SEND_ESTIMATE])}return(g,f)=>{const D=i("BaseButton"),k=i("router-link"),A=i("BaseFormatMoney"),P=i("BaseTable");return r(),C("div",null,[t("div",Gt,[e(n).hasAbilities(e(_).VIEW_INVOICE)?(r(),C("div",Jt,[t("div",Kt,[t("h6",te,h(g.$t("dashboard.recent_invoices_card.title")),1),a(D,{size:"sm",variant:"primary-outline",onClick:f[0]||(f[0]=m=>g.$router.push("/admin/invoices"))},{default:c(()=>[y(h(g.$t("dashboard.recent_invoices_card.view_all")),1)]),_:1})]),a(P,{data:e(o).recentDueInvoices,columns:x.value,loading:!e(o).isDashboardDataLoaded},j({"cell-user":c(({row:m})=>[a(k,{to:{path:`invoices/${m.data.id}/view`},class:"font-medium text-primary-500"},{default:c(()=>[y(h(m.data.customer.name),1)]),_:2},1032,["to"])]),"cell-due_amount":c(({row:m})=>[a(A,{amount:m.data.due_amount,currency:m.data.customer.currency},null,8,["amount","currency"])]),_:2},[w()?{name:"cell-actions",fn:c(({row:m})=>[a(G,{row:m.data,table:u.value},null,8,["row","table"])]),key:"0"}:void 0]),1032,["data","columns","loading"])])):v("",!0),e(n).hasAbilities(e(_).VIEW_ESTIMATE)?(r(),C("div",ee,[t("div",ae,[t("h6",se,h(g.$t("dashboard.recent_estimate_card.title")),1),a(D,{variant:"primary-outline",size:"sm",onClick:f[1]||(f[1]=m=>g.$router.push("/admin/estimates"))},{default:c(()=>[y(h(g.$t("dashboard.recent_estimate_card.view_all")),1)]),_:1})]),a(P,{data:e(o).recentEstimates,columns:b.value,loading:!e(o).isDashboardDataLoaded},j({"cell-user":c(({row:m})=>[a(k,{to:{path:`estimates/${m.data.id}/view`},class:"font-medium text-primary-500"},{default:c(()=>[y(h(m.data.customer.name),1)]),_:2},1032,["to"])]),"cell-total":c(({row:m})=>[a(A,{amount:m.data.total,currency:m.data.customer.currency},null,8,["amount","currency"])]),_:2},[I()?{name:"cell-actions",fn:c(({row:m})=>[a(J,{row:m,table:l.value},null,8,["row","table"])]),key:"0"}:void 0]),1032,["data","columns","loading"])])):v("",!0)])])}}},Pe={__name:"Dashboard",setup(d){const o=K(),s=E(),n=tt();return U(()=>{o.meta.ability&&!s.hasAbilities(o.meta.ability)?n.push({name:"account.settings"}):o.meta.isOwner&&!s.currentUser.is_owner&&n.push({name:"account.settings"})}),(u,l)=>{const x=i("BasePage");return r(),p(x,null,{default:c(()=>[a(ft),a(Yt),a(oe)]),_:1})}}};export{Pe as default};