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
                    :loading="isSaving"
                    :disabled="isSaving"
                    type="submit"
                    class="ml-6"
                    >
                    <template #left="slotProps">
                    <BaseIcon v-if="!isSaving" :class="slotProps.class" name="SaveIcon" />
                    </template>
                    {{ $t('settings.crm_configuration.zoho_crm_save') }}
                </BaseButton>
            </div>

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

<script setup>
import { reactive, ref, inject, computed } from 'vue'

import { useGlobalStore } from '@/scripts/admin/stores/global'
import { useCompanyStore } from '@/scripts/admin/stores/company'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useI18n } from 'vue-i18n'
import { required, minLength, helpers } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useModalStore } from '@/scripts/stores/modal'

const companyStore = useCompanyStore()
const globalStore = useGlobalStore()
const modalStore = useModalStore()
const notificationStore = useNotificationStore()
const { t } = useI18n()
const utils = inject('utils')

let isSaving = ref(false)
let isFetchingInitialData = ref(false)
let isZoho = ref(false)
isZoho.value = false

let currentUrl = window.location.href;

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

</script>