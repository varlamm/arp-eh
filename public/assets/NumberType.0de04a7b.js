import{a as m,O as s,j as r,k as d}from"./@vue.8cc12e6e.js";const V={__name:"NumberType",props:{modelValue:{type:[String,Number],default:null}},emits:["update:modelValue"],setup(o,{emit:u}){const a=o,e=m({get:()=>a.modelValue,set:t=>{u("update:modelValue",t)}});return(t,l)=>{const n=s("BaseInput");return r(),d(n,{modelValue:e.value,"onUpdate:modelValue":l[0]||(l[0]=p=>e.value=p),type:"number"},null,8,["modelValue"])}}};export{V as default};