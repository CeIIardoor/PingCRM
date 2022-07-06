<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/collaborateurs">Collaborateurs</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
    <trashed-message v-if="collaborateur.deleted_at" class="mb-6" @restore="restore"> This collaborateur has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" />
          <select-input v-model="form.organisation_id" :error="form.errors.organisation_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Organisation">
            <option :value="null" />
            <option v-for="organisation in organisations" :key="organisation.id" :value="organisation.id">{{ organisation.name }}</option>
          </select-input>
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
          <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="Address" />
          <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="City" />
          <text-input v-model="form.region" :error="form.errors.region" class="pb-8 pr-6 w-full lg:w-1/2" label="Province/State" />
          <select-input v-model="form.country" :error="form.errors.country" class="pb-8 pr-6 w-full lg:w-1/2" label="Country">
            <option :value="null" />
            <option value="CA">Canada</option>
            <option value="US">United States</option>
          </select-input>
          <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="Postal code" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!collaborateur.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Collaborateur</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Collaborateur</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    collaborateur: Object,
    organisations: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.collaborateur.first_name,
        last_name: this.collaborateur.last_name,
        organisation_id: this.collaborateur.organisation_id,
        email: this.collaborateur.email,
        phone: this.collaborateur.phone,
        address: this.collaborateur.address,
        city: this.collaborateur.city,
        region: this.collaborateur.region,
        country: this.collaborateur.country,
        postal_code: this.collaborateur.postal_code,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/collaborateurs/${this.collaborateur.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this collaborateur?')) {
        this.$inertia.delete(`/collaborateurs/${this.collaborateur.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this collaborateur?')) {
        this.$inertia.put(`/collaborateurs/${this.collaborateur.id}/restore`)
      }
    },
  },
}
</script>
