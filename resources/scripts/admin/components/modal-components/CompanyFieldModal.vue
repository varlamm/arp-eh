<template>
    <BaseModal :show="modalActive" @open="setData">
        <template #header>
            <div class="flex justify-between w-full">
                {{ modalStore.title }}

                <BaseIcon
                name="XIcon"
                class="w-6 h-6 text-gray-500 cursor-pointer"
                @click="closeCompanyFieldModal"
                />
            </div>
        </template>

        <form action="" @submit.prevent="submitCompanyFieldData">
            <div class="overflow-y-auto max-h-[550px]">
                <div class="px-4 md:px-8 py-8 overflow-y-auto sm:p-6">
                    <BaseInputGrid layout="one-column">
                        <BaseInputGroup
                            :label="$t('settings.company_fields.column_name')"
                            required
                            :error="
                                v$.currentCompanyField.column_name.$error &&
                                v$.currentCompanyField.column_name.$errors[0].$message
                            "
                        >
                        <BaseInput
                            :disabled="companyFieldStore.isEdit"
                            v-model="companyFieldStore.currentCompanyField.column_name"
                            :invalid="v$.currentCompanyField.column_name.$error"
                            @input="v$.currentCompanyField.column_name.$touch()"
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            :label="$t('settings.company_fields.table_name')"
                            :error="
                                v$.currentCompanyField.table_name.$error &&
                                v$.currentCompanyField.table_name.$errors[0].$message
                            "
                            required
                        >

                        <BaseMultiselect
                            :disabled="companyFieldStore.isEdit"
                            v-model="companyFieldStore.currentCompanyField.table_name"
                            :options="tableTypes"
                            :can-deselect="false"
                            :invalid="v$.currentCompanyField.table_name.$error"
                            :searchable="true"
                            @input="v$.currentCompanyField.table_name.$touch()"
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            class="flex items-center space-x-4"
                            :label="$t('settings.company_fields.required')"
                        >
                            <BaseSwitch v-model="isRequiredField" />
                        </BaseInputGroup>

                        <BaseInputGroup
                        :label="$t('settings.company_fields.column_type')"
                        :error="
                            v$.currentCompanyField.column_type.$error &&
                            v$.currentCompanyField.column_type.$errors[0].$message
                        "
                        required
                        >
                        <BaseMultiselect
                            :disabled="companyFieldStore.isEdit"
                            v-model="selectedType"
                            :options="dataTypes"
                            :invalid="v$.currentCompanyField.column_type.$error"
                            :searchable="true"
                            :can-deselect="false"
                            object
                            @update:modelValue="onSelectedTypeChange"
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            :label="$t('settings.company_fields.label')"
                            required
                            :error="
                                v$.currentCompanyField.label.$error &&
                                v$.currentCompanyField.label.$errors[0].$message
                            "
                        >
                        <BaseInput
                            v-model="companyFieldStore.currentCompanyField.label"
                            :invalid="v$.currentCompanyField.label.$error"
                            @input="v$.currentCompanyField.label.$touch()"
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            v-if="isDropdownSelected"
                            :label="$t('settings.company_fields.options')"
                        >
                        <OptionCreate @onAdd="addNewOption" />

                        <div
                            v-for="(option, index) in companyFieldStore.currentCompanyField
                            .options"
                            :key="index"
                            class="flex items-center mt-5"
                        >
                            <BaseInput v-model="option.name" class="w-64" />

                            <BaseIcon
                                name="MinusCircleIcon"
                                class="ml-1 cursor-pointer"
                                @click="removeOption(index)"
                            />
                        </div>
                        </BaseInputGroup>

                        <BaseInputGroup
                            :label="$t('settings.company_fields.default_value')"
                            class="relative"
                        >
                        <component
                            :is="defaultValueComponent"
                            v-model="companyFieldStore.currentCompanyField.default_value"
                            :options="companyFieldStore.currentCompanyField.options"
                            :default-date-time="
                            companyFieldStore.currentCompanyField.dateTimeValue
                            "
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            :label="$t('settings.company_fields.field_type')"
                            required
                            :error="
                                v$.currentCompanyField.field_type.$error &&
                                v$.currentCompanyField.field_type.$errors[0].$message
                            "
                            v-if="userRole === 'super admin'"
                        >
                        <BaseRadio
                            id="field-standard"
                            v-model="companyFieldStore.currentCompanyField.field_type"
                            :label="$t('settings.company_fields.standard')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="field_type"
                            value="standard"
                            class="mt-2"
                        />

                        <BaseRadio
                            id="field-custom"
                            v-model="companyFieldStore.currentCompanyField.field_type"
                            :label="$t('settings.company_fields.custom')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="field_type"
                            value="custom"
                            class="mt-2"
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            :label="$t('settings.company_fields.is_unique')"
                            required
                            :error="
                                v$.currentCompanyField.is_unique.$error &&
                                v$.currentCompanyField.is_unique.$errors[0].$message
                            "
                        >

                        <BaseRadio
                            id="field-yes-unique"
                            v-model="companyFieldStore.currentCompanyField.is_unique"
                            :label="$t('settings.company_fields.yes')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="is_unique"
                            value="yes"
                            class="mt-2"
                        />

                        <BaseRadio
                            id="field-not-unique"
                            v-model="companyFieldStore.currentCompanyField.is_unique"
                            :label="$t('settings.company_fields.no')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="is_unique"
                            value="no"
                            class="mt-2"
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            :label="$t('settings.company_fields.visiblity')"
                            required
                            :error="
                                v$.currentCompanyField.visiblity.$error &&
                                v$.currentCompanyField.visiblity.$errors[0].$message
                            "
                        >

                        <BaseRadio
                            id="field-yes-visible"
                            v-model="companyFieldStore.currentCompanyField.visiblity"
                            :label="$t('settings.company_fields.visible')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="visiblity"
                            value="visible"
                            class="mt-2"
                        />

                        <BaseRadio
                            id="field-not-visible"
                            v-model="companyFieldStore.currentCompanyField.visiblity"
                            :label="$t('settings.company_fields.hidden')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="visiblity"
                            value="hidden"
                            class="mt-2"
                        />
                        </BaseInputGroup>
                        
                        <BaseInputGroup
                            :label="$t('settings.company_fields.listing_page')"
                            required
                            :error="
                                v$.currentCompanyField.listing_page.$error &&
                                v$.currentCompanyField.listing_page.$errors[0].$message
                            "
                        >

                        <BaseRadio
                            id="field-listing-page-true"
                            v-model="companyFieldStore.currentCompanyField.listing_page"
                            :label="$t('settings.company_fields.yes')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="listing_page"
                            value="yes"
                            class="mt-2"
                        />

                        <BaseRadio
                            id="field-listing-page-false"
                            v-model="companyFieldStore.currentCompanyField.listing_page"
                            :label="$t('settings.company_fields.no')"
                            :content-loading="isFetchingInitialData"
                            size="sm"
                            name="listing_page"
                            value="no"
                            class="mt-2"
                        />
                        </BaseInputGroup>
                        
                        <BaseInputGroup
                            :label="$t('settings.company_fields.order_listing_page')"
                        >
                        <BaseInput
                            v-model="companyFieldStore.currentCompanyField.order_listing_page"
                            type="number"
                        />
                        </BaseInputGroup>

                        <BaseInputGroup
                            :label="$t('settings.company_fields.order_form_page')"
                        >
                        <BaseInput
                            v-model="companyFieldStore.currentCompanyField.order_form_page"
                            type="number"
                        />
                        </BaseInputGroup>

                    </BaseInputGrid>
                </div>
            </div>
            <div class="z-0 flex justify-end p-4 border-t border-solid border-gray-light border-modal-bg">
                <BaseButton
                    class="mr-3"
                    type="button"
                    variant="primary-outline"
                    @click="closeCompanyFieldModal"
                >
                    {{ $t('general.cancel') }}
                </BaseButton>

                <BaseButton
                    variant="primary"
                    :loading="isSaving"
                    :disabled="isSaving"
                    type="submit"
                >
                    <template #left="slotProps">
                        <BaseIcon
                        v-if="!isSaving"
                        :class="slotProps.class"
                        name="SaveIcon"
                        />
                    </template>
                    {{
                        !companyFieldStore.isEdit ? $t('general.save') : $t('general.update')
                    }}
                </BaseButton>
            </div>
        </form>
    </BaseModal>
