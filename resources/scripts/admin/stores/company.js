import axios from 'axios'
import { defineStore } from 'pinia'
import { useNotificationStore } from '@/scripts/stores/notification'
import { handleError } from '@/scripts/helpers/error-handling'
import Ls from '@/scripts/services/ls'
import { reject } from 'lodash'

export const useCompanyStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'company',

    state: () => ({
      companies: [],
      selectedCompany: null,
      selectedCompanySettings: {},
      selectedCompanyCurrency: null,
    }),

    actions: {
      setSelectedCompany(data) {
        window.Ls.set('selectedCompany', data.id)
        this.selectedCompany = data
      },

      fetchBasicMailConfig() {
        return new Promise((resolve, reject) => {
          axios
            .get('/api/v1/company/mail/config')
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateCompany(data) {
        return new Promise((resolve, reject) => {
          axios
            .put('/api/v1/company', data)
            .then((response) => {
              const notificationStore = useNotificationStore()

              notificationStore.showNotification({
                type: 'success',
                message: global.t('settings.company_info.updated_message'),
              })

              this.selectedCompany = response.data.data

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateCompanyLogo(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/company/upload-logo', data)
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateTransparentLogo(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/company/upload-transparent-logo`, data)
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addNewCompany(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/companies', data)
            .then((response) => {
              const notificationStore = useNotificationStore()
              notificationStore.showNotification({
                type: 'success',
                message: global.t('company_switcher.created_message'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchCompany(params) {
        return new Promise((resolve, reject) => {
          axios
            .get('/api/v1/current-company', params)
            .then((response) => {
              Object.assign(this.companyForm, response.data.data.address)
              this.companyForm.name = response.data.data.name
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchUserCompanies() {
        return new Promise((resolve, reject) => {
          axios
            .get('/api/v1/companies')
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchCompanySettings(settings) {
        return new Promise((resolve, reject) => {
          axios
            .get('/api/v1/company/settings', {
              params: {
                settings,
              },
            })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateCompanySettings({ data, message }) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/company/settings', data)
            .then((response) => {
              Object.assign(this.selectedCompanySettings, data.settings)

              if (message) {
                const notificationStore = useNotificationStore()

                notificationStore.showNotification({
                  type: 'success',
                  message: global.t(message),
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

      deleteCompany(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/companies/delete`, data)
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      setDefaultCurrency(data) {
        this.defaultCurrency = data.currency
      },
      
      crmConfiguration({data, message}) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/company/crm-config`, {
              params: data
            })
            .then((response) => {
              if(Object.hasOwn(response, 'data')){
                if(Object.hasOwn(response.data, 'redirect_location')){
                    window.location.href = response.data.redirect_location
                }
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchCrmSyncs({data, message}){
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/company/crm-syncs`, {
              params: data
            })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateZohoSync({data, message}){
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/company/crm-syncs`, {
              params: data
            })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      companySettingsByDomain(data){
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/company/domain-settings`, {
              params: data
            })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateItemColumns(data){
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/company/update-item-columns`, {
              params: data
            })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      }
      
    },
  })()
}
