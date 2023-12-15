import axios from 'axios'
import { defineStore } from 'pinia'
import { useNotificationStore } from '@/scripts/stores/notification'
import { reject } from 'lodash'
import { handleError } from 'vue'

export const useSyncStore = (useWindow = false) => {
    const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
    const { global } = window.i18n

    
  return defineStoreFunc({
    id: 'sync',

    actions: {
        fetchCrmProducts(){
            return new Promise((resolve, reject) => [
                axios
                  .get(`/api/v1/company/crm-products`)
                  .then((response) => {
                    resolve(response)
                  })
                  .catch((err) => {
                    handleError(err)
                    reject(err)
                  })
            ])
        },

        fetchItemColumns(){
            return new Promise((resolve, reject) => [
                axios
                  .get(`/api/v1/company/item-columns`)
                  .then((response) => {
                    resolve(response)
                  })
                  .catch((err) => {
                    handleError(err)
                    reject(err)
                  })
            ])
        }
    }
  })()
}