</template>

<script setup>
import { reactive, ref, computed, defineAsyncComponent } from 'vue'
import moment from 'moment'
import OptionCreate from '@/scripts/admin/components/modal-components/custom-fields/OptionsCreate.vue'
import { useModalStore } from '@/scripts/stores/modal'
import { useUserStore } from '@/scripts/admin/stores/user'
import useVuelidate from '@vuelidate/core'
import { required, numeric, helpers } from '@vuelidate/validators'
import { useCompanyFieldStore } from '@/scripts/admin/stores/company-field'
import { useI18n } from 'vue-i18n'

const modalStore = useModalStore()
const companyFieldStore = useCompanyFieldStore()
const userStore = useUserStore()
const { t } = useI18n()

let isSaving = ref(false)
let isFetchingInitialData = ref(false)

const tableTypes = reactive([
    { label: 'Item', value: 'items' },
    { label: 'User', value: 'users' },
    { label: 'Crm Role', value: 'crm_roles' }
])

const dataTypes = reactive([
  { label: 'Text', value: 'Input' },
  { label: 'Price', value: 'Price' },
  { label: 'Textarea', value: 'TextArea' },
  { label: 'Phone', value: 'Phone' },
  { label: 'URL', value: 'Url' },
  { label: 'Number', value: 'Number' },
  { label: 'Select Field', value: 'Dropdown' },
  { label: 'Switch Toggle', value: 'Switch' },
  { label: 'Date', value: 'Date' },
  { label: 'Time', value: 'Time' },
  { label: 'Date & Time', value: 'DateTime' },
])


