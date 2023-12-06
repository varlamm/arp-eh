<template>
    <BaseSettingCard
        :title="$t('settings.menu_title.company_fields')"
        :description="$t('settings.company_fields.section_description')"
    >
        <template #action>
            <BaseButton
                variant="primary-outline"
                @click="addCompanyField"
            >
            
            <template #left="slotProps">
                <BaseIcon :class="slotProps.class" name="PlusIcon" />

                {{ $t('settings.company_fields.add_company_field') }}
            </template>
            </BaseButton>
        </template>

        <CompanyFieldModal/>

        <BaseTable
            ref="table"
            :data="fetchData"
            :columns="companyFieldsColumns"
            class="mt-16"
        >
        <template #cell-name="{ row }">
            {{ row.data.name }}
            <span class="text-xs text-gray-500"> ({{ row.data.slug }})</span>
        </template>
        </BaseTable>
    </BaseSettingCard>
</template>

<script setup>
import { reactive, ref, inject, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useModalStore } from '@/scripts/stores/modal'
import { useCompanyFieldStore } from '@/scripts/admin/stores/company-field'
import CompanyFieldModal from '@/scripts/admin/components/modal-components/CompanyFieldModal.vue'

const utils = inject('utils')
const { t } = useI18n()
const companyFieldStore = useCompanyFieldStore()
const modalStore = useModalStore()

const table = ref(null)

const companyFieldsColumns = computed(() => {
  return [
    {
      key: 'column_name',
      label: t('settings.company_fields.column_name'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900',
    },
    {
      key: 'table_name',
      label: t('settings.company_fields.table_name'),
    },
    {
      key: 'column_type',
      label: t('settings.company_fields.column_type'),
    },
    {
      key: 'is_required',
      label: t('settings.company_fields.required'),
    },
    {
      key: 'actions',
      label: '',
      tdClass: 'text-right text-sm font-medium',
      sortable: false,
    },
  ]
})

async function fetchData({ page, filter, sort }) {
  let data = {
    orderByField: sort.fieldName || 'created_at',
    orderBy: sort.order || 'desc',
    page,
  }

  let response = await companyFieldStore.fetchCompanyFields(data)

  return {
    data: response.data.data,
    pagination: {
      totalPages: response.data.meta.last_page,
      currentPage: page,
      limit: 5,
      totalCount: response.data.meta.total,
    },
  }
}

function addCompanyField() {
    modalStore.openModal({
        title: 'Add Company Field',
        componentName: 'CompanyFieldModal',
        size: 'sm',
        refreshData: table.value && table.value.refresh(),
    })
}

async function refreshTable() {
    table.value && table.value.refresh()
}

</script>