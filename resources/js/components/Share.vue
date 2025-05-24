<!-- Projects/Share.vue -->
<template>
  <div class="text-black space-y-2">
    <input v-model="form.user_id" placeholder="ID del usuario" class="border border-gray-300 rounded-md px-3 py-2 w-full text-black focus:outline-none focus:ring-blue-500 focus:border-blue-500 shadow-sm">
    <select v-model="form.role" class="border border-gray-300 rounded-md px-3 py-2 w-full text-black focus:outline-none focus:ring-blue-500 focus:border-blue-500 shadow-sm">
      <option value="admin">Administrador</option>
      <option value="member">Miembro</option>
    </select>
    <button @click="share" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition shadow">Compartir</button>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ projectId: Number })

const form = useForm({
  user_id: '',
  role: 'member'
})

function share() {
  form.post(`/projects/${props.projectId}/share`)
}
</script>
