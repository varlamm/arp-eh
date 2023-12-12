<script setup>
import { reactive, ref, inject, computed } from 'vue'
import { useCompanyStore } from '@/scripts/admin/stores/company'

const utils = inject('utils')
const companyStore = useCompanyStore()

defineProps({
  darkColor: {
    type: String,
    default: 'rgba(var(--color-primary-500), var(--tw-text-opacity))',
  },
  lightColor: {
    type: String,
    default: 'rgba(var(--color-primary-400), var(--tw-text-opacity))',
  },
})



const companyForm = reactive({
  id: null,
  name: null,
  logo: null,
  transparent_logo: null
})

utils.mergeSettings(companyForm, {
  ...companyStore.selectedCompany,
})

const settingsForm = reactive({
  primary_color: '#5851d8',
  secondary_color: '#8a85e4'
})

utils.mergeSettings(settingsForm, {
  ...companyStore.selectedCompanySettings
})

function convertHexCode(hex) {
  hex = hex.replace(/^#/, '');
 
  const bigint = parseInt(hex, 16);
  const r = (bigint >> 16) & 255;
  const g = (bigint >> 8) & 255;
  const b = bigint & 255;

  return `${r}, ${g}, ${b}`;
}

function headerBackground() {
  const styleTag = document.createElement('style');
  styleTag.textContent = `
      :root {
          --color-primary-500: ${convertHexCode(settingsForm.primary_color)};
          --color-primary-400: ${convertHexCode(settingsForm.secondary_color)};
      }
  `;
  document.head.appendChild(styleTag);
}

headerBackground()

async function getCompanySettingsByDomain(){

  let subDomainUrl = window.location.origin
  let data = {
    sub_domain_url : subDomainUrl,
    rand : Math.ceil(Math.random()*1000000)
  }
  const response = await companyStore.companySettingsByDomain(data)
  console.log(response)
  if(response.data){
    settingsForm.primary_color = response.data.primary_color
    settingsForm.secondary_color = response.data.secondary_color

    headerBackground()

    if(response.data.transparent_logo){
      companyForm.transparent_logo = response.data.transparent_logo
    }
    else{
        companyForm.transparent_logo = window.location.origin + '/img/xcelerate-logo-transparent.png'
    }
  }
}

getCompanySettingsByDomain()


</script>
<template>
        <div class="universal-logo">
                <img id="logo-img-url" :src="companyForm.transparent_logo">
        </div>
</template>
