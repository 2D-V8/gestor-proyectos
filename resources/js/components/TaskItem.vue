<script setup>
import { reactive, watch } from 'vue'

const props = defineProps(['task'])
const emit = defineEmits(['update-task', 'delete-task'])

// Creamos copia reactiva local
const localTask = reactive({ ...props.task })

// Cuando prop task cambie (por ejemplo al recargar la lista), actualizamos localTask
watch(() => props.task, (newTask) => {
  Object.assign(localTask, newTask)
})

// Emitimos los cambios con la copia local actualizada
const emitUpdate = () => {
  emit('update-task', { ...localTask })
}
</script>

<template>
  <div class="bg-white p-3 rounded-md shadow border border-gray-200 hover:shadow-lg transition-shadow space-y-2">
    <!-- Usamos v-model con localTask -->
    <input
      v-model="localTask.title"
      @blur="emitUpdate"
      class="w-full font-semibold text-gray-800 border-b border-gray-200 focus:outline-none focus:border-blue-500"
      placeholder="Título de la tarea"
    />
    <textarea
      v-model="localTask.description"
      @blur="emitUpdate"
      class="w-full text-sm text-gray-600 bg-gray-50 rounded p-1 border border-gray-200 focus:outline-none focus:border-blue-400"
      placeholder="Descripción"
      rows="2"
    ></textarea>
    <select
      v-model="localTask.status"
      @change="emitUpdate"
      class="w-full text-sm text-gray-600 bg-white border border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-blue-500"
    >
      <option disabled value="">Estado</option>
      <option value="todo">Por hacer</option>
      <option value="in_progress">En progreso</option>
      <option value="done">Hecha</option>
    </select>
    <input
      v-model="localTask.due_date"
      @blur="emitUpdate"
      type="date"
      class="w-full text-sm text-gray-600 border border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-blue-500"
    />
    <div class="flex justify-end">
      <button
        @click="emit('delete-task', localTask.id)"
        class="text-red-500 text-sm hover:underline"
      >
        Eliminar
      </button>
    </div>
  </div>
</template>
