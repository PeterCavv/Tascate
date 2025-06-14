<script setup>
import {computed, ref} from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {useI18n} from "vue-i18n";
import InputIcon from "primevue/inputicon";
import IconField from "primevue/iconfield";

const {t} = useI18n();

defineOptions({
    layout: MainLayout
})

const props = defineProps({
    managers: Array,
    can: Object,
});

const showDeleteModal = ref(false);
const managerToDelete = ref(null);

const form = useForm({
    manager_id: null,
});

const confirmDelete = (manager) => {
    managerToDelete.value = manager;
    form.manager_id = manager.id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    managerToDelete.value = null;
    form.reset();
};

const deleteManager = () => {
    form.delete(route('managers.destroy', form.manager_id), {
        onSuccess: () => closeDeleteModal(),
    });
};

const tascas = computed(() => {
    return [
        ...new Map(
            props.managers.map(e => [e.tasca.id, e.tasca])
        ).values()
    ];
});

const searchByName = ref('');
const searchByTascaName = ref('');

const filteredManagers = computed(() => {
    return props.managers.filter(manager => {
        const name = manager.user?.name ?? '';
        const nameMatches = name.toLowerCase().includes(searchByName.value.toLowerCase());

        const tascaFilter = searchByTascaName.value;
        const tascaMatches = !tascaFilter || manager.tasca.id === tascaFilter.id;

        return nameMatches && tascaMatches;
    });
});
</script>

<template>
  <Head :title="t('messages.managers.title')" />

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <section aria-labelledby="proposals-heading">
            <h1 id="proposals-heading" class="text-3xl font-bold text-gray-800">{{ t('messages.managers.title') }}</h1>
            <p class="text-gray-600 mt-1">{{ t('messages.managers.desc') }}</p>
        </section>

        <section class="flex items-center gap-3" aria-labelledby="search-filter">
            <IconField>
                <InputIcon class="pi pi-search" />
                <InputText
                    v-model="searchByName"
                    placeholder="Busca por nombre"
                    class="w-full max-w-[800px]"
                />
            </IconField>
            <Select
                v-if="$page.props.auth.is_admin"
                v-model="searchByTascaName"
                filter
                showClear
                :options="tascas"
                optionLabel="name"
                placeholder="Busca por tasca"
                class="w-full md:w-56"
            />
        </section>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card v-for="manager in filteredManagers" :key="manager.id" class="hover:shadow-lg transition-shadow">
            <template #header>
              <div class="flex items-center space-x-4 p-4">
                <Avatar :image="manager.user.avatar || '/default-avatar.png'" :label="manager.user.name.charAt(0)" size="large" shape="circle" />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ manager.user.name }}
                  </p>
                  <p class="text-sm text-gray-500 truncate">
                    {{ manager.user.email }}
                  </p>
                <p class="text-sm text-gray-500 truncate">
                    {{ manager.tasca.name }}
                </p>
                </div>
              </div>
            </template>
            <template #footer>
              <div class="flex justify-end space-x-2">
                <Link :href="route('managers.show', manager)">
                  <Button icon="pi pi-eye" severity="info" text rounded />
                </Link>
                <Link :href="route('managers.edit', manager)">
                  <Button icon="pi pi-pencil" severity="success" text rounded />
                </Link>
                <Button icon="pi pi-trash" severity="danger" text rounded @click="confirmDelete(manager)" />
              </div>
            </template>
          </Card>
        </div>
      </div>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:visible="showDeleteModal" modal header="Confirmar eliminación" :style="{ width: '450px' }">
      <div class="p-6">
        <p class="text-sm text-gray-600">
          ¿Estás seguro de que quieres eliminar este manager? Esta acción no se puede deshacer.
        </p>
        <div class="mt-6 flex justify-end space-x-3">
          <Button label="Cancelar" severity="secondary" @click="closeDeleteModal" />
          <Button
            label="Eliminar"
            severity="danger"
            :loading="form.processing"
            :disabled="form.processing"
            @click="deleteManager"
          />
        </div>
      </div>
    </Dialog>
</template>
