import axios from 'axios'
import { defineStore } from 'pinia'
import { useNotificationStore } from '@/scripts/stores/notification'
import { reject } from 'lodash'
import { handleError } from 'vue'

export const useSyncStore = (useWindow = false) => {
    const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
    const { global } = window.i18n
    const notificationStore = useNotificationStore()
    
  return defineStoreFunc({
    id: 'sync',

    actions: {
        fetchCrmProducts() {
            return new Promise((resolve, reject) => [
                axios
                  .get(`/api/v1/company/crm-products`)
                  .then((response) => {
                    if(response.data){
                        if(response.data.code === 'INVALID_TOKEN'){
                            notificationStore.showNotification({
                                type: "error",
                                message: "Invalid OAuth Token. Please generate a fresh access token to continue.",
                            })
                        }
                    }
                    resolve(response)
                  })
                  .catch((err) => {
                    handleError(err)
                    reject(err)
                  })
            ])
        },

        fetchCrmUsers() {
          return new Promise((resolve, reject) => [
            axios
              .get(`/api/v1/company/crm-users`)
              .then((response) => {
                  if(response.data){
                    if(response.data.code === 'INVALID_TOKEN'){
                      notificationStore.showNotification({
                        type: "error",
                        message: "Invalid OAuth Token. Please generate a fresh access token to continue.",
                      })
                    }
                  }
                resolve(response)
              })
              .catch((err) => {
                handleError(err)
                reject(err)
              })
          ])
        },

        fetchTableColumns(params){
            return new Promise((resolve, reject) => [
                axios
                  .get(`/api/v1/company/table-columns`, {
                    params: params
                  })
                  .then((response) => {
                    resolve(response)
                  })
                  .catch((err) => {
                    handleError(err)
                    reject(err)
                  })
            ])
        },

        submitSyncSettings(data, params){
            return new Promise((resolve, reject) => [
                axios
                  .put(`/api/v1/company/company-field-mapping/${params.table_name}`, data)
                  .then((response) => {
                    notificationStore.showNotification({
                        type: "success",
                        message: response.data.success,
                    })
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