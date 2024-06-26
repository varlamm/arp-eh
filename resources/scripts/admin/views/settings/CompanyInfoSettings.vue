<template>
  <form @submit.prevent="updateCompanyData">
    <BaseSettingCard
      :title="$t('settings.company_info.company_info')"
      :description="$t('settings.company_info.section_description')"
    >
      <BaseInputGrid class="mt-5">
        <BaseInputGroup :label="$t('settings.company_info.company_logo')">
          <BaseFileUploader
            v-model="previewLogo"
            base64
            @change="onFileInputChange"
            @remove="onFileInputRemove"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.transparent_logo')">
          <BaseFileUploader
            v-model="transparentLogo"
            base64
            @change="onTransparentLogoChange"
            @remove="onTransparentLogoRemove"
          />
        </BaseInputGroup>
      </BaseInputGrid>

      <BaseInputGrid class="mt-5">
        <BaseInputGroup
          :label="$t('settings.company_info.company_name')"
          :error="v$.name.$error && v$.name.$errors[0].$message"
          required
        >
          <BaseInput
            v-model="companyForm.name"
            :invalid="v$.name.$error"
            @blur="v$.name.$touch()"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.phone')">
          <BaseInput v-model="companyForm.address.phone" />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.address_1')">
            <BaseTextarea
              v-model="companyForm.address.address_street_1"
              rows="2"
            />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.address_2')">
          <BaseTextarea
            v-model="companyForm.address.address_street_2"
            rows="2"
          />
        </BaseInputGroup>
        
        <BaseInputGroup
          :label="$t('settings.company_info.country')"
          :error="
            v$.address.country_id.$error &&
            v$.address.country_id.$errors[0].$message
          "
          required
        >
          <BaseMultiselect
            v-model="companyForm.address.country_id"
            label="name"
            :invalid="v$.address.country_id.$error"
            :options="globalStore.countries"
            value-prop="id"
            :can-deselect="true"
            :can-clear="false"
            searchable
            track-by="name"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.state')">
          <BaseInput
            v-model="companyForm.address.state"
            name="state"
            type="text"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.city')">
          <BaseInput v-model="companyForm.address.city" type="text" />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.zip')">
          <BaseInput v-model="companyForm.address.zip" />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.company_url')">
          <BaseInput
            v-model="settingsForm.company_url"
            name="company_url"
            type="text"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.invoice_url')">
          <BaseInput
            v-model="settingsForm.invoice_url"
            name="invoice_url"
            type="text"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.sub_domain_url')">
          <BaseInput
            v-model="settingsForm.sub_domain_url"
            name="sub_domain_url"
            type="text"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.tagline_text')">
          <BaseInput
            v-model="settingsForm.tagline_text"
            name="tagline_text"
            type="text"
          />
        </BaseInputGroup>
        
        <BaseInputGroup :label="$t('settings.company_info.primary_color')">
          <BaseInput
            v-model="settingsForm.primary_color"
            name="primary_color"
            type="color"
          />
        </BaseInputGroup>
        
        <BaseInputGroup :label="$t('settings.company_info.secondary_color')">
          <BaseInput
            v-model="settingsForm.secondary_color"
            name="secondary_color"
            type="color"
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$t('settings.company_info.login_page_heading')">
          <BaseTextarea
            v-model="settingsForm.login_page_heading"
            rows="3"
          />
        </BaseInputGroup>
        
        <BaseInputGroup :label="$t('settings.company_info.login_page_description')">
          <BaseTextarea
            v-model="settingsForm.login_page_description"
            rows="3"
          />
        </BaseInputGroup>

        <BaseInputGroup required :label="$t('settings.company_info.choose_crm')">
            <BaseRadio
              id="zoho"
              v-model="settingsForm.company_crm"
              :label="$t('settings.company_info.crm_zoho')"
              :content-loading="isFetchingInitialData"
              size="sm"
              name="crm"
              value="zoho"
              class="mt-2"
            />

            <BaseRadio
              id="none"
              v-model="settingsForm.company_crm"
              :label="$t('settings.company_info.crm_none')"
              :content-loading="isFetchingInitialData"
              size="sm"
              name="crm"
              value="none"
              class="mt-2"
            />
         </BaseInputGroup>

         <BaseInputGroup
            :label="$t('settings.company_info.allowed_currencies')"
            required
          >
            <BaseMultiselect
             :content-loading="isFetchingInitialData"
              mode="tags"
              v-model="selectedCurrencies"
              :options="allowedCurrencies"
              searchable
              class="w-full"
              track-by="name"
              @update:modelValue="(val) => updateSelectedCurrency(val)"
            />
          </BaseInputGroup>

      </BaseInputGrid>

      <BaseButton
        :loading="isSaving"
        :disabled="isSaving"
        type="submit"
        class="mt-6"
      >
        <template #left="slotProps">
          <BaseIcon v-if="!isSaving" :class="slotProps.class" name="SaveIcon" />
        </template>
        {{ $t('settings.company_info.save') }}
      </BaseButton>

      <div v-if="companyStore.companies.length !== 1" class="py-5 hidden">
        <BaseDivider class="my-4" />
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          {{ $t('settings.company_info.delete_company') }}
        </h3>
        <div class="mt-2 max-w-xl text-sm text-gray-500">
          <p>
            {{ $t('settings.company_info.delete_company_description') }}
          </p>
        </div>
        <div class="mt-5">
          <button 
            type="button"
            class="
              inline-flex
              items-center
              justify-center
              px-4
              py-2
              border border-transparent
              font-medium
              rounded-md
              text-red-700
              bg-red-100
              hover:bg-red-200
              focus:outline-none
              focus:ring-2
              focus:ring-offset-2
              focus:ring-red-500
              sm:text-sm
            "
            @click="removeCompany"
          >
            {{ $t('general.delete') }}
          </button>
        </div>
      </div>
    </BaseSettingCard>
  </form>
  <DeleteCompanyModal />
