<template>
  <div class="bg-gray-100 p-3 md:p-4 rounded-lg shadow-md w-80 md:w-96 flex-shrink-0 self-start">
    <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-gray-700">Crear Nuevo Proyecto</h2>
    <form @submit.prevent="emit('create-project')" class="space-y-3 md:space-y-4">
      <input v-model="newProject.name" type="text" placeholder="Nombre del nuevo proyecto" class="border border-gray-300 p-2 w-full rounded-md focus:ring-1 focus:ring-blue-500 shadow-sm text-black" required />
      <textarea v-model="newProject.description" placeholder="Descripción (opcional)" class="border border-gray-300 p-2 w-full rounded-md h-20 md:h-24 resize-none focus:ring-1 focus:ring-blue-500 shadow-sm text-black"></textarea>
      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 w-full font-semibold shadow hover:shadow-md transition-all">
        🚀 Crear Proyecto
      </button>
    </form>
  </div>
</template>

<script setup>
const props = defineProps(['newProject'])
const emit = defineEmits(['create-project'])
</script>
