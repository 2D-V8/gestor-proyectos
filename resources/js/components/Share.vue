<!-- Projects/Share.vue -->
<template>
  <div>
    <input v-model="form.user_id" placeholder="ID del usuario">
    <select v-model="form.role">
      <option value="admin">Administrador</option>
      <option value="member">Miembro</option>
    </select>
    <button @click="share">Compartir</button>
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
