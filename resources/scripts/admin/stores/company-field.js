import axios from 'axios'
import { defineStore } from 'pinia'
import { handleError } from 'vue'
import { useNotificationStore } from '@/scripts/stores/notification'
import companyFieldStub from '@/scripts/admin/stub/company-field'

export const useCompanyFieldStore = (useWindow = false) => {
    const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
    const  { global } = window.i18n

    return defineStoreFunc({
        id: 'company-field',

        state: () => ({
            companyFields: [],

            currentCompanyField: {
                ...companyFieldStub,
              },
        }),

        getters: {
            isEdit() {
              return this.currentCompanyField.id ? true : false
            },
          },

        actions: {

            resetCompanyFields() {
                this.companyFields = []
              },
        
            resetCurrentCompanyField() {
                this.currentCompanyField = {
                    ...companyFieldStub,
                }
            },
            
            fetchCompanyField(id) {
                return new Promise((resolve, reject) => {
                    axios.get(`/api/v1/company-fields/${id}`, {})
                    .then((response) => {
                        this.currentCompanyField = response.data.data
                        if (
                            this.currentCompanyField.options &&
                            this.currentCompanyField.options.length
                        ) {
                            this.currentCompanyField.options =
                            this.currentCompanyField.options.map((option) => {
                                return (option = { name: option })
                            })
                        }

                        resolve(response)
                    })
                    .catch((err) => {
                        handleError(err)
                        reject(err)
                    })
                })
            },

            fetchCompanyFields(params) {
                return new Promise((resolve, reject) => {
                    axios.get(`/api/v1/company-fields`, { params })
                    .then((response) => {
                        resolve(response)
                    })
                    .catch((err) => {
                        handleError(err)
                        reject(err)
                    })
                })
            },

            addCompanyField(params){
                const notificationStore = useNotificationStore()
                return new Promise((resolve, reject) => {
                    axios.post(`/api/v1/company-fields`, params)
                    .then((response) => {
                        if(response.status === 205){
                            notificationStore.showNotification({
                                type: "error",
                                message: "This column already exists!",
                            })
                        } else {
                            notificationStore.showNotification({
                                type: "success",
                                message: global.t('settings.company_fields.added_message'),
                            })
                        }
                        resolve(response)
                    })
                    .catch((err) => {
                        handleError(err)
                        reject(err)
                    })
                })
            },

            updateCompanyField(params) {
                const notificationStore = useNotificationStore()
        
                return new Promise((resolve, reject) => {
                  axios
                    .put(`/api/v1/company-fields/${params.id}`, params)
                    .then((response) => {
                      let data = {
                        ...response.data.data,
                      }
        
                      if (data.options) {
                        data.options = data.options.map((option) => {
                          return { name: option ? option : '' }
                        })
                      }
        
                      let pos = this.companyFields.findIndex((_f) => _f.id === data.id)
        
                      if (this.companyFields[pos]) {
                        this.companyFields[pos] = data
                      }
        
                      notificationStore.showNotification({
                        type: 'success',
                        message: global.t('settings.company_fields.updated_message'),
                      })
                      resolve(response)
                    })
                    .catch((err) => {
                      handleError(err)
                      reject(err)
                    })
                })
            },

            deleteCompanyFields(id) {
                const notificationStore = useNotificationStore()
                return new Promise((resolve, reject) => {
                  axios
                    .delete(`/api/v1/company-fields/${id}`)
                    .then((response) => {
                      let index = this.companyFields.findIndex(
                        (field) => field.id === id
                      )
        
                      this.companyFields.splice(index, 1)
        
                      if (response.data.error) {
                        notificationStore.showNotification({
                          type: 'error',
                          message: global.t('settings.company_fields.already_in_use'),
                        })
                      } else {
                        notificationStore.showNotification({
                          type: 'success',
                          message: global.t('settings.company_fields.deleted_message'),
                        })
                      }
                      resolve(response)
                    })
                    .catch((err) => {
                      handleError(err)
                      // notificationStore.showNotification({
                      //   type: 'error',
                      //   message: global.t('settings.custom_fields.already_in_use'),
                      // })
                      reject(err)
                    })
                })
            },
        }
    })()
}