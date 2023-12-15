<template>
    <BasePage>
        <BasePageHeader title="Sync Settings">
            <BaseBreadcrumb>
                <BaseBreadcrumbItem :title="$t('general.home')" to="dashboard"/>
                <BaseBreadcrumbItem title="Item" active/>
            </BaseBreadcrumb>

            <template #actions>
                <div class="flex items-center justify-end space-x-5">
                    <BaseButton
                        @click="createSyncItem"
                    >
                    <template #left="slotProps">
                        <BaseIcon name="PlusIcon" :class="slotProps.class" />
                    </template>
                    Add Sync Item
                </BaseButton>
                </div>
            </template>
        </BasePageHeader>

        <div class="relative table-container mt-16">
            <div class="px-2 flex" v-for="(value, key) in crm_products[0]" :key="key">
                <div class="w-1/3  h-12">
                    {{ key }}
                </div>
                <div class="w-1/3  h-12">
                    {{ value }}
                </div>
                <div class="w-1/3  h-12">
                    <select  class="text-sm relative flex items-center h-10 pl-2
                        bg-gray-200 border border-gray-200 border-solid rounded">
                        <option value="INR"> INR </option>
                        <option value="AED"> AED </option>
                        <option value="USD"> USD </option>
                        <option value="SAARC"> SAARC </option>
                        <option value="ROW"> ROW </option>
                    </select>
                </div>
            </div>
        </div>
    </BasePage>
</template>

<script setup>
import { reactive, ref, inject, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router'
import { useSyncStore } from '@/scripts/admin/stores/sync-settings'

const table = ref(null)
const route = useRoute()
const router = useRouter()
const syncStore = useSyncStore()

const syncItemColumns = computed(() => {
    return [
        {
            key: 'field_name',
            label: 'Field Name',
            thClass: 'extra',
            tdClass: 'font-medium text-gray-900',
        },
        {
            key: 'column_name',
            label: 'Column Name',
            thClass: 'extra',
            tdClass: 'font-medium text-gray-900',
        },
        {
            key: 'status',
            label: 'Status',
            thClass: 'extra',
            tdClass: 'font-medium text-gray-900',
        }
    ]
})

const  syncItemData = computed(() =>{
    let data = [
            { id: 1, field_name: 'price_aed', column_name: 'price_ad'}
        ]
    
        console.log(data)
    
    return data
})

async function createSyncItem(){
    router.push(`/admin/sync-settings/create/${route.params.name}`)
}

let crm_products = ref([])
let item_columns = ref(null)

crmProducts()

async function crmProducts(){
    let crm_products_response = await syncStore.fetchCrmProducts()
    
    if(crm_products_response.data){
        if(crm_products_response.data.data){
            crm_products = crm_products_response.data.data
            console.log(crm_products)
        }
    }

    let item_columns_response = await syncStore.fetchItemColumns()
   
    if(item_columns_response.data.item_columns){
        item_columns = item_columns_response.data.item_columns
        console.log(item_columns)
    }
}
</script>