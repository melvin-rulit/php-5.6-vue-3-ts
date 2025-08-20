import { defineStore } from 'pinia'

interface FormResponse {
  application_id: string
  classifier: string
}

export const useFormsStore = defineStore('forms', {
  state: () => ({
    results: {} as Record<string, FormResponse>,
    error: null as string | null,
  }),
  actions: {
    setFormResult(result: FormResponse) {
      this.results[result.classifier] = result
    },
    setError(err: string) {
      this.error = err
    },
    clearError() {
      this.error = null
    }
  }
})
