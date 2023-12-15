<template>
    <form @submit.prevent="updateCrmConfig">
        <div v-if="isZoho">
            <BaseSettingCard
                :title="$t('settings.crm_configuration.zoho_configuration')"
                :description="$t('settings.crm_configuration.zoho_description')"
            >

            <BaseInputGrid class="mt-5">
                <BaseInputGroup
                    :label="$t('settings.crm_configuration.zoho_client_id')"
                    :error="v$.zoho.client_id.$error && v$.zoho.client_id.$errors[0].$message"
                    required
                >
                <BaseInput
                    v-model="zohoSettings.zoho.client_id"
                    :invalid="v$.zoho.client_id.$error"
                    :content-loading="isFetchingInitialData"
                    type="text"
                />
                </BaseInputGroup>
            </BaseInputGrid>
            
            <BaseInputGrid class="mt-5">
                <BaseInputGroup
                    :label="$t('settings.crm_configuration.zoho_client_secret')"
                    :error="v$.zoho.client_secret.$error && v$.zoho.client_secret.$errors[0].$message"
                    required
                >
                <BaseInput
                    v-model="zohoSettings.zoho.client_secret"
                    :content-loading="isFetchingInitialData"
                    :invalid="v$.zoho.client_secret.$error"
                    type="text"
                />
                </BaseInputGroup>
            </BaseInputGrid>

            <div class="grid-cols-2 col-span-1 mt-5">
                <BaseInputGroup
                    :label="$t('settings.crm_configuration.zoho_auth_callback_url')"
                    :error="v$.zoho.call_back_uri.$error && v$.zoho.call_back_uri.$errors[0].$message"
                >
                <BaseInput
                    v-model="zohoSettings.zoho.call_back_uri"
                    :content-loading="isFetchingInitialData"
                    :invalid="v$.zoho.call_back_uri.$error"
                    disabled
                    type="text"
                />
                </BaseInputGroup>
            </div>
            
            <div class="mt-5">
                <BaseButton
                    :loading="isSaving"
                    :disabled="isSaving"
                    variant="primary-outline"
                    type="button"
                    @click="testCrmConfig()"
                    class="ml-2"
                    >
                    {{ $t('settings.crm_configuration.zoho_crm_test') }}
                </BaseButton>

                <BaseButton
                    :loading="isSaving && !isDisabled"
                    :disabled="isSaving"
                    type="submit"
                    class="ml-6"
                    :class="{'disabled-btn-cust' : isDisabled}"
                    >
                    <template #left="slotProps">
                    <BaseIcon v-if="!isSaving" :class="slotProps.class" name="SaveIcon" />
                    </template>
                    {{ $t('settings.crm_configuration.zoho_crm_save') }}
                </BaseButton>
            </div>
            
            <BaseTable ref="table" v-if="zohoSyncTable === true" class="mt-10" :data="zohoSyncs" :columns="zohoSyncColumns">
                
                <template #cell-checkbox="{ row }">
                    <input 
                        type="checkbox" 
                        class="w-4 h-4 border-gray-300 text-primary-600 focus:ring-primary-500 rounded cursor-pointer"
                        :id="row.data.id"
                        variant="primary"
                        :checked="row.data.value === 'Yes'"
                        @click="updateZohoSync(row.data.name)"
                    />
                   
                </template>

                <template #cell-actions="{ row }">
                    <router-link
                    v-if="userStore.hasAbilities(abilities.VIEW_CRM_CONFIG)"
                    :to="`/admin/sync-settings/${row.data.name.toLowerCase()}`"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke-width="2" 
                            stroke="currentColor" 
                            aria-hidden="true" 
                            class="h-5 w-5"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </router-link>
                </template>
            </BaseTable>

            </BaseSettingCard>
        </div>

        <div v-else>
            <BaseSettingCard
                :title="$t('settings.crm_configuration.crm_none')"
                :description="$t('settings.crm_configuration.crm_none_description')"
            >
            </BaseSettingCard>
        </div>
    </form>
</template>

<style scoped>
.disabled-btn-cust {
    background-color: lightgrey;
    cursor: not-allowed;
}

svg { cursor: pointer; }
</style>

<script setup>
import { reactive, ref, inject, computed } from 'vue'

import { useGlobalStore } from '@/scripts/admin/stores/global'
import { useCompanyStore } from '@/scripts/admin/stores/company'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useI18n } from 'vue-i18n'
import { required, minLength, helpers } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useModalStore } from '@/scripts/stores/modal'
import { useRoleStore } from '@/scripts/admin/stores/role'
import { useUserStore } from '@/scripts/admin/stores/user'
import abilities from '@/scripts/admin/stub/abilities'

const companyStore = useCompanyStore()
const globalStore = useGlobalStore()
const modalStore = useModalStore()
const notificationStore = useNotificationStore()
const roleStore = useRoleStore()
const { t } = useI18n()
const utils = inject('utils')
const userStore = useUserStore()

let isSaving = ref(false)
let isDisabled = ref(false)
let isFetchingInitialData = ref(false)

let isZoho = ref(false)
const table = ref(null)

let currentUrl = window.location.href

let zohoSyncTable = ref(false);

const zohoSettings = reactive({
    zoho: {
        client_id: '',
        client_secret: '',
        call_back_uri: window.location.origin + '/oauth2callback'
    }
})

const crmSettingsForm = reactive({
    company_crm: ''
})

