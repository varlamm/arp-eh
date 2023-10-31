<template>
    <form>
        <BaseSettingCard
            :title="$t('settings.crm_configuration.configuration')"
            :description="$t('settings.crm_configuration.description')"
        >

        <BaseInputGrid class="mt-5">
            <BaseInputGroup
            :label="$t('settings.crm_configuration.client_id')"
            required
            >
            <BaseInput
            />
            </BaseInputGroup>
        </BaseInputGrid>
        
        <BaseInputGrid class="mt-5">
            <BaseInputGroup
            :label="$t('settings.crm_configuration.client_secret')"
            required
            >
            <BaseInput
            />
            </BaseInputGroup>
        </BaseInputGrid>

        <div class="grid-cols-2 col-span-1 mt-5">
            <BaseInputGroup
            :label="$t('settings.crm_configuration.auth_callback_url')"
            required
            >
            <BaseInput
            />
            </BaseInputGroup>
        </div>
        
        <div class="mt-5">
            <BaseButton
                variant="primary-outline"
                type="button"
                class="ml-2"
                >
                {{ $t('settings.crm_configuration.test') }}
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
                {{ $t('settings.crm_configuration.save') }}
            </BaseButton>
        </div>

      

        </BaseSettingCard>
    </form>
</template>

<script setup>
import { reactive, ref, inject, computed } from 'vue'

import { useGlobalStore } from '@/scripts/admin/stores/global'
import { useCompanyStore } from '@/scripts/admin/stores/company'
import { useI18n } from 'vue-i18n'
import { required, minLength, helpers } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useModalStore } from '@/scripts/stores/modal'

const companyStore = useCompanyStore()
const globalStore = useGlobalStore()
const modalStore = useModalStore()
const { t } = useI18n()
const utils = inject('utils')

let isSaving = ref(false)

</script>