</template>

<script setup>
import { reactive, ref, inject, computed, onMounted } from 'vue'
import { useGlobalStore } from '@/scripts/admin/stores/global'
import { useCompanyStore } from '@/scripts/admin/stores/company'
import { useI18n } from 'vue-i18n'
import { required, minLength, helpers } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useModalStore } from '@/scripts/stores/modal'
import DeleteCompanyModal from '@/scripts/admin/components/modal-components/DeleteCompanyModal.vue'
import { set } from '@vueuse/core'

const companyStore = useCompanyStore()
const globalStore = useGlobalStore()
const modalStore = useModalStore()
const { t } = useI18n()
const utils = inject('utils')

let isSaving = ref(false)
let isFetchingInitialData = ref(false)

const companyForm = reactive({
  name: null,
  logo: null,
  transparent_logo: null,
  address: {
    address_street_1: '',
    address_street_2: '',
    website: '',
    country_id: null,
    state: '',
    city: '',
    phone: '',
    zip: '',
  },
})

const settingsForm = reactive({ 
  company_crm: 'none',
  company_url: '', 
  invoice_url: '',
  sub_domain_url: '',
  tagline_text: 'One stop invoicing solution',
  login_page_heading: 'Simple Invoicing for Individuals Small Businesses',
  login_page_description: 'Xcelerate helps you track expenses, record payments & generate beautiful invoices & estimates.',
  primary_color: '#5851d8',
  secondary_color: '#8a85e4',
  selected_currencies: [],
  active_crms: []
})

const companySettings = reactive({ ...companyStore.selectedCompanySettings })

utils.mergeSettings(companyForm, {
  ...companyStore.selectedCompany,
})

utils.mergeSettings(settingsForm, {
  ...companyStore.selectedCompanySettings
})

let previewLogo = ref([])
let logoFileBlob = ref(null)
let logoFileName = ref(null)
const isCompanyLogoRemoved = ref(false)

if (companyForm.logo) {
  previewLogo.value.push({
    image: companyForm.logo,
  })
}

let transparentLogo = ref([])
let transparentLogoFileBlob = ref(null)
let transparentLogoFileName = ref(null)
const isTransparentLogoRemoved = ref(false)

if(companyForm.transparent_logo) {
  transparentLogo.value.push({
    image: companyForm.transparent_logo,
  })
}

const rules = computed(() => {
  return {
    name: {
      required: helpers.withMessage(t('validation.required'), required),
      minLength: helpers.withMessage(
        t('validation.name_min_length'),
        minLength(3)
      ),
    },
    address: {
      country_id: {
        required: helpers.withMessage(t('validation.required'), required),
      },
    }
  }
})

