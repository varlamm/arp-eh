import{a as s,O as m,j as r,k as d}from"./@vue.8cc12e6e.js";const V={__name:"UrlType",props:{modelValue:{type:String,default:null}},emits:["update:modelValue"],setup(o,{emit:a}){const u=o,e=s({get:()=>u.modelValue,set:l=>{a("update:modelValue",l)}});return(l,t)=>{const n=m("BaseInput");return r(),d(n,{modelValue:e.value,"onUpdate:modelValue":t[0]||(t[0]=p=>e.value=p),type:"url"},null,8,["modelValue"])}}};export{V as default};