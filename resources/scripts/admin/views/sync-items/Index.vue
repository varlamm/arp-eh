<template>
    <BasePage>
        <form action="" @submit.prevent="submitSyncSettings">
            <BasePageHeader title="Sync Settings">
                <BaseBreadcrumb>
                    <BaseBreadcrumbItem :title="$t('general.home')" to="dashboard"/>
                    <BaseBreadcrumbItem title="Item" active/>
                </BaseBreadcrumb>

                <template #actions>
                    <div class="flex items-center justify-end space-x-5">
                        <BaseButton
                            :loading="isSaving"
                            :disabled="isSaving"
                            variant="primary"
                            type="submit"
                            class="mt-4"
                        >
                        <template #left="slotProps">
                            <BaseIcon v-if="!isSaving" :class="slotProps.class" name="SaveIcon" />
                        </template>
                            Save Settings
                        </BaseButton>
                    </div>
                </template>
            </BasePageHeader>

            <div class="relative table-container mt-16">
                <div class="px-2 flex">
                    <div class="w-1/6  h-12">
                        <span> API Key </span>
                    </div>
                    <div class="w-1/6  h-12">
                        <span> API Value </span>
                    </div>
                    <div class="w-1/6  h-12">
                        <span> Column Name </span>
                    </div>
                    <div class="w-1/6  h-12">
                        <span> Column Type </span>
                    </div>
                    <div class="w-1/6  h-12">
                        <span> Custom Field </span>
                    </div>

                    <div class="w-1/6  h-12">
                        <span> Standard Mapping </span>
                    </div>
                </div>
                <div class="px-2 flex" v-for="(item, index, key) in form" :key="key">
                    <div class="w-1/6  h-12">
                        {{ item.api_key }}
                    </div>
                    <div class="w-1/6  h-12">
                        {{ item.api_key_value }}
                    </div>
                    
                    <div class="w-1/6  h-12">
                        <select @change="event => onColumnNameChange(event, index)" 
                            class="
                                text-sm 
                                relative 
                                flex 
                                items-center 
                                h-10 
                                pl-2
                                bg-gray-200 
                                border 
                                border-gray-200 
                                border-solid 
                                rounded"
                            >
                            <option value="">-- Select --</option>
                            <option :selected="item.api_key === table_col.crm_mapped_field" :value="col_index" v-for="(table_col, col_index, col_key) in table_columns" :key="col_key"> {{ table_col.column_name }} </option>
                        </select>
                    </div>

                    <div class="w-1/6  h-12">
                        {{ item.column_type }}
                    </div>

                    <div class="w-1/6  h-12">
                        <svg v-if="!item.is_standard && item.column_name !== ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div class="w-1/6  h-12">
                        <input 
                            type="checkbox" 
                            class="w-4 h-4 border-gray-300 text-primary-600 focus:ring-primary-500 rounded cursor-pointer"
                            v-if="item.column_name !== ''"
                            variant="primary"
                            :checked="item.is_crm_standard_mapping"
                            @click="event => onStandardMappingClick(event, index)"
                        />
                    </div>
                </div>
            </div>
        </form>
    </BasePage>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { useSyncStore } from '@/scripts/admin/stores/sync-settings'

const syncStore = useSyncStore()
let isSaving = ref(false)
const form = ref([])
const table_columns = ref(null)

crmProducts()

async function crmProducts(){
    let response = await syncStore.fetchCrmProducts()
    
    if(response.data.crm_products.data){
        let products = response.data.crm_products.data[0]
        
        for (var key  in products) {
            if(products.hasOwnProperty(key)) {
                form.value.push({
                    api_key: key,
                    api_key_value: products[key],
                    column_row_id: null,
                    column_name: '',
                    column_type: '',
                    crm_mapped_field: '',
                    is_standard: false,
                    is_crm_standard_mapping: false
                })
            }
        }
       
        fetchTableColumns('items')
    }
}

async function fetchTableColumns(tableName) {
    let params = {
        table_name: tableName
    }

   let tableColumns = await syncStore.fetchTableColumns(params)

   if(tableColumns.data.table_columns){
        table_columns.value = tableColumns.data.table_columns
        setStandardMappingField()
   }
}

async function setStandardMappingField() {
    for(let i=0; i<form.value.length; i++){
        table_columns.value.forEach(element => {
            if(element.crm_mapped_field === form.value[i].api_key){
                form.value[i].column_name = element.column_name
                form.value[i].is_crm_standard_mapping = element.is_crm_standard_mapping
                form.value[i].column_type = element.column_type

                if(element.field_type === 'standard'){
                    form.value[i].is_standard = true
                }
            }
        });
    }
}

function onColumnNameChange(event, index) {
    let colIndex = event.target.value
    this.form[index].is_standard = false
    
    if(table_columns.value[colIndex] !== undefined){
        if(table_columns.value[colIndex].field_type === 'standard'){
            this.form[index].is_standard = true
        }

        this.form[index].column_name = table_columns.value[colIndex].column_name
        this.form[index].column_type = table_columns.value[colIndex].column_type
        this.form[index].column_row_id = table_columns.value[colIndex].id
        this.form[index].crm_mapped_field = table_columns.value[colIndex].crm_mapped_field
    }
    else{
        this.form[index].column_name = ''
        this.form[index].column_type = ''
        this.form[index].column_row_id = null
        this.form[index].crm_mapped_field = ''
    }
}

function onStandardMappingClick(event, index) {
    this.form[index].is_crm_standard_mapping = false
    if(event.target.value !== undefined){
        this.form[index].is_crm_standard_mapping = !this.form[index].is_crm_standard_mapping
    }
}

async function submitSyncSettings(){
    isSaving.value = true
    let res = await syncStore.submitSyncSettings(form.value)
    if(res.data.success){
        isSaving.value = false
    }
}
</script>