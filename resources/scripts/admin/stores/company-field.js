import axios from 'axios'
import { defineStore } from 'pinia'
import { handleError } from 'vue'
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
            }
        }
    })()
}