<template>
    <BaseSettingCard
        :title="$t('settings.menu_title.company_fields')"
        description=""
    >
        <template #action>
            <div class="flex items-center justify-end space-x-5">
                <BaseButton
                    variant="primary-outline"
                    @click="toggleFilter"
                >
                    {{ $t('general.filter') }}
                    <template #right="slotProps">
                    <BaseIcon
                        v-if="!showFilters"
                        :class="slotProps.class"
                        name="FilterIcon"
                    />
                    <BaseIcon v-else name="XIcon" :class="slotProps.class" />
                    </template>
                </BaseButton>

                <BaseButton
                    variant="primary-outline"
                    @click="addCompanyField"
                >
                <template #left="slotProps">
                    <BaseIcon :class="slotProps.class" name="PlusIcon" />

                    {{ $t('settings.company_fields.add_company_field') }}
                </template>
                </BaseButton>
            </div>
            
        </template>

        <CompanyFieldModal/>

        <BaseFilterWrapper :show="showFilters" class="mt-5" @clear="clearFilter">
            <BaseInputGroup :label="$t('settings.company_fields.table')" class="text-left">
                <BaseMultiselect
                    v-model="filters.name"
                    :placeholder="$t('settings.company_fields.select_a_table')"
                    class="w-full"
                    :options="tableTypes"
                    value-prop="id"
                    track-by="name"
                    :filter-results="false"
                    label="name"
                    resolve-on-load
                    searchable
                    :delay="500"
                />
            </BaseInputGroup>
        </BaseFilterWrapper>

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

        <template #cell-is_required="{ row }">
            <BaseBadge
            :bg-color="
                utils.getBadgeStatusColor(row.data.is_required ? 'YES' : 'NO')
                .bgColor
            "
            :color="
                utils.getBadgeStatusColor(row.data.is_required ? 'YES' : 'NO').color
            "
            >
            {{
                row.data.is_required
                ? $t('settings.company_fields.yes')
                : $t('settings.company_fields.no').replace('_', ' ')
            }}
            </BaseBadge>
        </template>
        <template
            v-if="
            userStore.hasAbilities([
                abilities.DELETE_COMPANY_FIELDS,
                abilities.EDIT_COMPANY_FIELDS,
            ])
            "
            #cell-actions="{ row }"
        >
            <CompanyFieldDropdown
            :row="row.data"
            :table="table"
            :load-data="refreshTable"
            />
        </template>
        </BaseTable>
    </BaseSettingCard>
</template>

<script setup>
import { reactive, ref, inject, computed, onMounted } from 'vue'
import { debouncedWatch } from '@vueuse/core'
import { useI18n } from 'vue-i18n'
import { useModalStore } from '@/scripts/stores/modal'
import { useUserStore } from '@/scripts/admin/stores/user'
import { useCompanyFieldStore } from '@/scripts/admin/stores/company-field'
import CompanyFieldModal from '@/scripts/admin/components/modal-components/CompanyFieldModal.vue'
import CompanyFieldDropdown from '@/scripts/admin/components/dropdowns/CompanyFieldIndexDropdown.vue'
import abilities from '@/scripts/admin/stub/abilities'

const utils = inject('utils')
const { t } = useI18n()
const companyFieldStore = useCompanyFieldStore()
const modalStore = useModalStore()
const userStore = useUserStore()

let showFilters = ref(false)

const filters = reactive({
    name: ''
})

const table = ref(null)

const companyFieldsColumns = computed(() => {
  return [
    {
      key: 'table_name',
      label: t('settings.company_fields.table_name'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900'
    },
    {
      key: 'column_name',
      label: t('settings.company_fields.column_name')
    },
    {
      key: 'column_type',
      label: t('settings.company_fields.column_type')
    },
    {
      key: 'is_required',
      label: t('settings.company_fields.required')
    },
    {
      key: 'field_type',
      label: t('settings.company_fields.field_type')
    },
    {
      key: 'actions',
      label: '',
      tdClass: 'text-right text-sm font-medium',
      sortable: false
    },
  ]
})

function clearFilter() {
    filters.name = ''
}

function setFilters() {
    refreshTable()
}

debouncedWatch(
  filters,
  () => {
    setFilters()
  },
  { debounce: 500 }
)

function toggleFilter() {
    if(showFilters.value){
        clearFilter()
    }
    showFilters.value = !showFilters.value
}

async function tableTypes() {
    let  tableTypes = [
                'items',
                'users'
            ]

    return tableTypes
}

async function fetchData({ page, filter, sort }) {

  let data = {
    search: filters.name,
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
        refreshData: table.value && table.value.refresh,
    })
}

async function refreshTable() {
    table.value && table.value.refresh()
}

</script>