let selectedType = ref(dataTypes[0])

const modalActive = computed(() => {
  return modalStore.active && modalStore.componentName === 'CompanyFieldModal'
})

const isSwitchTypeSelected = computed(
  () => selectedType.value && selectedType.value.label === 'Switch Toggle'
)

const isDropdownSelected = computed(
  () => selectedType.value && selectedType.value.label === 'Select Field'
)

const defaultValueComponent = computed(() => {
  if (companyFieldStore.currentCompanyField.column_type) {
    return defineAsyncComponent(() =>
      import(
        `../custom-fields/types/${companyFieldStore.currentCompanyField.column_type}Type.vue`
      )
    )
  }

  return false
})

const userRole = computed(() => {
  return userStore.currentUser.role
})

const rules = computed(() => {
    return {
        currentCompanyField: {
            column_name: {
                required: helpers.withMessage(t('validation.required'), required),
            },

            table_name: {
                required: helpers.withMessage(t('validation.required'), required)
            },

            column_type: {
                required: helpers.withMessage(t('validation.required'), required)
            },

            label: {
                required: helpers.withMessage(t('validation.required'), required)
            },

            field_type: {
                required: helpers.withMessage(t('validation.required'), required)
            },

            is_unique: {
                required: helpers.withMessage(t('validation.required'), required)
            },

            visiblity: {
                required: helpers.withMessage(t('validation.required'), required)
            },

            listing_page: {
                required: helpers.withMessage(t('validation.required'), required)
            }
        }
    }
})

const v$ = useVuelidate(
  rules,
  computed(() => companyFieldStore)
)

function setData() {
  if (companyFieldStore.isEdit) {
    selectedType.value = dataTypes.find(
      (type) => type.value == companyFieldStore.currentCompanyField.column_type
    )
  } else {
    companyFieldStore.currentCompanyField.column_type = tableTypes[0]

    companyFieldStore.currentCompanyField.column_type = dataTypes[0].value
    selectedType.value = dataTypes[0]
  }
}

const isRequiredField = computed({
  get: () => companyFieldStore.currentCompanyField.is_required === 1,
  set: (value) => {
    const intVal = value ? 1 : 0
    companyFieldStore.currentCompanyField.is_required = intVal
  },
})

function addNewOption(option) {
    companyFieldStore.currentCompanyField.options = [
    { name: option },
    ...companyFieldStore.currentCompanyField.options,
  ]
}

function removeOption(index) {
  if (companyFieldStore.isEdit && companyFieldStore.currentCompanyField.in_use) {
    return
  }

  const option = companyFieldStore.currentCompanyField.options[index]

  if (option.name === companyFieldStore.currentCompanyField.default_value) {
    companyFieldStore.currentCompanyField.default_value = null
  }

  companyFieldStore.currentCompanyField.options.splice(index, 1)
}

function onSelectedTypeChange(data) {
    companyFieldStore.currentCompanyField.column_type = data.value
}

async function submitCompanyFieldData() {
    v$.value.currentCompanyField.$touch()

    if (v$.value.currentCompanyField.$invalid) {
        return true
    }

    isSaving.value = true

    let data = {
        ...companyFieldStore.currentCompanyField,
    }

    if (companyFieldStore.currentCompanyField.options) {
        data.options = companyFieldStore.currentCompanyField.options.map(
        (option) => option.name
        )
    }

    if (data.column_type == 'Time' && typeof data.default_value == 'object') {
        let HH =
        data && data.default_value && data.default_value.HH
            ? data.default_value.HH
            : null
        let mm =
        data && data.default_value && data.default_value.mm
            ? data.default_value.mm
            : null
        let ss =
        data && data.default_value && data.default_value.ss
            ? data.default_value.ss
            : null

        data.default_value = `${HH}:${mm}`
    }

    const action = companyFieldStore.isEdit
        ? companyFieldStore.updateCompanyField
        : companyFieldStore.addCompanyField

    let response = await action(data)

    isSaving.value = false

    if(response.status !== 205){
        modalStore.refreshData ? modalStore.refreshData() : ''
    
        closeCompanyFieldModal()
    }
    
}

function onChangeReset() {
    companyFieldStore.$patch((state) => {
    state.currentCompanyField.default_value = null
    state.currentCompanyField.is_required = false
    state.currentCompanyField.placeholder = null
    state.currentCompanyField.options = []
  })

  v$.value.$reset()
}

function closeCompanyFieldModal() {
    modalStore.closeModal()

    setTimeout(() => {
        companyFieldStore.resetCurrentCompanyField()
        v$.value.$reset()
    }, 300)
}
</script>