utils.mergeSettings(crmSettingsForm, {
  ...companyStore.selectedCompanySettings
})

if(crmSettingsForm.company_crm === 'zoho'){
    isZoho.value = true
    isDisabled = true
}

const rules = computed(() => {
    return {
        zoho: {
            client_id:{
                required: helpers.withMessage(t('validation.required'), required),
                minLength: helpers.withMessage(
                    t('validation.name_min_length'),
                    minLength(8)
                ),
            },
            client_secret:{
                required: helpers.withMessage(t('validation.required'), required),
                minLength: helpers.withMessage(
                    t('validation.name_min_length'),
                    minLength(8)
                ),
            },
            call_back_uri:{
                required: helpers.withMessage(t('validation.required'), required),
                minLength: helpers.withMessage(
                    t('validation.name_min_length'),
                    minLength(8)
                ),
            },
        }
    }
})

const v$ = useVuelidate(
    rules,
    computed(() => zohoSettings)
)

let data = {}
if(isZoho){
    data.crm = zohoSettings
}

async function testCrmConfig() {
    v$.value.$touch()

    if (v$.value.$invalid) {
        return true
    }

    if(isZoho){
        data.mode = 'test'
    }

    await companyStore.crmConfiguration({
        data,
        message: 'CRM Configuration Tested Successfully.'
    })
}

async function updateCrmConfig() {
    v$.value.$touch()

    if (v$.value.$invalid) {
        return true
    }
    
    if(isZoho){
        data.mode = 'production'
    }

    await companyStore.crmConfiguration({
        data,
        message: 'CRM configuration saved successfully.'
    })
   
}

let zoho_oauth_msz = getUrlParameter('zoho_oauth_msz')
let zoho_oauth_mode = getUrlParameter('zoho_oauth_mode')
let zoho_oauth_token_file = getUrlParameter('zoho_oauth_token_file')

if(zoho_oauth_msz && zoho_oauth_mode){
    if(zoho_oauth_msz === 'success'){
        if(zoho_oauth_mode === 'test'){
            notificationStore.showNotification({
                type: 'success',
                message: 'Zoho connection successfull.',
            })

            let zoho_client_id = getUrlParameter('zoho_client_id')
            let zoho_client_secret = getUrlParameter('zoho_client_secret')
            if(isZoho){
                zohoSettings.zoho.client_id = zoho_client_id
                zohoSettings.zoho.client_secret = zoho_client_secret
                isDisabled = false
                isSaving = false
            }
        }
        else if(zoho_oauth_mode === 'production'){      
            notificationStore.showNotification({
                type: 'success',
                message: 'Zoho configuration saved successfully.',
            })

            zohoSettings.zoho.client_id = companyStore.selectedCompanySettings.crm_client_id
            zohoSettings.zoho.client_secret = companyStore.selectedCompanySettings.crm_client_secret
            zohoSettings.zoho.call_back_uri = companyStore.selectedCompanySettings.crm_call_back_uri

            zohoSyncTable.value = true
        }
    }
    else if(zoho_oauth_msz === 'failed'){
        if(zoho_oauth_mode === 'test'){
            notificationStore.showNotification({
                type: 'error',
                message: 'Zoho connection failed.',
            })
        }
        else if(zoho_oauth_mode === 'production'){
            notificationStore.showNotification({
                type: 'error',
                message: 'Zoho configuration save failed.',
            })
        }
    }

    currentUrl = currentUrl.replace(window.location.href, window.location.origin + '/admin/settings/crm-config');
}
else {
    if(isZoho){
        zohoSettings.zoho.client_id = companyStore.selectedCompanySettings.crm_client_id
        zohoSettings.zoho.client_secret = companyStore.selectedCompanySettings.crm_client_secret
        zohoSettings.zoho.call_back_uri = window.location.origin + '/oauth2callback'

        if(companyStore.selectedCompanySettings.crm_client_id !== undefined){
            zohoSyncTable.value = true
        }
    }
}

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

currentUrl = currentUrl.replace(/[&?]$/, "");
window.history.replaceState({}, document.title, currentUrl);

const zohoSyncColumns = computed(() => {
    
    if(zohoSyncTable.value === true){
        return [
            {
                key: 'name',
                label: 'Sync',
                thClass: 'extra',
                tdClass: 'font-medium text-gray-900',
            },
            {
                key: 'checkbox',
                label: 'is enabled',
                thClass: 'extra w-10 pr-0',
                sortable: false,
                tdClass: 'font-medium text-gray-900 pr-0',
            },
            {
                key: 'actions',
                tdClass: 'text-right text-sm font-medium pl-0',
                thClass: 'pl-0',
                sortable: false,
            }
        ]
    }
  
})

async function zohoSyncs(){
    let data = {
        crm: 'zoho'
    }

    let response = await companyStore.fetchCrmSyncs({
        data,
        message: 'Fetched all syncs'
    })
   console.log(response.data)
    return {
        data: response.data
    }
}


async function updateZohoSync(name) {
    let data = {
        crm: 'zoho',
        name: name
    }

    let response = await companyStore.updateZohoSync({
        data,
        message: 'Zoho Sync Setting Updated' 
    })

    if(Object.hasOwn(response, 'data')){
        if(Object.hasOwn(response.data, 'success')){
            if(response.data.success = true){
                notificationStore.showNotification({
                    type: 'success',
                    message: 'Sync setting updated successfully.',
                })
            }
        }
    }
}
</script>