const v$ = useVuelidate(
  rules,
  computed(() => companyForm)
)

globalStore.fetchCountries()

async function allowedCurrencies() {
  isFetchingInitialData.value = true
  let response = await globalStore.fetchCurrencies()
  let currencies = [];
  let res = {}
  if(response.data){
    if(response.data.data){
      res = response.data.data
    }
  }
  else if(response){
    res = response
  }
  
  res.filter((currency) => {
    currencies.push(`${currency.code}`)
  })
  
  isFetchingInitialData.value = false

  return currencies
}

const selectedCurrencies = computed(() => {
  let currencies = []
  if(Object.keys(settingsForm.selected_currencies).length > 2){
    currencies = JSON.parse(settingsForm.selected_currencies);
    return Object.values(currencies);
  }

  return currencies
})

onMounted(() => {
   if(settingsForm.active_crms.length > 0){
      const crms = JSON.parse(settingsForm.active_crms)
      if(crms.zoho == 'false'){
        let optionZoho = document.getElementById('zoho');
        optionZoho.classList.add('hidden');
      } 
      if(crms.none == 'false'){
        let optionNone = document.getElementById('none')
        optionNone.classList.add('hidden');
      }
   }
})

function updateSelectedCurrency(val) {
  let selectedCurrenciesObj = {}
  val.forEach((eachCurrency, index) => {
    selectedCurrenciesObj[index] = eachCurrency 
  })
  settingsForm.selected_currencies = JSON.stringify(selectedCurrenciesObj)
}

function onFileInputChange(fileName, file, fileCount, fileList) {
  logoFileName.value = fileList.name
  logoFileBlob.value = file
}

function onFileInputRemove() {
  logoFileBlob.value = null
  isCompanyLogoRemoved.value = true
}

function onTransparentLogoChange(fileName, file, fileCount, fileList) {
  transparentLogoFileName.value = fileList.name
  transparentLogoFileBlob.value = file
}

function onTransparentLogoRemove() {
  transparentLogoFileBlob.value = null
  isTransparentLogoRemoved.value = true
}

async function updateCompanyData() {
  if (v$.value.$invalid) {
    return true
  }

  isSaving.value = true

  const res = await companyStore.updateCompany(companyForm)

  if (res.data.data) {
    if (logoFileBlob.value || isCompanyLogoRemoved.value) {
      let logoData = new FormData()

      if (logoFileBlob.value) {
        logoData.append(
          'company_logo',
          JSON.stringify({
            name: logoFileName.value,
            data: logoFileBlob.value,
          })
        )
      }
      logoData.append('is_company_logo_removed', isCompanyLogoRemoved.value)

      await companyStore.updateCompanyLogo(logoData)
      logoFileBlob.value = null
      isCompanyLogoRemoved.value = false

    }

    if (transparentLogoFileBlob.value || isTransparentLogoRemoved.value) {
      let transparentLogoData = new FormData()

      if (transparentLogoFileBlob.value) {
        transparentLogoData.append(
          'transparent_logo',
          JSON.stringify({
            name: transparentLogoFileName.value,
            data: transparentLogoFileBlob.value
          })
        )
      }
      transparentLogoData.append('is_transparent_logo_removed', isTransparentLogoRemoved.value)

      await companyStore.updateTransparentLogo(transparentLogoData)
      transparentLogoFileBlob.value = null
      isTransparentLogoRemoved.value = false

    }

    let data = {
      settings: {
        ...settingsForm,
      }  
    }

    await companyStore.updateCompanySettings({
      data,
      message: ''
    })
    
    if(Object.keys(settingsForm.selected_currencies).length > 2){
      let item_currencies = JSON.parse(settingsForm.selected_currencies)
      let currency_data = {
        item_currencies: item_currencies,
        company_currency: companySettings.currency
      }

      await companyStore.updateItemColumns({
        currency_data,
      })
    }
    
    isSaving.value = false
  }
  isSaving.value = false
}

function removeCompany(id) {
  modalStore.openModal({
    title: t('settings.company_info.are_you_absolutely_sure'),
    componentName: 'DeleteCompanyModal',
    size: 'sm',
  })
}
</script>
