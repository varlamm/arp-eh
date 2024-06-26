<template>
  <tr class="box-border bg-white border border-gray-200 border-solid rounded-b">
    <td colspan="5" class="p-0 text-left align-top">
      <table class="w-full">
        <colgroup>
          <col style="width: 40%; min-width: 280px" />
          <col style="width: 10%; min-width: 120px" />
          <col style="width: 15%; min-width: 120px" />
          <col
            v-if="store[storeProp].discount_per_item === 'YES'"
            style="width: 15%; min-width: 160px"
          />
          <col style="width: 15%; min-width: 120px" />
        </colgroup>
        <tbody>
          <tr>
            <td class="px-5 py-4 text-left align-top">
              <div class="flex justify-start">
                <div
                  class="flex items-center justify-center w-5 h-5 mt-2 mr-2 text-gray-300 cursor-move  handle"
                >
                  <DragIcon />
                </div>
                <BaseItemSelect
                  type="Invoice"
                  :item="itemData"
                  :invalid="v$.name.$error"
                  :invalid-description="v$.description.$error"
                  :taxes="itemData.taxes"
                  :index="index"
                  :store-prop="storeProp"
                  :store="store"
                  @search="searchVal"
                  @select="onSelectItem"
                />
              </div>
            </td>
            <td class="px-5 py-4 text-right align-top">
              <BaseInput
                v-model="quantity"
                :invalid="v$.quantity.$error"
                :content-loading="loading"
                type="number"
                small
                min="0"
                step="any"
                @change="syncItemToStore()"
                @input="v$.quantity.$touch()"
              />
            </td>
            <td class="px-5 py-4 text-left align-top">
              <div class="flex flex-col">
                <div class="flex-auto flex-fill bd-highlight">
                  <div class="relative w-full">
                    <BaseMoney
                      :key="selectedCurrency"
                      v-model="price"
                      :invalid="v$.price.$error"
                      :content-loading="loading"
                      :currency="selectedCurrency"
                    />
                  </div>
                </div>
              </div>
            </td>
            <td
              v-if="store[storeProp].discount_per_item === 'YES'"
              class="px-5 py-4 text-left align-top"
            >
              <div class="flex flex-col">
                <div class="flex" style="width: 120px" role="group">
                  <BaseInput
                    v-model="discount"
                    :invalid="v$.discount_val.$error"
                    :content-loading="loading"
                    class="
                      border-r-0
                      focus:border-r-2
                      rounded-tr-sm rounded-br-sm
                      h-[38px]
                    "
                  />
                  <BaseDropdown position="bottom-end">
                    <template #activator>
                      <BaseButton
                        :content-loading="loading"
                        class="rounded-tr-md rounded-br-md !p-2 rounded-none"
                        type="button"
                        variant="white"
                      >
                        <span class="flex items-center">
                          {{
                            itemData.discount_type == 'fixed'
                              ? currency.symbol
                              : '%'
                          }}

                          <BaseIcon
                            name="ChevronDownIcon"
                            class="w-4 h-4 ml-1 text-gray-500"
                          />
                        </span>
                      </BaseButton>
                    </template>

                    <BaseDropdownItem @click="selectFixed">
                      {{ $t('general.fixed') }}
                    </BaseDropdownItem>

                    <BaseDropdownItem @click="selectPercentage">
                      {{ $t('general.percentage') }}
                    </BaseDropdownItem>
                  </BaseDropdown>
                </div>
              </div>
            </td>
            <td class="px-5 py-4 text-right align-top">
              <div class="flex items-center justify-end text-sm">
                <span>
                  <BaseContentPlaceholders v-if="loading">
                    <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
                  </BaseContentPlaceholders>

                  <BaseFormatMoney
                    v-else
                    :amount="total"
                    :currency="selectedCurrency"
                  />
                </span>
                <div class="flex items-center justify-center w-6 h-10 mx-2">
                  <BaseIcon
                    v-if="showRemoveButton"
                    class="h-5 text-gray-700 cursor-pointer"
                    name="TrashIcon"
                    @click="store.removeItem(index)"
                  />
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="store[storeProp].tax_per_item === 'YES'">
            <td class="px-5 py-4 text-left align-top" />
            <td colspan="4" class="px-5 py-4 text-left align-top">
              <BaseContentPlaceholders v-if="loading">
                <BaseContentPlaceholdersText
                  :lines="1"
                  class="w-24 h-8 border rounded-md"
                />
              </BaseContentPlaceholders>

              <ItemTax
                v-for="(tax, index1) in itemData.taxes"
                v-else
                :key="tax.id"
                :index="index1"
                :item-index="index"
                :tax-data="tax"
                :taxes="itemData.taxes"
                :discounted-total="total"
                :total-tax="totalSimpleTax"
                :total="subtotal"
                :currency="currency"
                :update-items="syncItemToStore"
                :ability="abilities.CREATE_INVOICE"
                :store="store"
                :store-prop="storeProp"
                :discount="discount"
                @update="updateTax"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
