import {ref} from "vue";
import tascaService from "@/Services/tascaService.js";

export function useTasca() {

    const tascas = ref([]);
    const tasca = ref({});
    const error = ref(null);
    const meta = ref({});
    const links = ref([]);

    /**
     * Fetch all tascas from the API. If no page is provided, it defaults to 1.
     * @returns {Promise<void>}
     */
    const fetchTascas = async (page = 1) => {
        error.value = null;

        try{
            const response = await tascaService.getAll(page);
            tascas.value = response.data;
            meta.value = response.meta;
            links.value = response.links;
        } catch {
            error.value = e.message || 'Error fetching users'
        }
    };

    /**
     * Fetch a single tasca by ID from the API
     * @param id Tasca's ID
     * @returns {Promise<void>}
     */
    const fetchTasca = async (id) => {
        error.value = null;

        try{
            const response = await tascaService.getById(id);
            tasca.value = response.data;
        } catch {
            error.value = e.message || 'Error fetching tasca'
        }
    };

    /**
     * Delete a tasca
     * @param id Tasca's ID
     * @returns {Promise<void>}
     */
    const deleteTasca = async (id) => {
        error.value = null;

        try {
            await tascaService.deleteById(id);
            tascas.value = tascas.value.filter(tasca => tasca.id !== id);
        } catch {
            error.value = e.message || 'Error deleting tasca'
        }
    };

    /**
     * Update a tasca
     * @param id Tasca's ID
     * @param data Tasca's data
     * @returns {Promise<void>}
     */
    const updateTasca = async (id, data) => {
        error.value = null;

        try {
            await tascaService.updateById(id, data);
            await fetchTasca(id);
        } catch {
            error.value = e.message || 'Error updating tasca'
        }
    };

    return {
        tascas,
        tasca,
        error,
        meta,
        links,
        fetchTascas,
        fetchTasca,
        deleteTasca,
        updateTasca
    }
}
