import{r as w,a as y,O as m,j as c,M as C,l as s,U as l,u as e,k as $,P as D,as as b,X as B,g as M,by as j}from"./@vue.8cc12e6e.js";import{v as E}from"./vue-i18n.c6de3223.js";import{d as L,c as g,r as _,e as N}from"./@vuelidate.c7422de1.js";import{u as O}from"./auth.f67c9755.js";import{b as P,u as T}from"./vue-router.746ec05f.js";import"./@intlify.0be1c2d8.js";import"./vue.a6ed3e81.js";import"./axios.7b768d2b.js";import"./main.f5ac513d.js";import"./v-tooltip.1cd7a91e.js";import"./vue-resize.0b8cd4b5.js";import"./@popperjs.940d197a.js";import"./pinia.32c02ade.js";import"./lodash.8ab3583e.js";import"./moment.9709ab41.js";import"./guid.7b3dea7e.js";import"./@headlessui.4523e0e1.js";import"./@vueuse.b6c5a1c1.js";import"./prettier.6e0a0647.js";import"./vue-flatpickr-component.30399bbe.js";import"./flatpickr.dff0933c.js";import"./@heroicons.aa650707.js";import"./v-money3.6f830de1.js";import"./maska.1121c2e3.js";const U=["onSubmit"],G={class:"flex items-center justify-between"},ce={__name:"Login",setup(R){const I=P(),k=T(),r=O(),{t:p}=E.useI18n();let u=w(!1);const i=w(!1),h=y(()=>i.value?"text":"password"),V=y(()=>({loginData:{email:{required:g.withMessage(p("validation.required"),_),email:g.withMessage(p("validation.email_incorrect"),N)},password:{required:g.withMessage(p("validation.required"),_)}}})),t=L(V,r);async function S(){if(t.value.loginData.$touch(),t.value.loginData.$invalid)return!0;u.value=!0;let n={...r.loginData,company:k.params.company};try{return await r.login(n),u.value=!1,I.push({name:"customer.dashboard"});r.$reset()}catch{u.value=!1}}return(n,o)=>{const v=m("BaseInput"),f=m("BaseInputGroup"),d=m("BaseIcon"),q=m("router-link"),x=m("BaseButton");return c(),C("form",{id:"loginForm",class:"space-y-6",action:"#",method:"POST",onSubmit:j(S,["prevent"])},[s(f,{error:e(t).loginData.email.$error&&e(t).loginData.email.$errors[0].$message,label:n.$t("login.email"),class:"mb-4",required:""},{default:l(()=>[s(v,{modelValue:e(r).loginData.email,"onUpdate:modelValue":o[0]||(o[0]=a=>e(r).loginData.email=a),type:"email",invalid:e(t).loginData.email.$error,onInput:o[1]||(o[1]=a=>e(t).loginData.email.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["error","label"]),s(f,{error:e(t).loginData.password.$error&&e(t).loginData.password.$errors[0].$message,label:n.$t("login.password"),class:"mb-4",required:""},{default:l(()=>[s(v,{modelValue:e(r).loginData.password,"onUpdate:modelValue":o[4]||(o[4]=a=>e(r).loginData.password=a),type:h.value,invalid:e(t).loginData.password.$error,onInput:o[5]||(o[5]=a=>e(t).loginData.password.$touch())},{right:l(()=>[i.value?(c(),$(d,{key:0,name:"EyeOffIcon",class:"w-5 h-5 mr-1 text-gray-500 cursor-pointer",onClick:o[2]||(o[2]=a=>i.value=!i.value)})):(c(),$(d,{key:1,name:"EyeIcon",class:"w-5 h-5 mr-1 text-gray-500 cursor-pointer",onClick:o[3]||(o[3]=a=>i.value=!i.value)}))]),_:1},8,["modelValue","type","invalid"])]),_:1},8,["error","label"]),D("div",G,[s(q,{to:{name:"customer.forgot-password"},class:"text-sm text-primary-600 hover:text-gray-500"},{default:l(()=>[b(B(n.$t("login.forgot_password")),1)]),_:1},8,["to"])]),D("div",null,[s(x,{loading:e(u),disabled:e(u),type:"submit",class:"w-full justify-center"},{left:l(a=>[s(d,{name:"LockClosedIcon",class:M(a.class)},null,8,["class"])]),default:l(()=>[b(" "+B(n.$t("login.login")),1)]),_:1},8,["loading","disabled"])])],40,U)}}};export{ce as default};