</template>

<script setup>
import { computed, ref, inject, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Guid from 'guid'
import TaxStub from '@/scripts/admin/stub/tax'
import ItemTax from './CreateItemRowTax.vue'
import { sumBy } from 'lodash'
import abilities from '@/scripts/admin/stub/abilities'
import {
  required,
  between,
  maxLength,
  helpers,
  minValue,
} from '@vuelidate/validators'
import useVuelidate from '@vuelidate/core'
import { useCompanyStore } from '@/scripts/admin/stores/company'
import { useItemStore } from '@/scripts/admin/stores/item'
import DragIcon from '@/scripts/components/icons/DragIcon.vue'
import invoice from '../../stub/invoice'

const props = defineProps({
  store: {
    type: Object,
    default: null,
  },
  storeProp: {
    type: String,
    default: '',
  },
  itemData: {
    type: Object,
    default: null,
  },
  index: {
    type: Number,
    default: null,
  },
  type: {
    type: String,
    default: '',
  },
  loading: {
    type: Boolean,
    default: false,
  },
  currency: {
    type: [Object, String],
    required: true,
  },
  invoiceItems: {
    type: Array,
    required: true,
  },
  itemValidationScope: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update', 'remove', 'itemValidate'])

const companyStore = useCompanyStore()
const itemStore = useItemStore()

let route = useRoute()
const { t } = useI18n()

const quantity = computed({
  get: () => {
    return props.itemData.quantity
  },
  set: (newValue) => {
    updateItemAttribute('quantity', parseFloat(newValue))
  },
})

const price = computed({
  get: () => {
    const price = props.itemData.price

    if (parseFloat(price) > 0) {
      return price / 100
    }
  
    return price
  },

  set: (newValue) => {
    if (parseFloat(newValue) > 0) {
      let price = Math.round(newValue * 100)

      updateItemAttribute('price', price)
    } else {
      updateItemAttribute('price', newValue)
    }
  },
})

const subtotal = computed(() => props.itemData.price * props.itemData.quantity)

const discount = computed({
  get: () => {
    return props.itemData.discount
  },
  set: (newValue) => {
    if (props.itemData.discount_type === 'percentage') {
      updateItemAttribute('discount_val', (subtotal.value * newValue) / 100)
    } else {
      updateItemAttribute('discount_val', Math.round(newValue * 100))
    }

    updateItemAttribute('discount', newValue)
  },
})

const total = computed(() => {
  return subtotal.value - props.itemData.discount_val
})

const selectedCurrency = computed(() => {
  if (props.currency) {
    return props.currency
  } else {
    return companyStore.selectedCompanyCurrency
  }
})

const showRemoveButton = computed(() => {
  if (props.store[props.storeProp].items.length == 1) {
    return false
  }
  return true
})

const totalSimpleTax = computed(() => {
  return Math.round(
    sumBy(props.itemData.taxes, function (tax) {
      if (!tax.compound_tax) {
        return tax.amount
      }
      return 0
    })
  )
})

const totalCompoundTax = computed(() => {
  return Math.round(
    sumBy(props.itemData.taxes, function (tax) {
      if (tax.compound_tax) {
        return tax.amount
      }
      return 0
    })
  )
})

const totalTax = computed(() => totalSimpleTax.value + totalCompoundTax.value)

const rules = {
  name: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  quantity: {
    required: helpers.withMessage(t('validation.required'), required),
    minValue: helpers.withMessage(
      t('validation.qty_must_greater_than_zero'),
      minValue(0)
    ),
    maxLength: helpers.withMessage(
      t('validation.amount_maxlength'),
      maxLength(20)
    ),
  },
  price: {
    required: helpers.withMessage(t('validation.required'), required),
    minValue: helpers.withMessage(
      t('validation.number_length_minvalue'),
      minValue(1)
    ),
    maxLength: helpers.withMessage(
      t('validation.price_maxlength'),
      maxLength(20)
    ),
  },
  discount_val: {
    between: helpers.withMessage(
      t('validation.discount_maxlength'),
      between(
        0,
        computed(() => subtotal.value)
      )
    ),
  },
  description: {
    maxLength: helpers.withMessage(
      t('validation.notes_maxlength'),
      maxLength(65000)
    ),
  },
}

const v$ = useVuelidate(
  rules,
  computed(() => props.store[props.storeProp].items[props.index]),
  { $scope: props.itemValidationScope }
)

//
// if (
//   route.params.id &&
//   (props.store[props.storeProp].tax_per_item === 'YES' || 'NO')
// ) {
//   if (props.store[props.storeProp].items[props.index].taxes === undefined) {
//     props.store.$patch((state) => {
//       state[props.storeProp].items[props.index].taxes = [
//         { ...TaxStub, id: Guid.raw() },
//       ]
//     })
//   }
// }

function updateTax(data) {
  props.store.$patch((state) => {
    state[props.storeProp].items[props.index]['taxes'][data.index] = data.item
  })

  let lastTax = props.itemData.taxes[props.itemData.taxes.length - 1]

  if (lastTax?.tax_type_id !== 0) {
    props.store.$patch((state) => {
      state[props.storeProp].items[props.index].taxes.push({
        ...TaxStub,
        id: Guid.raw(),
      })
    })
  }

  syncItemToStore()
}

function searchVal(val) {
  updateItemAttribute('name', val)
}

let isEdit = false
var itemsFetched = false

onMounted(() => {
  if(route.params.id !== undefined){
    isEdit = true

    async function fetchAllItems(){
      try {
        await getAllItems()
        itemsFetched = true
      }
      catch(error){
        
      }
    }
    
    fetchAllItems()
  }
})

async function getAllItems(){
    let itemResponse = await itemStore.fetchItems();
    if(itemResponse.data.data){
      let itemIndex = 0
      props.store[props.storeProp].items.forEach((eachItem) => {
        let invoiceItem = itemResponse.data.data.find(item => item.id === parseInt(eachItem.item_id))
        if(invoiceItem !== undefined){
          props.store[props.storeProp].items[itemIndex].price_inr = invoiceItem.price
          props.store[props.storeProp].items[itemIndex].price_aed = invoiceItem.price_aed * 100
          props.store[props.storeProp].items[itemIndex].price_us = invoiceItem.price_us * 100
          props.store[props.storeProp].items[itemIndex].price_saarc = invoiceItem.price_saarc * 100
          props.store[props.storeProp].items[itemIndex].price_row = invoiceItem.price_row * 100
        }
        itemIndex++
      })
    }
}

function onSelectItem(itm) {
  
    props.store.$patch((state) => {
      
      if(!isEdit || isEdit){
        state[props.storeProp].items[props.index].name = itm.name
        if(props.currency.zoho_code == 'INR'){
          state[props.storeProp].items[props.index].price = itm.price
        }
        else if(props.currency.zoho_code == 'AED'){
          state[props.storeProp].items[props.index].price = itm.price_aed * 100
        }
        else if(props.currency.zoho_code == 'SAARC'){
          state[props.storeProp].items[props.index].price = itm.price_saarc * 100
        }
        else if(props.currency.zoho_code == 'USD'){
          state[props.storeProp].items[props.index].price = itm.price_us * 100
        }
        else if(props.currency.zoho_code == 'ROW'){
          state[props.storeProp].items[props.index].price = itm.price_row * 100
        }
        else{
          state[props.storeProp].items[props.index].price = itm.price
        }

        state[props.storeProp].items[props.index].price_inr = itm.price
        state[props.storeProp].items[props.index].price_aed = itm.price_aed * 100
        state[props.storeProp].items[props.index].price_row = itm.price_row * 100
        state[props.storeProp].items[props.index].price_us= itm.price_us * 100
        state[props.storeProp].items[props.index].price_saarc = itm.price_saarc * 100

        state[props.storeProp].items[props.index].item_id = itm.id
        state[props.storeProp].items[props.index].description = itm.description

        if (itm.unit) {
          state[props.storeProp].items[props.index].unit_name = itm.unit.name
        }

        if (props.store[props.storeProp].tax_per_item === 'YES' && itm.taxes) {
          let index = 0

          itm.taxes.forEach((tax) => {
            updateTax({ index, item: { ...tax } })
            index++
          })
        }

        if (state[props.storeProp].exchange_rate) {
          state[props.storeProp].items[props.index].price /=
            state[props.storeProp].exchange_rate
        }
      }
    })


  itemStore.fetchItems()
  syncItemToStore()

}

function selectFixed() {
  if (props.itemData.discount_type === 'fixed') {
    return
  }

  updateItemAttribute('discount_val', Math.round(props.itemData.discount * 100))
  updateItemAttribute('discount_type', 'fixed')
}

function selectPercentage() {
  if (props.itemData.discount_type === 'percentage') {
    return
  }

  updateItemAttribute(
    'discount_val',
    (subtotal.value * props.itemData.discount) / 100
  )

  updateItemAttribute('discount_type', 'percentage')
}

function syncItemToStore() {
  let itemTaxes = props.store[props.storeProp]?.items[props.index]?.taxes

  if (!itemTaxes) {
    itemTaxes = []
  }

  let data = {
    ...props.store[props.storeProp].items[props.index],
    index: props.index,
    total: total.value,
    sub_total: subtotal.value,
    totalSimpleTax: totalSimpleTax.value,
    totalCompoundTax: totalCompoundTax.value,
    totalTax: totalTax.value,
    tax: totalTax.value,
    taxes: [...itemTaxes],
  }

  props.store.updateItem(data)
}

function updateItemAttribute(attribute, value) {
  props.store.$patch((state) => {
    state[props.storeProp].items[props.index][attribute] = value
  })

  syncItemToStore()
}

watch(
  () => props.currency.zoho_code,
  (newCurrencyCode, oldCurrencyCode) => {
   
    props.store[props.storeProp].items.forEach(function(propItem){
      
      if(!isEdit){
        if(newCurrencyCode == 'INR'){
          propItem.price = propItem.price_inr
        }
        else if(newCurrencyCode == 'AED'){
          propItem.price = propItem.price_aed
        }
        else if(newCurrencyCode == 'USD'){
          propItem.price = propItem.price_us
        }
        else if(newCurrencyCode == 'SAARC'){
          propItem.price = propItem.price_saarc
        }
        else if(newCurrencyCode == 'ROW'){
          propItem.price = propItem.price_row
        }
        else{
          propItem.price = propItem.price_inr
        }

        itemStore.fetchItems()
        syncItemToStore()
      }else{
        if(newCurrencyCode !== undefined && itemsFetched){
          if(newCurrencyCode == 'INR'){
            propItem.price = propItem.price_inr
          }
          else if(newCurrencyCode == 'AED'){
            propItem.price = propItem.price_aed
          }
          else if(newCurrencyCode == 'USD'){
            propItem.price = propItem.price_us
          }
          else if(newCurrencyCode == 'SAARC'){
            propItem.price = propItem.price_saarc
          }
          else if(newCurrencyCode == 'ROW'){
            propItem.price = propItem.price_row
          }
          else{
            propItem.price = propItem.price_inr
          }

          itemStore.fetchItems()
          syncItemToStore()
        }
       
      }
      
    })

  },
  { deep: true }
);

</script>
